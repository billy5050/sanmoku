<?php 
require_once 'db_connect.php';
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
                <?php for ($i = 0; $i < 3; $i++) {?>
                    <tr>
                        <?php for ($y = 3; $y < 6; $y++) {
                            $z = 0;
                            print '<td>
                                <select name="'.$i.$y.'_'.$z.'">
                                    <option value="null" selected"></option>
                                    <option value="circle">〇</option>
                                </select>
                            </td>';
                        }?>
                    </tr>
                <?php }?>
              </table>
            <button class="act" type="submit">プレイ</button>
          </form>
    </body>
</html>