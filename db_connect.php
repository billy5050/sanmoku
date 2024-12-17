<?php
$db_user = "root";
$db_pass = "";
$db_host = "localhost";
$db_name = "gomoku";
$db_type = "mysql";

$dsn = "$db_type:host=$db_host;dbname=$db_name;charset=utf8";

// PDO接続を行う関数を定義
function getPdoConnection() {
    global $db_user, $db_pass, $dsn;
    try {
        // 新しいPDOインスタンスを返す
        $pdo = new PDO($dsn, $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    } catch (PDOException $Exception) {
        die('エラー: ' . $Exception->getMessage());
    }
}
?>