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
      </form>
    </body>
</html>