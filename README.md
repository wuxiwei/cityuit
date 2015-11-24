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
#####备注
应用必须通过此接口登陆验证。一，获取个人信息及im帐号。二，保证用户信息在云数据库存储。
####2.下单
`curl -d 'ordernum=订单号&ordermenu=订单&ordermealusername=学号' http://server.sinaapp.com/ordermeal.php`
#####请求
```
1.ordernum=订单号  订单号=学号+时间戳（秒）  
2.ordermenu={  
"ordernum":"订单号",  
"where":"七号楼东",  
"phone":"18940961111",  
"ordermeal":[{"st":"二食堂","dk":"香包包","sl":"2","dj":"7","cm":"蛋炒饭"},{"st":"二食堂","dk":"豪大大鸡排","sl":"1","dj":"12","cm":"铁板鸡排"}],  
"price":"26",  
"reward":"3",  
"remark":"亲，帮帮忙"  
}
```
#####响应
-`{"status":"internal error","content":"post null"}`POST请求数据为空  
-`{"status":"ok"}`订单生成成功  
-`{"status":"internal error"}`内部错误
#####IM接收
```
通知所有送餐人有人订餐
{  
	"target_type":"users",  // users 给用户发消息, chatgroups 给群发消息  
	"target":["sendimb","sendimc"], // 送餐人im帐号（数组）  
	"msg":{  //消息内容  
		"type":"cmd",  //消息类型 （透传） 
		"action":"ordermenu"  //信息获取（客户端主要获取数据）菜单信息  
	},  
	"from":"ordermanusername",  //下单人学号（不使用）  
	"ext":{   //消息类型  
		"object":"send",   //面向送餐人  
		"status":"new"    /代表有新单  
	}  
}
```
#####备注
菜单json嵌套json，下单实现购物车模式。监听im信息，接收消息根据消息类型判断，object表示对哪类用户有价值，否则不用处理。status状态指消息意图。下同！
####3.抢单
`curl -d 'ordernum=订单号&takeorderusername=学号' http://server.sinaapp.com/takeorder.php`
#####请求
#####响应
-`{"status":"internal error","content":"post null"}`POST请求数据为空  
-`{"status":"take success"}`抢单成功  
-`{"status":"take failed"}`单被抢，抢单失败  
-`{"status":"internal error"}`内部错误
#####IM接收
```
1.通知订单人有人抢单
{  
	"target_type":"users",  // users 给用户发消息, chatgroups 给群发消息  
	"target":["ordermealmanim"], // 订餐人im帐号（数组）  
	"msg":{  //消息内容  
		"type":"cmd",  //消息类型 （透传） 
		"action":"takeorderman"  //信息获取（客户端主要获取数据）送餐人信息（学号，手机号）  
	},  
	"from":"takeorderusername",  //送餐人学号（不使用）  
	"ext":{   //消息类型  
		"object":"user",   //面向订餐人  
		"status":"taked"    /代表单被抢  
	}  
}
2.通知所有送餐人某单已被抢
{  
	"target_type":"users",  // users 给用户发消息, chatgroups 给群发消息  
	"target":["sendimb","sendimc"], // 送餐人im帐号（数组）  
	"msg":{  //消息内容  
		"type":"cmd",  //消息类型 （透传） 
		"action":"ordernum"  //信息获取（客户端主要获取数据）订单号  
	},  
	"from":"takeorderusername",  //送餐人学号（不使用）  
	"ext":{   //消息类型  
		"object":"send",   //面向送餐人  
		"status":"taked"    /代表单被抢  
	}  
}
```
#####备注
注意处理im信息接收关系
####未完待续