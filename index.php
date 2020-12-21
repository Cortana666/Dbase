<?php

$file_url = 'upload/'.$_GET['file_url'];

require_once('class/Dbase.php');
$DBF = new Dbase();
$res = $DBF->readAllAssoc($file_url);

$data = $res['data'];
$title = $data[0];
unset($data[0]);
$header = $res['header'];
$headTitle = array('type', 'length', 'precision', 'format', 'offset');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .body {
            width: 999999px;
        }

        .content {
            height: 90%;
            overflow: scroll;
        }
    </style>
</head>

<body>
    <div class="body">
        <form action="save.php" method="post">
            <table>
                <thead>
                    <tr>
                        <?php
                        foreach ($title as $key => $value) {
                            echo '<th><div><input name="'.$value.'_name" style="width: 200px;" type="text" value="'.$value.'"></div></th>';
                        }
                        ?>
                    </tr>
                </thead>
            </table>
            <div class="content">
                <table>
                    <?php
                    foreach ($data as $key => $value) {
                        echo '<tr>';
                        foreach ($value as $key1 => $value1) {
                            if (in_array($key1, $title)) {
                                echo '<td><input name="'.$key1.'_'.$key.'" style="width: 200px;" type="text" value="'.$value1.'"></td>';
                            }
                        }
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>
            <?php
            foreach ($headTitle as $key => $value) {
                foreach ($header as $key1 => $value1) {
                    echo '<input name="'.$value1['name'].'_'.$value.'" style="width: 200px;" type="hidden" value="'.$value1[$value].'">';
                }
            }
            ?>
            <input type="submit" value="保存">
            <a href="index.html">重新选择</a>
        </form>
    </div>
</body>

</html>