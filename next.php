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
            header("Location: imdex.php");
            exit; // ここでスクリプトの実行を停止
        } catch (PDOException $e) {
            echo "データベースエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
        // リセット後の処理が必要であればここに記述
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
        $index = 0;
        // データベースに追加
        $sql = "INSERT INTO test (place, process, time) VALUES (:place, :process, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':place', $place, PDO::PARAM_STR);
        $stmt->bindParam(':process', $process, PDO::PARAM_STR);
        $stmt->execute();
        //現在の状況を取得
        $stmtMy = $pdo->query("SELECT * FROM test ORDER BY time DESC;");
        $turns = $stmtMy->fetchAll(PDO::FETCH_ASSOC);
        $place = $turns[0]["place"];
        $process = $turns[0]["process"];
        $stmtCpu = $pdo->query("SELECT * FROM cpu;");
        $cpu = $stmtCpu->fetchAll(PDO::FETCH_ASSOC);
        // ランダム結果が今までの場所と重複していたら、抽選しなおす（CPUの操作）
//         $ram = array_rand($grid, 1);
//         foreach ($turns as $index => $turn) {
//             foreach ($cpu as $index => $cp) {
//                 while (in_array($grid[$ram], (array)$turn["place"]) || in_array($grid[$ram], (array)$cp["place"]) || $grid[$ram] == $place) {
//                     $ram = array_rand($grid, 1);
//                 }
//             }
//         }
        $winSelf = array_column($turns, 'place');
        $windCpu = array_column($cpu, 'place');
        
        //プレーヤーの勝利判定
        if (in_array("03", $winSelf) && in_array("04", $winSelf) && in_array("05", $winSelf) || 
            in_array("03", $winSelf) && in_array("13", $winSelf) && in_array("23", $winSelf) || 
            in_array("03", $winSelf) && in_array("14", $winSelf) && in_array("25", $winSelf) ||
            in_array("13", $winSelf) && in_array("14", $winSelf) && in_array("15", $winSelf) || 
            in_array("23", $winSelf) && in_array("24", $winSelf) && in_array("25", $winSelf) || 
            in_array("05", $winSelf) && in_array("14", $winSelf) && in_array("23", $winSelf) || 
            in_array("04", $winSelf) && in_array("14", $winSelf) && in_array("24", $winSelf) ||
            in_array("05", $winSelf) && in_array("15", $winSelf) && in_array("25", $winSelf))
        {
            // リセット後に `index.php` へリダイレクト
            header("Location: result.php");
            exit; // ここでスクリプトの実行を停止
        }
        // ランダム結果が今までの場所と重複していたら、抽選しなおす（CPUの操作）
        $usedPlaces = array_merge(
            array_column($turns, 'place'),
            array_column($cpu, 'place')
            );
        do {
            $ram = array_rand($grid, 1); // ランダムな値を生成
        } while (in_array($grid[$ram], $usedPlaces));
        
        $sql2 = "INSERT INTO cpu (place, time) VALUES (:place, NOW())";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->bindParam(':place', $grid[$ram], PDO::PARAM_STR);
        $stmt2->execute();
        $stmtCpu = $pdo->query("SELECT * FROM cpu;");
        $cpu = $stmtCpu->fetchAll(PDO::FETCH_ASSOC);
        
        //CPUの処理判定
        if (in_array("03", $windCpu) && in_array("04", $windCpu) && in_array("05", $windCpu) ||
            in_array("03", $windCpu) && in_array("13", $windCpu) && in_array("23", $windCpu) ||
            in_array("03", $windCpu) && in_array("14", $windCpu) && in_array("25", $windCpu) ||
            in_array("13", $windCpu) && in_array("14", $windCpu) && in_array("15", $windCpu) ||
            in_array("23", $windCpu) && in_array("24", $windCpu) && in_array("25", $windCpu) ||
            in_array("05", $windCpu) && in_array("14", $windCpu) && in_array("23", $windCpu) ||
            in_array("04", $windCpu) && in_array("14", $windCpu) && in_array("24", $windCpu) ||
            in_array("05", $windCpu) && in_array("15", $windCpu) && in_array("25", $windCpu))
        {
            // リセット後に `index.php` へリダイレクト
            header("Location: result2.php");
            exit; // ここでスクリプトの実行を停止
        }
    } catch (PDOException $e) {
        echo "データベースエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
}
// try {
//     $stmtMy = $pdo->query("SELECT * FROM test;");
//     $turns = $stmtMy->fetchAll(PDO::FETCH_ASSOC); // 結果を取得
// } catch (Exception $e) {
//     echo "手順の取得に失敗しました。";
// }
//初回のcpuデータ取得
try {
    $stmtCpu = $pdo->query("SELECT * FROM cpu;");
    $cpu = $stmtCpu->fetchAll(PDO::FETCH_ASSOC); // 結果を取得
} catch (Exception $e) {
    echo "手順の取得に失敗しました。";
}

function left($str, $num, $encoding = "UTF-8"){
    return mb_substr($str, 0, $num, $encoding);
}
function right($str, $num, $encoding = "UTF-8"){
    return mb_substr($str, $num * (-1), $num, $encoding);
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