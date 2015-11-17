<?php
/*
登陆帐号
*/
include ("./database/database.php");//连接数据库
$username=$_POST['username'];//接收客户端发来的username；
$password=$_POST['password'];
echo $username.$password;
/**
 * CURL Post
 */
function postCurl($url, $option, $header = 0, $type = 'POST') {
	$curl = curl_init (); // 启动一个CURL会话
	curl_setopt ( $curl, CURLOPT_URL, $url ); // 要访问的地址
	curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, FALSE ); // 对认证证书来源的检查
	curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, FALSE ); // 从证书中检查SSL加密算法是否存在
	curl_setopt ( $curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)' ); // 模拟用户使用的浏览器
	if (! empty ( $option )) {
		$options = json_encode ( $option );
		curl_setopt ( $curl, CURLOPT_POSTFIELDS, $options ); // Post提交的数据包
	}
	curl_setopt ( $curl, CURLOPT_TIMEOUT, 30 ); // 设置超时限制防止死循环
	@curl_setopt ( $curl, CURLOPT_HTTPHEADER, $header ); // 设置HTTP头
	//print_r($header);
	curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 获取的信息以文件流的形式返回
	curl_setopt ( $curl, CURLOPT_CUSTOMREQUEST, $type );
	$result = curl_exec ( $curl ); // 执行操作
	curl_close ( $curl ); // 关闭CURL会话
	return $result;
}


