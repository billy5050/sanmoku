<?php
require_once 'db_connect.php';
$pdo = getPdoConnection();

$z = 0;
// 最新の試合結果を取得
$Score = $pdo->query("SELECT game_result FROM score ORDER BY time DESC")->fetch(PDO::FETCH_ASSOC);
$result = $Score['game_result'] ?? 'unknown';

// プレーヤーの行動を全て取得
$stmtMy = $pdo->query("SELECT * FROM test ORDER BY time DESC;");
$turns = $stmtMy->fetchAll(PDO::FETCH_ASSOC);
$stmtCpu = $pdo->query("SELECT * FROM cpu;");
$cpu = $stmtCpu->fetchAll(PDO::FETCH_ASSOC);

// テーブルリセット処理（ゲーム終了時）
if (in_array($result, ['win', 'lose', 'drow'])) {
    try {
        $pdo->exec("DELETE FROM test");
        $pdo->exec("DELETE FROM cpu");
    } catch (PDOException $e) {
        echo "データベースエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
}

$advice = [
    "勝ちたい時は中央から攻めてみよう！",
    "CPUのパターンを読んでみて。",
    "次回は1ターン目で相手の動きを予測してみよう！"
];
$randomAdvice = $advice[array_rand($advice)];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ゲーム結果</title>
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: 'Arial', sans-serif;
        }
</style>
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
    <div>
        <?php if ($result === 'win'): ?>
            <h2>おめでとう！あなたの勝ちです！</h2>
        <?php elseif ($result === 'lose'): ?>
            <h2>残念！あなたの負けです！</h2>
            <p>アドバイス: <?= $randomAdvice ?></p>
        <?php elseif ($result === 'drow'): ?>
            <h2>引き分けです！次回は頑張りましょう！</h2>
            <p>アドバイス: <?= $randomAdvice ?></p>
        <?php endif; ?>
    </div>
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
    <a href="index.php">もう一度プレイする</a>
</body>
</html>
