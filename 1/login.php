<?php
/*
登陆帐号
*/
include ("./database/database.php");//连接数据库
include ("./im/basedim.php");
include ("./fun.php");
include ("./config.php");
$username=$_POST['username'];//接收客户端发来的username；
$password=$_POST['password'];
if(!postnull([$username,$password])){
    $usrInfo = array('status'=>'internal error','content'=>'post null');
    echoinf($usrInfo);
    exit();
}

$url = $LOGINLINK;
// 参数数组
$data = [
    "username"=>$username,
    "password"=>$password,
];
$this_header = [
    "content-type"=>"application/x-www-form-urlencoded", 
    "charset"=>"UTF-8"
];
$ch = curl_init (); // 启动一个CURL会话
curl_setopt($ch,CURLOPT_HTTPHEADER,$this_header);
curl_setopt ( $ch, CURLOPT_URL, $url );
curl_setopt ( $ch, CURLOPT_POST, 1 );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query($data) );
$linkresult = json_decode(curl_exec ( $ch ), true);  //获取json数据并解析成array
curl_close ( $ch ); // 关闭CURL会话
switch($linkresult['status']){
    case 'ok':
        try{
            $sql="SELECT * FROM `register_user` WHERE `username` = '$username'";
            $resultsql = $pdo->query($sql);
            if($row = $resultsql->fetch()){     //如果已经存在该帐号
                $userinf = array('status'=>'ok','man'=>$row['name'],'im'=>$row['im']);
                echoinf($userinf);
            }else{
                $imuser = array('username'=>$username, 'password'=>$username);
                $registerRes = json_decode($IM->accreditRegister($imuser), true);
                //print_r($registerRes);
                if($registerRes['action'] == 'post'){
                    //环信注成功以后才将用户信息写入数据库(密码帐号都是学号)
                    $sql_in="INSERT INTO `register_user` ( `username`, `password`, `name`, `im`) VALUES ('$username', '$password', '$linkresult[man]', '$username');";
                    $pdo->exec($sql_in);
                    $userinf = array('status'=>'ok','man'=>$linkresult['man'],'im'=>$username);
                    echoinf($userinf);
                }else{
                    $usrInfo = array('status'=>'internal error','content'=>'im error');
                    echoinf($usrInfo);
                    exit();
                }
            }
        }catch(PDOException $e){
            $usrInfo = array('status'=>'internal error','content'=>$e->getMessage());
            echoinf($usrInfo);
            exit();
        }
        break;
    case 'login failed':
        echoinf($linkresult);
        break;
    case 'internal error':
        echoinf($linkresult);
        break;
    case 'School network connection failure':
        echoinf($linkresult);
        break;
    default:
        echoinf($linkresult);
}
