<?php

$file_name = $_FILES['file']['name'];
$file_url = 'upload/'.$file_name;
if (!copy($_FILES['file']['tmp_name'], $file_url)) {
    echo '<a href="index.html">请重新上传！</a>';
    exit;
}

header("Location:/dbase/index.php?file_url=".$file_name);

?>