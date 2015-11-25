<?php

include("./database/database.php");
$userID=$_POST['userID'];
$password=$_POST['password'];
$tel=$_POST['tel'];
$name=$_POST['name'];
$pas="sui bian ding yi yi ge zhi";
try{
	foreach ($pdo->query("select password from register_user where username=".$userID." ") as $arr) {
		$pas=$arr['password'];
	}

	if($pas==$password){
		$pdo->exec("insert into register_send_user set username='".$userID."',phone='".$tel."',im='".$userID."',name='".$name."' ");
		echo "登记成功，请等待工作人员审核  :)";
	}else{
		echo "错误的学号或密码";
	}
}catch(PDOException $e){
	echo "内部错误";
}
?>