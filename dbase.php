<?php
// require_once('class/Dbase.php');
if ($_GET['act'] == 'upload') {
    $file = 'upload/file_'.date('YmdHis').'dbf';
    copy($_FILES['file']['tmp_name'], $file);
    $dbf = dbase_open($file, 2);
    $head = dbase_get_header_info($dbf);
    $title = array_column($head, 'name');
    $line = dbase_numrecords($dbf);
    for ($i=1; $i <= $line; $i++) { 
        $val = dbase_get_record_with_names($dbf, $i);
        foreach ($title as $key => $value) {
            $data[$i][$value] = trim(mb_convert_encoding($val[$value], 'UTF-8', 'GBK'));
        }
    }
    dbase_close($dbf);

    $content = '<html>
        <head>
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
                <form action="dbase.php?act=submit" method="post">
                    <table>
                        <thead>
                            <tr>';
    
    foreach ($title as $key => $value) {
        $content .= '<th><div><input name="'.$value.'_name" style="width: 200px;" type="text" value="'.$value.'"></th>';
    }

    $content .= '</tr></thead>
        </table>
        <div class="content">
            <table>';

    foreach ($data as $key => $value) {
        $content .= '<tr>';
        foreach ($value as $key1 => $value1) {
            $content .= '<td><input name="'.$key1.'_'.$key.'" style="width: 200px;" type="text" value="'.$value1.'"></td>';
        }
        $content .= '</tr>';
    }

    $content .= '</table>
                    </div>';

    $headTitle = array('type', 'length', 'precision', 'format', 'offset');
    foreach ($headTitle as $key => $value) {
        foreach ($head as $key1 => $value1) {
            $content .= '<input name="'.$value1['name'].'_'.$value.'" style="width: 200px;" type="hidden" value="'.$value1[$value].'">';
        }
    }
    
    $content .= '<input type="submit" value="保存"><a href="index.html">重新选择</a>
                </form>
            </div>
        </body>
        </html>';

    echo $content;
    exit;
}

if ($_GET['act'] == 'submit') {
    foreach ($_POST as $key => $value) {
        $key = explode("_", $key);
        if (is_numeric($key[1])) {
            $data[$key[1]][$key[0]] = trim(mb_convert_encoding($value, 'GBK', 'UTF-8'));
        } else {
            $head[$key[0]][] = $value;
        }
    }

    $head = array_values($head);
    $file = "create/file_".date('YmdHis').".dbf";
    $dbf = dbase_create($file, $head, DBASE_TYPE_FOXPRO);
    
    foreach ($data as $key => $value) {
        $val = array_values($value);
        dbase_add_record($dbf, $val);
    }
    dbase_close($dbf);

    echo '<html><body><div><a href="'.$file.'">下载文件</a><a href="index.html">重新选择</a></div></body></html>';
    exit;
}
?>