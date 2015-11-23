<?php
/*
用户订餐
*/
include ("./database/database.php");//连接数据库
include ("./im/basedim.php");
include ("./fun.php");
$ordernum=$_POST['ordernum'];//接收客户端发来的；
$ordermenu=$_POST['ordermenu'];
$ordermealusername=$_POST['ordermealusername'];
//$ordermanim=$_POST['ordermanim'];
if(!postnull([$ordernum,$ordermenu,$ordermealusername])){
    $usrInfo = array('status'=>'internal error','content'=>'post null');
    echoinf($usrInfo);
    exit();
}

$time = date('y-m-d h:i:s',time());   //下单时间
try{
    $sql_in="INSERT INTO `order_meal` ( `ordernum`, `orderstate`, `ordermealman`, `sendmealman`, `ordermenu`, `orderstart`, `ordersuccess`, `orderend`) VALUES ('$ordernum', 'n', '$ordermealusername', '', '$ordermenu', '$time', '', '');";
    $pdo->exec($sql_in);
    $senduser = getAllSendIm($pdo);   //获取所有送餐人im帐号
    $ordermanim=getim($ordermealusername,$pdo);    //获取im帐号
    $usrInfo = array('status'=>'ok');
    $sendRes = json_decode($IM->xx_hxSend($senduser,$ordermenu,$usrInfo,$ordermanim),true);
    //print_r($sendRes);
    if($sendRes['action'] == 'post'){
        echoinf($usrInfo);
    }else{
        $usrInfo = array('status'=>'internal error','content'=>'im error');
        echoinf($usrInfo);
        exit();
    }
}catch(PDOException $e){
    $usrInfo = array('status'=>'internal error','content'=>$e->getMessage());
    echoinf($usrInfo);
    exit();
}
