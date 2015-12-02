<?php
include("./database/database.php");
include ("./fun.php");
$username=$_POST["username"];
$need=$_POST["need"];

if(!postnull([$username,$need])){
    $usrInfo = array('status'=>'internal error','content'=>'post null');
    echoinf($usrInfo);
    exit();
}

switch($need){
	//历史送餐记录
	case 'HS':
		foreach($pdo->query("select * from old_orders where sendmealman = '$username' ") as $arr){
			unset($arr['ordermenu']);
			echoinf($arr);
        }
        break;
	//历史订餐记录
	case 'HO':
		foreach($pdo->query("select * from old_orders where ordermealman = '$username' ") as $arrr){
			unset($arrr['ordermenu']);
			echoinf($arrr);
		}
        break;
	//送餐人正在进行订单
	case 'SING':
		foreach($pdo->query("select * from order_meal where sendmealman = '$usermane' ") as $arrrr){
			unset($arrrr['ordermenu']);
			echoinf($arrrr);
		}
        break;
	//订餐人正在进行订单
	case 'OING':
		foreach($pdo->query("select * from order_meal where ordermealman = '$usermane' ") as $arrrr){
			unset($arrrr['ordermenu']);
			echoinf($arrrr);
		}
        break;
	default:
		$userInfo=array('status'=>' "need" error','content'=>'the need is null');
		echoinf($userInfo);
		exit;
}	

?>
