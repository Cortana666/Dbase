<?php

foreach ($_POST as $key => $value) {
    $key = explode("_", $key);
    if (is_numeric($key[1])) {
        $data[$key[1]][$key[0]] = trim(mb_convert_encoding($value, 'GBK', 'UTF-8'));
    } else {
        $header[$key[0]][] = $value;
    }
}

$header = array_values($header);
$file_url = "create/file_".date('YmdHis').".dbf";
foreach ($data as $key => $value) {
    $data[$key] = array_values($value);
}

require_once('class/Dbase.php');
$DBF = new Dbase();
$DBF->addAll($file_url, $header, $data);

echo '<html><body><div><a href="'.$file_url.'">下载文件</a><a href="index.html">重新选择</a></div></body></html>';
exit;

?>