<?php

class Dbase {

    function __construct() {

    }


    // 打开dbf
    public function open($file_url) {
        return dbase_open($file_url, 2);
    }

    // 创建dbf
    public function create($file_url, $field) {
        return dbase_create($file_url, $field, DBASE_TYPE_FOXPRO);
    }

    // 关闭dbf
    public function close($resource) {
        return dbase_close($resource);
    }

    public function getHeader($resource) {
        return dbase_get_header_info($resource);
    }

    // 获取数据总行数
    public function getLines($resource) {
        return dbase_numrecords($resource);
    }

    // 读取一行数据（索引数组）
    public function read($resource, $line) {
        $row = dbase_get_record($resource, $line);
        foreach ($row as $key => $value) {
            $row[$key] = trim(mb_convert_encoding($value, 'UTF-8', 'GBK'));
        }
        return $row;
    }

    // 读取一行数据（关联数组）
    public function readAssoc($resource, $line) {
        $row = dbase_get_record_with_names($resource, $line);
        foreach ($row as $key => $value) {
            $row[$key] = trim(mb_convert_encoding($value, 'UTF-8', 'GBK'));
        }
        return $row;
    }

    // 添加一行数据
    public function add($resource, $row) {
        return dbase_add_record($resource, $row);
    }

    // 读取所有数据
    public function readAllAssoc($file_url) {
        $resource = $this->open($file_url);
        $lines = $this->getLines($resource);
        $header = $this->getHeader($resource);
        $data[] = array_column($header, 'name');
        for ($i=1; $i <= $lines; $i++) { 
            $data[] = $this->readAssoc($resource, $i);
        }
        $this->close($resource);
        return array('data'=>$data, 'header'=>$header);
    }

    // 添加所有数据
    public function addAll($file_url, $field, $data) {
        $resource = $this->create($file_url, $field);
        foreach ($data as $key => $value) {
            $this->add($resource, $value);
        }
        $this->close($resource);
        return true;
    }

}
?>