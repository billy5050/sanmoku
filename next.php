<?php
require_once 'db_connect.php';
$pdo = getPdoConnection();

$z = 0;
$place = "";
$process = "";
$grid = [];
for ($index = 0; $index < 3; $index++) {
    for ($column = 3; $column < 6; $column++) {
        array_push($grid, $index.$column);
    }
}
// フォーム送信処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $index => $value) {
        if ($value == "circle") {
            if (right($index, 1) == 0 && $value !== "null") {
                $place = left($index, 2);
                $process = $value;
            }
        }
    }
    try {
        // プレーヤーの行動をデータベースに追加
        $sql = "INSERT INTO test (place, process, time) VALUES (:place, :process, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':place', $place, PDO::PARAM_STR);
        $stmt->bindParam(':process', $process, PDO::PARAM_STR);
        $stmt->execute();
        
        // プレーヤーとCPUの行動を全て取得
        $stmtMy = $pdo->query("SELECT * FROM test ORDER BY time DESC;");
        $turns = $stmtMy->fetchAll(PDO::FETCH_ASSOC);
        $stmtCpu = $pdo->query("SELECT * FROM cpu;");
        $cpu = $stmtCpu->fetchAll(PDO::FETCH_ASSOC);
        
        // 今までの行動を配列に格納
        $winSelf = array_column($turns, 'place');
        winF($winSelf, "win", $pdo); // 勝利判定（プレイヤー）
        
        // ランダム結果が今までの場所と重複していたら、抽選しなおす（CPUの操作）
        $usedPlaces = array_merge(
            array_column($turns, 'place'),
            array_column($cpu, 'place')
            );
        
        if (count($usedPlaces) < count($grid)) {
            do {
                $ram = array_rand($grid, 1); // ランダムな値を生成
            } while (in_array($grid[$ram], $usedPlaces));
            
            // CPUの行動をデータベースに記録
            $sql2 = "INSERT INTO cpu (place, time) VALUES (:place, NOW())";
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->bindParam(':place', $grid[$ram], PDO::PARAM_STR);
            $stmt2->execute();
            $stmtCpu = $pdo->query("SELECT * FROM cpu;");
            $cpu = $stmtCpu->fetchAll(PDO::FETCH_ASSOC);
            $winCpu = array_column($cpu, 'place');
            winF($winCpu, "lose", $pdo); // 勝利判定（CPU）
        }
        
        if (count($usedPlaces) === count($grid)) {
            // 引き分けをデータベースに保存
            $stmt = $pdo->prepare("INSERT INTO score (game_result, time) VALUES ('drow', NOW())");
            $stmt->execute();
            
            // 引き分けとしてリダイレクト
            header("Location: result.php?result=drow");
            exit;
        }
    } catch (PDOException $e) {
        echo "データベースエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
}

// 左の文字列を指定の数取得
function left($str, $num, $encoding = "UTF-8") {
    return mb_substr($str, 0, $num, $encoding);
}

// 右の文字列を指定の数取得
function right($str, $num, $encoding = "UTF-8") {
    return mb_substr($str, $num * (-1), $num, $encoding);
}

//勝利判定
function winF($win, $resultValue, $pdo) {
    $isWin = in_array("03", $win) && in_array("04", $win) && in_array("05", $win) ||
    in_array("03", $win) && in_array("13", $win) && in_array("23", $win) ||
    in_array("03", $win) && in_array("14", $win) && in_array("25", $win) ||
    in_array("13", $win) && in_array("14", $win) && in_array("15", $win) ||
    in_array("23", $win) && in_array("24", $win) && in_array("25", $win) ||
    in_array("05", $win) && in_array("14", $win) && in_array("23", $win) ||
    in_array("04", $win) && in_array("14", $win) && in_array("24", $win) ||
    in_array("05", $win) && in_array("15", $win) && in_array("25", $win);
    
    if ($isWin) {
        // 勝利または敗北の結果をデータベースに保存
        $result = ($resultValue === "win") ? "win" : "lose";
        $stmt = $pdo->prepare("INSERT INTO score (game_result, time) VALUES (:result, Now())");
        $stmt->bindParam(':result', $result, PDO::PARAM_STR);
        $stmt->execute();   
        // リダイレクト
        header("Location: result.php?result=$resultValue");
        exit;
    }
}
?>

<!doctype html>
<html lang="jp">
<head> 
	<meta charset="UTF-8" />
	<title>Document</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
    <header class="stats-header">
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">🎮</div>
                <div class="stat-info">
                    <p class="stat-label">試合数</p>
                    <p class="stat-value"><?= $totalGames ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🏆</div>
                <div class="stat-info">
                    <p class="stat-label">勝利数</p>
                    <p class="stat-value"><?= $wins ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">😔</div>
                <div class="stat-info">
                    <p class="stat-label">敗北数</p>
                    <p class="stat-value"><?= $losses ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🤝</div>
                <div class="stat-info">
                    <p class="stat-label">引き分け数</p>
                    <p class="stat-value"><?= $draws ?></p>
                </div>
            </div>
        </div>
    </header>
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
                                            <option value="circle" disabled selected>〇</option>
                                        </select>';
                                    } elseif ($turn["process"] == "cross") {
                                        $cellContent = '<select name="'.$i.$y.'_'.$z.'">
                                            <option value="cross" disabled selected>×</option>
                                        </select>';
                                    }
                                    break; // 該当する要素が見つかったらループを抜ける
                                }
                                //CPUの処理
                                foreach ($cpu as $index => $cp) {
                                    if ($cp["place"] == "{$i}{$y}") {
                                            $matched = true; // 条件に一致したフラグを立てる
                                            $cellContent = '<select name="'.$i.$y.'">
                                                    <option value="cross" disabled selected>×</option>
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
            <button type="submit">プレイ</button>
        </form>
</body>
</html>