<?php
include("./database/database.php");
$username=$_POST["username"];
$need=$_POST["need"];

if(!postnull([$username,$need])){
    $usrInfo = array('status'=>'internal error','content'=>'post null');
    echoinf($usrInfo);
    exit();
}

switch($need){
	//历史送餐记录
	case HS:
		foreach($pdo->query("select * from old_orders where sendmealman = '$username' ") as $arr){
			echoinf($arr);
		}
	//历史订餐记录
	case HO:
		foreach($pdo->query("select * from old_orders where ordermealman = '$username' ") as $arrr){
			echoinf($arrr);
		}
	//正在订餐记录
	case Oing:
		foreach($pdo->query("select * from order_meal where ordermealman = '$usermane' ") as $arrrr){
			echoinf($arrrr);
		}
	//正在送餐记录
	case Sing:
		foreach($pdo->query("select * from order_meal where sendmealman = '$username' ")as $arrrrr){
			echoinf($arrrrr);
		}
	default:
		$userInfo=array('status'=>' "need" error','content'=>'the need is null');
		echoinf($userInfo);
		exit;
	
}	


?>