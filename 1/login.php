<?php
/*
登陆帐号
*/
include ("./database/database.php");//连接数据库
$username=$_POST['username'];//接收客户端发来的username；
$password=$_POST['password'];

//$url = "http://127.0.0.1:5000/api/login";
$url = "http://120.27.53.146:5000/api/login";
//$url = "http://1.cityuit.sinaapp.com/1.php";
// 参数数组
$data = [
    "username"=>$username,
    "password"=>$password,
];
$ch = curl_init (); // 启动一个CURL会话
curl_setopt ( $ch, CURLOPT_URL, $url );
curl_setopt ( $ch, CURLOPT_POST, 1 );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query($data) );
$result = curl_exec ( $ch ); // 执行操作
curl_close ( $ch ); // 关闭CURL会话
echo $result;


