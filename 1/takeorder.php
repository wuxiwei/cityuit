<?php
/*
用户抢单
*/
include ("./database/database.php");//连接数据库
include ("./im/basedim.php");
include ("./fun.php");
$ordernum=$_POST['ordernum'];//接收客户端发来的；
$takeorderusername=$_POST['takeorderusername'];

if(!postnull([$ordernum,$takeorderusername])){
    $usrInfo = array('status'=>'internal error','content'=>'post null');
    echoinf($usrInfo);
    exit();
}
try{
    $sql_se = "SELECT * FROM `order_meal` WHERE `ordernum` = '$ordernum' and `orderstate` = 'n'";
    $resultsql = $pdo->query($sql_se);
    if($row = $resultsql->fetch(PDO::FETCH_ASSOC)){     //如果满足条件则抢单成功
        $time = date('y-m-d h:i:s',time());   //抢单时间
        $sql_up="UPDATE `order_meal` SET `orderstate` = 's', `ordersuccess` = '$time', `sendmealman` = '$takeorderusername' WHERE `ordernum` = '$ordernum';";  //
        $pdo->exec($sql_up);
        $takeordermanphone=getphone($takeorderusername,$pdo);    //获取送餐人手机号
        $ordermanim=getim($row['ordermealman'],$pdo);    //获取订餐人im帐号
        $takeordermanim=getim($takeorderusername,$pdo);  //获取送餐人im帐号
        $usrInfo = array('status'=>'take success');
        echoinf($usrInfo);
    }else{
        $usrInfo = array('status'=>'take failed');
        echoinf($usrInfo);
    }
}catch(PDOException $e){
    $usrInfo = array('status'=>'internal error','content'=>$e->getMessage());
    echoinf($usrInfo);
    exit();
}
