#PHP版本 城市学院小助手送餐接口sae备份
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
####2.获取可抢状态订单
`curl http://server.sinaapp.com/neworder.php`
#####请求
注：GET请求
#####响应
-`{"status":"null"}`没有可抢订单  
-`{"status":"internal error"}`内部错误  
-`{"status":"订单数组"}`返回所有可抢订单,具体格式如下
```
{"orders":[{"ordernum":"2013120131448714557413","where":"六号楼东区999","phone":"15941116409","reward":"1","remark":"","price":"7.0","ordermeal":[{"st":"二食堂","dk":"汁味堂特色美食屋","sl":"1","dj":"6.0","cm":"银耳肉末"}]},{"ordernum":"2013120131448713303032","where":"六号楼东区999","phone":"15941116409","reward":"1","remark":"","price":"7.0","ordermeal":[{"st":"二食堂","dk":"汁味堂特色美食屋","sl":"1","dj":"6.0","cm":"银耳肉末"}]}]}
```
#####备注
每次进入送餐界面获取最新状况
####3.下单
`curl -d 'ordernum=订单号&ordermenu=订单&ordermealusername=订餐人学号' http://server.sinaapp.com/ordermeal.php`
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
		"action":"ordermenu"  //信息获取（客户端主要获取数据）菜单信息 json格式  
	},  
	"from":"ordermanusername",  //下单人学号（不使用）  
	"ext":{   //消息类型  
		"object":"send",   //面向送餐人  
		"status":"new"    /代表有新单  
	}  
}
```
#####备注
菜单json嵌套json，下单实现购物车模式。监听im信息，接收消息根据消息类型判断，object表示对哪类用户有价值，否则不用处理。status状态指消息意图。所有im信息只有在登陆监听情况下，后台给予推送。下同！
####4.抢单
`curl -d 'ordernum=订单号&takeorderusername=送餐人学号' http://server.sinaapp.com/takeorder.php`
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
		"action":"takeorderman"  //信息获取（客户端主要获取数据）送餐人信息（单号，学号，手机号） json格式  
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
		"action":"ordernum"  //信息获取（客户端主要获取数据）订单号 json格式  
	},  
	"from":"takeorderusername",  //送餐人学号（不使用）  
	"ext":{   //消息类型  
		"object":"send",   //面向送餐人  
		"status":"taked"   //代表单被抢  
	}  
}
```
#####备注
注意处理im信息接收关系
####5.确认收货
`curl -d 'ordernum=订单号&sendorderusername=送餐人学号' http://server.sinaapp.com/dealorder.php`
#####请求
#####响应
-`{"status":"internal error","content":"post null"}`POST请求数据为空  
-`{"status":"ok"}`确认收获成功  
#####IM接收
```
通知送餐人确认收获
{  
	"target_type":"users",  // users 给用户发消息, chatgroups 给群发消息  
	"target":["sendordermanim"], // 送餐人im帐号（数组）  
	"msg":{  //消息内容  
		"type":"cmd",  //消息类型 （透传） 
		"action":"ordernum"  //信息获取（客户端主要获取数据）订单号 json格式  
	},  
	"from":"admin",  //（不使用）  
	"ext":{   //消息类型  
		"object":"send",   //面向送餐人  
		"status":"end"    //代表单确认收货（结束）  
	}  
}
```
#####备注
后台自动将订单信息以成功状态存至历史订单，只要post数据不为空就返回成功，所有问题后台自动处理，用户当面交易所以不必要返回错误信息。
####6.废弃订单（后台定时触发）
#####请求
#####响应
#####IM接收
```
1.通知订餐人订单时间够长废弃
{  
	"target_type":"users",  // users 给用户发消息, chatgroups 给群发消息  
	"target":["ordermanim"], // 订餐人im帐号（数组）  
	"msg":{  //消息内容  
		"type":"cmd",  //消息类型 （透传） 
		"action":"ordernum"  //信息获取（客户端主要获取数据）订单号 json格式  
	},  
	"from":"admin",  //（不使用）  
	"ext":{   //消息类型  
		"object":"user",   //面向送餐人  
		"status":"fail"    //代表单确认收货（结束）  
	}  
}
2.通知所有送餐人某单已废弃
{  
	"target_type":"users",  // users 给用户发消息, chatgroups 给群发消息  
	"target":["sendimb","sendimc"], // 送餐人im帐号（数组）  
	"msg":{  //消息内容  
		"type":"cmd",  //消息类型 （透传） 
		"action":"ordernum"  //信息获取（客户端主要获取数据）订单号 json格式  
	},  
	"from":"admin",  //（不使用）  
	"ext":{   //消息类型  
		"object":"send",   //面向送餐人  
		"status":"fail"   //代表单被抢  
	}  
}
```
#####备注
后台自动将订单信息以废弃状态存至历史订单
####7.获取该学号有关的所有单号
#####请求
`curl -d 'username=学号' http://server.sinaapp.com/backordernum.php`
#####响应
-`{"status":"internal error","content":"post null"}`POST请求数据为空  
-`{"status":["2013120281448466270227","2013120281448466493412"]}`json格式返回所有单号  
-`{"status":"internal error"}`内部错误
#####IM接收
#####备注
####8.获取该单号所有详情
#####请求
`curl -d 'ordernum=单号' http://server.sinaapp.com/orderdetall.php`
#####响应
-`{"status":"internal error","content":"post null"}`POST请求数据为空  
-`{"ordernum":"2013120131448707468197","orderstate":"s","ordermealman":"201312050","sendmealman":"201422052","sendmanphone":"18940961111","ordermenu":[{"ordernum":"2013120131448707468197","where":"六号楼东区111","phone":"15941116408","reward":"1","remark":"","price":"6.0","ordermeal":[{"st":"二食堂","dk":"汁味堂特色美食屋","sl":"1","dj":"6.0","cm":"银耳肉末"}]}],"orderstart":"15-11-28 18:44:33","ordersucces":"15-11-28 18:47:13","orderend":""}`json格式返回单号详情  
-`{"status":"order null"}`没有该单号详情  
-`{"status":"internal error"}`内部错误
#####IM接收
#####备注
状态：n ->新订单，可抢单，s->被抢，正在送，o->成功收货，f->失败单。如果没有送餐人，就没有送餐人手机号。
####9.判断该学号是否可以送餐
#####请求
`curl -d 'username=学号' http://server.sinaapp.com/sendable.php`
#####响应
-`{"status":"internal error","content":"post null"}`POST请求数据为空  
-`{"status":"ok"}`可以送餐  
-`{"status":"no"}`不可以送餐  
-`{"status":"internal error"}`内部错误
#####IM接收
#####备注
如果不可以送餐，客户端统一提示：您未申请送餐，请关注微心城院小助手（csxyxzs）申请送餐，感谢使用。
####未完待续
