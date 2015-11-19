<?php
/*
登陆帐号
*/
include ("./database/database.php");//连接数据库
$username=$_POST['username'];//接收客户端发来的username；
$password=$_POST['password'];

$url = "http://120.27.53.146:5000/api/login";
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
$result = json_decode(curl_exec ( $ch ), true);  //获取json数据并解析成array
curl_close ( $ch ); // 关闭CURL会话
switch($result['status']){
case 'ok':
    echo 'io';
        try{
            $sql="SELECT * FROM `register_user` WHERE `username` = '$username'";
            $result = $pdo->query($sql);
            if($row = $result->fetch()){
                $userinf = array('status'=>'ok','man'=>$row[name],'im'=>$row[im]);
                $jsdata=json_encode($usrInfo);
                echo $jsdata;
            }else{
                echo 'jj';
            }
        }catch(PDOException $e){
            $usrInfo = array('status'=>'internal error');
            $jsdata=json_encode($usrInfo);
            echo $jsdata;
            exit();
        }
        break;
    case 'login failed':
        echo 'no';
        break;
    default:
        echo 'oo';
}


