<?php
include ("./database/database.php");//连接数据库
/*
 * 同意输出流处理
 */
function echoinf($options){
    $jsdata=json_encode($options, JSON_UNESCAPED_UNICODE);   //字符编码
    echo $jsdata;
}
/*
 * 判断array是否存在空键值
 */
function postnull($options){
    foreach ($options as $val){
        if(!isset($val)){
            return false;
        }
    }
    return true;
}
/*
 * 获取im帐号
 */
function getim($options,$pdo){
	$sql="SELECT * FROM `register_user` WHERE `username` = '$options'";
    $resultsql = $pdo->query($sql);
    $row = $resultsql->fetch();
    $a = $row['im'];
    return $a;
}
