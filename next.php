<?php
require_once 'db_connect.php';

$place = "";
$process = "";
$z = 0;
$grid = [];
for ($index = 0; $index < 3; $index++) {
    for ($column = 3; $column < 6; $column++) {
        array_push($grid, $index.$column);
    }
}
$ram = array_rand($grid, 1);
$pdo = getPdoConnection();

// フォーム送信処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // リセットボタンが押された場合
    if (isset($_POST['riset'])) {
        try {
            $pdo = getPdoConnection();
            // `test` テーブルと `cpu` テーブルのデータを削除
            $pdo->exec("DELETE FROM test");
            $pdo->exec("DELETE FROM cpu");
            
            // リセット後に `index.php` へリダイレクト
            header("Location: index.php");
            exit; // ここでスクリプトの実行を停止
        } catch (PDOException $e) {
            echo "データベースエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
        exit; // リセット処理の後に通常の処理を続けない
    }
    // 通常の送信処理
    foreach ($_POST as $index => $value) {
        if($value == "circle"){
            if (right($index, 1) == 0 && $value !== "null") {
                $place = left($index, 2);
                $process = $value;
            }
        }
    }
    try {
        $pdo = getPdoConnection();
//         $index = 0;
        // プレーヤーの行動をデータベースに追加
        $sql = "INSERT INTO test (place, process, time) VALUES (:place, :process, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':place', $place, PDO::PARAM_STR);
        $stmt->bindParam(':process', $process, PDO::PARAM_STR);
        $stmt->execute();
        //プレーヤーの行動を全て取得
        $stmtMy = $pdo->query("SELECT * FROM test ORDER BY time DESC;");
        $turns = $stmtMy->fetchAll(PDO::FETCH_ASSOC);
        //最新情報を取得
        $place = $turns[0]["place"];
        $process = $turns[0]["process"];
        //cpuの行動を全て取得
        $stmtCpu = $pdo->query("SELECT * FROM cpu;");
        $cpu = $stmtCpu->fetchAll(PDO::FETCH_ASSOC);
        
        //今までの行動を配列に格納
        $winSelf = array_column($turns, 'place');
        //プレーヤーの勝利判定
        winF($winSelf, "win");
        
        // ランダム結果が今までの場所と重複していたら、抽選しなおす（CPUの操作）
        $usedPlaces = array_merge(
            array_column($turns, 'place'),
            array_column($cpu, 'place')
            );
        do {
            $ram = array_rand($grid, 1); // ランダムな値を生成
        } while (in_array($grid[$ram], $usedPlaces));
        
        //cpuの行動をデータベースから取得
        $sql2 = "INSERT INTO cpu (place, time) VALUES (:place, NOW())";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->bindParam(':place', $grid[$ram], PDO::PARAM_STR);
        $stmt2->execute();
        $stmtCpu = $pdo->query("SELECT * FROM cpu;");
        $cpu = $stmtCpu->fetchAll(PDO::FETCH_ASSOC);
        $winCpu = array_column($cpu, 'place');
        //CPU勝利判定
        winF($winCpu, "lose");

    } catch (PDOException $e) {
        echo "データベースエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
}

//初回のcpuデータ取得
try {
    $stmtCpu = $pdo->query("SELECT * FROM cpu;");
    $cpu = $stmtCpu->fetchAll(PDO::FETCH_ASSOC); // 結果を取得
} catch (Exception $e) {
    echo "手順の取得に失敗しました。";
}

//左の文字列を指定の数取得
function left($str, $num, $encoding = "UTF-8"){
    return mb_substr($str, 0, $num, $encoding);
}

//右の文字列を指定の数取得
function right($str, $num, $encoding = "UTF-8"){
    return mb_substr($str, $num * (-1), $num, $encoding);
}

//勝利判定
function winF($win, $resultValue) {
    if (in_array("03", $win) && in_array("04", $win) && in_array("05", $win) ||
        in_array("03", $win) && in_array("13", $win) && in_array("23", $win) ||
        in_array("03", $win) && in_array("14", $win) && in_array("25", $win) ||
        in_array("13", $win) && in_array("14", $win) && in_array("15", $win) ||
        in_array("23", $win) && in_array("24", $win) && in_array("25", $win) ||
        in_array("05", $win) && in_array("14", $win) && in_array("23", $win) ||
        in_array("04", $win) && in_array("14", $win) && in_array("24", $win) ||
        in_array("05", $win) && in_array("15", $win) && in_array("25", $win))
    {
        // 勝敗の結果をクエリパラメータに付けて遷移
        header("Location: result.php" . '?result=' . $resultValue);
        exit; // ここでスクリプトの実行を停止
    }
}
?>
<!doctype html>
<html lang="en">
<head> 
	<meta charset="UTF-8" />
	<title>Document</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
    <form action="next.php" method="POST">
        <table>
            <?php for ($i = 0; $i < 3; $i++) { ?>
                <tr>
                    <?php for ($y = 3; $y < 6; $y++) {
                        $matched = false; // 条件に一致するかどうかのフラグをリセット
                        $cellContent = ''; // 出力内容を一時保存する変数
                        foreach ($turns as $index => $turn) {
                            if ($turn["place"] == "{$i}{$y}") {
                                $matched = true; // 条件に一致したフラグを立てる
                                $z += 1;
                                if ($turn["process"] == "circle") {
                                    $cellContent = '<select name="'.$i.$y.'_'.$z.'">
                                        <option value="circle"　selected>〇</option>
                                    </select>';
                                } elseif ($turn["process"] == "cross") {
                                    $cellContent = '<select name="'.$i.$y.'_'.$z.'">
                                        <option value="cross" selected>×</option>
                                    </select>';
                                }
                                break; // 該当する要素が見つかったらループを抜ける
                            }
                            //CPUの処理
                            foreach ($cpu as $index => $cp) {
                                if ($cp["place"] == "{$i}{$y}") {
                                        $matched = true; // 条件に一致したフラグを立てる
                                        $cellContent = '<select name="'.$i.$y.'">
                                                <option value="cross" selected>×</option>
                                            </select>';
                                }
                            }
                        }
                        // 一致しなかった場合のデフォルト出力
                        if (!$matched) {
                            $zz = 0;
                            $cellContent = '<select name="'.$i.$y.'_'.$zz.'">
                                <option value="null" selected></option>
                                <option value="circle">〇</option>
                            </select>';
                        }
    
                        // 最終的なセル内容を出力
                        print '<td>'.$cellContent.'</td>';
                    } ?>
                </tr>
            <?php } ?>
        </table>
        <button type="submit">行動する</button>
        <button name="riset" type="submit">リセットする</button>
    </form>
</body>
</html>