<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>了当了</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" type="text/css" href="/css/style.css"/>
	<script src="/js/jquery-1.10.2.js" type="text/javascript" charset="utf-8"></script>
</head>
<body>
<div class="main">
	<div class="share">
		<div class="userInfo">
			<img src="/img/wallhaven-317512.jpg"/>
			<span>小尼尼</span>
		</div>
		<h6 class="yq-text">您的好友邀请您注册《了当了》</h6>
		<div class="yq-code">
			3567220
		</div>
		<div class="share-box">
			<img src="/img/ewm.png" class="ewm"/>
			<img src="/img/720.png" class="share-img"/>
		</div>
		<div class="download">
			<a onclick="downloadFun()">
				<img src="/img/btn_720.png"/>
			</a>
		</div>
	</div>
</div>
</body>
</html>

<script>
	function downloadFun() {
		var u = navigator.userAgent;
		var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
		if(isiOS){
			window.location = "http://a.app.qq.com/o/simple.jsp?pkgname=com.juda.ldlchat";
		} else {
			window.location = "http://a.app.qq.com/o/simple.jsp?pkgname=com.juda.ldlchat";
		}
	}
</script>