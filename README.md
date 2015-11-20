#PHP版本 城市学院小助手sae备份
###接口使用
####1.登陆
`curl -d 'username=学号&password=密码' http://server.sinaapp.com/login.php`
#####请求
#####响应
-`{"status":"internal error","content":"post null"}`POST请求数据为空  
-`{"status":"login failed"}`帐号或密码错误  
-`{"status":"ok","man":"姓名","im":"im帐号"}`登陆成功  
-`{"status":"School network connection failure"}`校网或网络问题  
-`{"status":"internal error"}`内部错误
####2.下单
`curl -d 'ordernum=订单号&ordermenu=订单&ordermealusername=学号' http://server.sinaapp.com/ordermeal.php`
#####请求
-`ordernum=订单号`订单号=学号+时间戳（秒）  
-`ordermenu={
"地址":"七号楼东",
"电话":"18940961111",
"菜单":[{"食堂":"二食堂","挡口":"香包包","数量":"2","单价":"7","菜名":"蛋炒饭"},{"食堂":"二食堂","挡口":"豪大大鸡排","数量":"1","单价":"12","菜名":"铁板鸡排"}],
"总价":"26",
"配送费":"3",
"备注":"亲，帮帮忙"
}` json嵌套json
#####响应
-`{"status":"internal error","content":"post null"}`POST请求数据为空  
-`{"status":"ok"}`成功  
-`{"status":"internal error"}`内部错误
#####IM接收
```
{  
	"target_type":"users",     // users 给用户发消息,  chatgroups 给群发消息  
	"target":["testb","testc"], // 注意这里需要用数组  
                                // 一个用户u1或者群组, 也要用数组形式 ['u1'], 给用户发  
                                // 送时数组元素是用户名,给群组发送时数组元素是groupid  
	"msg":{  //消息内容  
		"type":"cmd",  //消息类型  
		"action":"action1"  //信息获取  
	},  
	"from":"testa",  //表示这个消息是谁发出来的, 可以没有这个属性, 那么就会显示是admin, 如果有的话, 则会显示是这个用户发出的  
	"ext":{   //扩展属性, 由app自己定义.可以没有这个字段，但是如果有，值不能是“ext:null“这种形式，否则出错  
		"attr1":"v1",  
		"attr2":"v2"  
	}  
}
```
####未完待续