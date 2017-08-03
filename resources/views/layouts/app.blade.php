<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel-Auth</title>

    <!-- Styles -->
    <link href="/css/baseold.css" rel="stylesheet">
    <link href="/css/header.css" rel="stylesheet">
    <link href="/css/home.css" rel="stylesheet">
    <!-- Scripts -->
    <script src="/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/js/base.js"></script>
    <script type="text/javascript"  src="/js/jquery.validate.js"></script>
    <script type="text/javascript" src="/js/global.js"></script>
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
<!-- 欢迎来到快学历 -->
<div class="main greyf5f5f5 headerOverflowInherit">
    <div class="minwidth welcomeKfw headerOverflowInherit" id='loginbar'>
        <ul class="overflowhidden floatLeft headerOverflowInherit" >
            <li class="navLocation">
                <a href="javascript:;" class="locatinIcon">
                    <i class="mykfw floatRight"></i>北京市</a>
                <div class="locationLayer">
                    <em></em>
                    <ul>
                        <li><a href="javascript:void(0)">北京</a></li>
                        <li><a href="javascript:void(0)">上海</a></li>
                        <li><a href="javascript:void(0)" >广州</a></li>
                        <li><a href="javascript:void(0)" >深圳</a></li>
                        <li><a href="javascript:void(0)">南京</a></li>
                        <li><a href="javascript:void(0)">杭州</a></li>
                        <li><a href="javascript:void(0)">宁波</a></li>
                        <li><a href="javascript:void(0)">苏州</a></li>
                        <li><a href="javascript:void(0)">成都</a></li>
                        <li><a href="javascript:void(0)">天津</a></li>
                        <li><a href="javascript:void(0)">厦门</a></li>
                        <li><a href="javascript:void(0)">重庆</a></li>
                        <li><a href="javascript:void(0)">武汉</a></li>
                        <li><a href="javascript:void(0)">西安</a></li>
                        <li><a href="javascript:void(0)">全国</a></li>
                    </ul>
                </div>
            </li>
        </ul>
        <ul class="overflowhidden floatRight headerOverflowInherit">
            @if (Auth::guest())
                <li><a class="noBorder" href="{{ url('/auth/login') }}">登录</a></li>
                <li><a href="{{ url('/auth/register') }}">免费注册</a></li>
            @else
                <li><a class="noBorder">欢迎xxx</a></li>
                <li><a class="noBorder" href="{{ url('/auth/logout') }}">退出</a></li>
            @endif
            <li><a href="/user/orders/index.html">我的订单</a></li>
            <li><a class="welcome1" href="/cart/info.html"><i class="gw floatLeft"></i>购物车<em>0</em>件</a></li>
            <li><a href="#" target="_blank">院校入驻</a></li>
            <li><a href="/help/joining.html" target="_blank" style="color:#00a5d5;">助学机构入驻</a></li>
            <li class="asj app">
                <a href="/mobile/index.html">
                    <i class="sj floatLeft"></i><i class="jt floatRight"></i>移动应用
                </a>
                <div class="welcomeKfwShow aimationKfw">
                    <em></em>
                    <img src="/Image/Home_index/kuaixuelierweima.png" alt="微信图片">
                    <h4>关注快学历官方微信</h4>
                    <a class="butTgreen padding14" href="/mobile/index.html">更多移动应用</a>
                </div>
            </li>
        </ul>
        <script type="text/javascript">
            $(function(){
                //欢迎来到快学历中去样式
                $(".asj").on("mouseenter mouseleave",function(e){

                    var obj = $(this).next("li").children("a");

                    if (e.type == "mouseenter") {
                        obj.css("border","none")
                    }else if (e.type == "mouseleave") {
                        obj.css("border-left","1px solid #e3e3e3")
                    };
                });

                var asjWidth = $('.asj').width();
                $('.iMyInfoOk em').css('width',150-asjWidth);
                //市区选择
                $(".locationLayer").on("click","ul li a",function(e){
                    console.log($(e.target).html());
                    $(".locatinIcon").html($(e.target).html()+"市<i class='mykfw floatRight'></i>");
                })
            })
        </script>
    </div>
</div>
<!-- 欢迎来到快学历 -->

<!-- 导航 -->
<div class="main bgWhite border000 headerOverflowInherit" style="z-index: 10;">
    <!-- LOGO -->
    <div class="minwidth kfwLogo">
        <a class="floatLeft" href="index.html">
            <img src="/Image/LOGO1.png" alt="快学历">
        </a>
        <!-- 这里是与info相同却删除的内容 -->
        <div class="logo-sub">欢迎注册</div>
    </div>
    <!-- LOGO -->

</div>
<!-- 导航 -->


<!-- 侧边栏 -->
<ul class="overflowhidden navSidebars headerOverflowInherit">
    <li style="border-top: 1px solid #dddddd;" class="li-1">
        <a href="javascript:void(0);" class="mx-a-1">
            <i class="gwc"></i>
            <div class="mx-green">在线咨询</div>
        </a>
    </li>
    <li>
        <a href="/cart/info.html" id="myCartInfo">
            <i class="wyts"></i>
            <div>购物车</div>
        </a>
    </li>
    <li class="complainLe" style="display:none;border-radius:0 0 0 6px;">
        <a href="javascript:;" class="a-4">
            <i class="fhdb fhdbClick"></i>
            <div>返回顶部</div>
        </a>
    </li>
</ul>
<script type="text/javascript" src="/js/rightfloat.js"></script>
<!-- 侧边栏 -->

@yield('content')

<!-- Scripts -->
<script src="/js/app.js"></script>
</body>
</html>