<?php 
require_once 'db_connect.php';

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
        <button class="act" type="submit">行動する</button>
        <button name="riset" type="submit">リセットする</button>
        <p>※再戦の方は最初に「リセットする」を押してください。</p>
      </form>
    </body>
</html>