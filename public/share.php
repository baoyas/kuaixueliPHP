<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>快学历</title>
<meta name="author" content="JudaMedia">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="format-detection" content="telephone=no" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=no,minimal-ui" />
<style>
body,div,h1,h2,html,i,li,p,span,ul{margin:0;padding:0;border:0;outline:0}
ul{list-style:none}
*{-webkit-tap-highlight-color:transparent;outline:0}
body{font-size:16px;max-width:640px;margin:0 auto;background-color:#fafafa;padding-bottom:56px;font-family:Helvetica}
.wrap{color:#666}
.title{font:20px/20px Helvetica;margin:0 0 5px 0;color:#000}
.sericetype{font:12px/20px bold Helvetica;width:40px;background:#f0f0f0;text-align:center;float:right;border-radius:2px}
.price{color:#fb685d}
.server_price{font-size:20px;font-weight:700}
.server_price:after{content:"元";font-size:14px}
.info{margin-top:2px;font-size:12px;line-height:20px;color:#999;background-image:none,none,linear-gradient(0deg,#ccc,#ccc 50%,transparent 50%),none;background-size:100% 1px;background-repeat:no-repeat;background-position:top,right top,bottom,left top;border:none;padding-bottom:10px}
.user{height:80px;background-color:#fff;margin:10px 0;background-image:linear-gradient(180deg,#ddd,#ddd 50%,transparent 50%),none,linear-gradient(0deg,#ccc,#ccc 50%,transparent 50%),none;background-size:100% 1px;background-repeat:no-repeat;background-position:top,right top,bottom,left top;border:none}
.head{float:left;margin:16px 15px 0 16px}
.head img{width:48px;height:48px;border-radius:24px}
.name{color:#333;line-height:20px;float:left}
.ser_name{padding-top:20px}
.male:before{content:"男"}
.female:before{content:"女"}
.lv{font:12px/16px Helvetica;padding:0 5px;background:#f0f0f0;text-align:center;border-radius:2px;display:inline-block;color:#999;margin-left:5px}
.lv:before{content:"LV"}
.ser_top{padding:15px 15px 10px 15px;background-color:#fff;background-image:linear-gradient(180deg,#ddd,#ddd 50%,transparent 50%);background-size:100% 1px;background-repeat:no-repeat;background-position:top;border:none;position:relative}
.ser_body{padding:0 15px 5px;background-color:#fff;margin-bottom:10px;line-height:25px;background-image:linear-gradient(0deg,#ccc,#ccc 50%,transparent 50%);background-size:100% 1px;background-repeat:no-repeat;background-position:bottom;border:none}
.ser_body img{width:100%;height:auto;margin:5px 0}
.logo{padding:8px 15px;position:fixed;left:0;bottom:0;background-color:#fff;width:100%;box-sizing:border-box;background-image:linear-gradient(180deg,#ddd,#ddd 50%,transparent 50%);background-size:100% 1px;background-repeat:no-repeat;background-position:top;border:none}
.logo a{display:block;float:right;width:60px;height:30px;line-height:30px;text-align:center;font-size:16px;background-color:#20b7a1;color:#fff;text-decoration:none;border-radius:4px;margin-top:5px}
.icon{background:url(/96.png) no-repeat;background-size:40px;padding-left:50px;height:40px}
.icon h1{font-size:20px;line-height:20px;padding-top:2px;color:#000}
.icon p{font-size:12px;line-height:12px;margin-top:4px}
</style>
</head>
<body>
<div class="wrap">
<div class="user">
      <div class="head"></div>
      <div class="name">
        <div class="ser_name"></div>
        <div class="name_info"></div>
      </div>
    </div>
  <div class="ser_top">
    <div class="title">
      <div class="sericetype"></div>
      </div>
    <div class="price"><span class="server_price"></span></div>
    <div class="info"><span class="time"></span></div>
    <!--------------- 头像 ---------------->
    
    
  </div>
  <div class="ser_body">
  
  </div>
</div>
<div class="logo"><a href="./downapp.html">下载</a><div class="icon"><h1>快学历</h1><p>商家版的“微信”</p></div></div>

<script src="http://cdn.bootcss.com/zepto/1.0/zepto.min.js"></script><script>
var id = "<?php echo $_GET['id']; ?>";
var uri = "/api/share/";
$.getJSON(uri+id, function(data) {
	$(".title").append(data.object[0].sell_info[0].sell_title);
	$(".server_price").html(data.object[0].sell_info[0].sell_price);
	(data.object[0].sell_info[0].is_sell == "1")?$(".sericetype").html("买"):$(".sericetype").html("卖");
	//判断地址是否显示，若为空则隐藏address，否则显示地址。
	$(".time").html(data.object[0].sell_info[0].sell_time);
	$(".ser_name").html(data.object[0].sell_info[0].nickname);
	$(".head").html("<img src='" + data.object[0].sell_info[0].user_face + "' />");
$(".ser_body").html(data.object[0].sell_info[0].sell_describe);
	$.each(data.object[0].sell_info[0].sell_pic, function(i, item) {
	$(".ser_body").append("<img src='"+ item +"'/>");
	});
})
</script>
</body>
</html>
