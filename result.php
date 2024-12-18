<?php
$result = isset($_GET['result']) ? $_GET['result'] : 'unknown';

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ゲーム結果</title>
    <style>
        body {
            text-align: center;
            background-color: #f0f8ff;
            font-family: 'Arial', sans-serif;
        }
        .message {
            font-size: 2em;
            margin-top: 20%;
        }
        .win {
            color: green;
        }
        .lose {
            color: red;
        }
        .btn {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 1em;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="message">
        <span class="<?= $result === 'win' ? 'win' : 'lose' ?>">
            <?= $result === 'win' ? 'おめでとう！あなたの勝ちです！' : '残念！あなたの負けです！' .$randomAdvice ?>
        </span>
    </div>
    <button class="btn" onclick="window.location.href='index.php'">もう一度プレイ</button>
</body>
</html>
