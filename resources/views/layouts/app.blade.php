<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>快学历</title>

    <!-- Styles -->
    @if (Request::path()=='auth/login' || Request::path()=='auth/register' || Request::path()=='auth/forget')
        <link href="/css/baseold.css" rel="stylesheet">
    @else
        <link href="/css/base.css" rel="stylesheet">
    @endif
    <link href="/css/header.css" rel="stylesheet">
    <link href="/css/home.css" rel="stylesheet">
    <link href="/css/product.css" rel="stylesheet">
    <link href="/css/product_new.css" rel="stylesheet">
    <link href="/unit/css/beAlert.css" rel="stylesheet">	
    @if (Request::path()=='auth/login' || Request::path()=='auth/register')
    @elseif (Request::path()=='education/info')
        <link href="/css/kxlpop.css" rel="stylesheet">
    @endif
    <link href="/css/serviceInfo.css" rel="stylesheet">
    <link href="/css/buy.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/js/base.js"></script>
    <script type="text/javascript"  src="/js/jquery.validate.js"></script>
    <script type="text/javascript" src="/js/global.js"></script>
    <script type="text/javascript" src="/js/kxlnav.js"></script>
    <script type="text/javascript" src="/js/index.js"></script>
    <script type="text/javascript" src="/js/unit/beAlert.js"></script>
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
<!-- 欢迎来到快学历 -->
<div class="main greyf5f5f5 headerOverflowInherit">
    <div class="minwidth welcomekxl headerOverflowInherit" id='loginbar'>
        <ul class="overflowhidden floatLeft headerOverflowInherit" >
            <li class="navLocation">
                <a href="javascript:;" class="locatinIcon">
                    <i class="mykxl floatRight"></i>北京市</a>
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
                <li><a class="noBorder">欢迎{{ app('request')->user()->realname }}</a></li>
                <li><a class="noBorder" href="{{ url('/auth/logout') }}">退出</a></li>
            @endif
            <li><a href="{{ url('order/list') }}">我的订单</a></li>
            <li><a class="welcome1" href="javascript:{void(0);}"><i class="gw floatLeft"></i>购物车<em>0</em>件</a></li>
            <li><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=99618132&site=qq&menu=yes">院校入驻</a></li>
            <li><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=99618132&site=qq&menu=yes">助学机构入驻</a></li>
            <li class="asj app">
                <a href="/mobile/index.html">
                    <i class="sj floatLeft"></i><i class="jt floatRight"></i>移动应用
                </a>
                <div class="welcomekxlShow aimationkxl">
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
                    $(".locatinIcon").html($(e.target).html()+"市<i class='mykxl floatRight'></i>");
                })
            })
        </script>
    </div>
</div>
<!-- 欢迎来到快学历 -->

<!-- 导航 -->
<div class="main bgWhite border000 headerOverflowInherit" style="z-index: 10;">
    <!-- LOGO -->
    @if (Request::path()=='order/pay')
    <div class="minwidth kxlLogo">
        <a class="floatLeft" href="/">
            <img id="logoImg" src="/Image/LOGO.png" alt="快学历">
        </a>
        <div class="logo-sub">订单</div>
        <div class="stepflex">
            <dl class="first done">
                <dt class="s-num">1</dt>
                <dd class="s-text">1.确认服务信息<s></s><b></b></dd>
            </dl>
            <dl class="normal done">
                <dt class="s-num">2</dt>
                <dd class="s-text">2.我的购物车<s></s><b></b></dd>
            </dl>
            <dl class="normal doing">
                <dt class="s-num">3</dt>
                <dd class="s-text">3.提交订单并支付<s></s><b></b></dd>
            </dl>
        </div>
    </div>
    @else
    <div class="minwidth kxlLogo">
        <a class="floatLeft" href="/">
            <img src="/Image/LOGO.png" alt="快学历">
        </a>
        <ul class="floatRight overflowhidden marT30">
            <li><i class="zzrz"></i>资质认证</li>
            <li><i class="zfaq"></i>支付安全</li>
            <li><i class="zrfw"></i>保险赔付</li>
            <li><i class="shwy"></i>售后无忧</li>
        </ul>
         	@if (Request::path()=='auth/login')
        	<div class="logo-sub">
            	登录
        	</div>
            @elseif(Request::path()=='auth/register')
            <div class="logo-sub">
                欢迎注册
            </div>
            @elseif(Request::path()=='order/list')
            <div class="logo-sub">
                我的订单
            </div>
            @elseif(Request::path()=='order/pay')
            <div class="logo-sub">
                支付订单
            </div>
            @elseif(Request::path()=='help/about')
            <div class="logo-sub">
                关于我们
            </div>
            @elseif(Request::path()=='/')
            <div class="logo-sub">
                首页
            </div>
            @elseif(Request::path()=='auth/forget')
            <div class="logo-sub">
                忘记密码
            </div>
            @else

            @endif
    </div>
    @endif
    <!-- LOGO -->

</div>
<!-- 导航 -->


<!-- 侧边栏 -->
<ul class="overflowhidden navSidebars headerOverflowInherit">
    <li style="border-top: 1px solid #dddddd;" class="li-1">
        <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=99618132&site=qq&menu=yes" class="mx-a-1">
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



<!-- 关于快学历平台 -->
<!-- 友情链接 -->
<link rel="stylesheet" href="/css/home_index.css">
<!-- 友情链接 -->
<div class="main bgWhite">
    <div class="main greyf5f5f5">
        <a href="javascript:void(0);" target="_blank">
            <ul class="index-fo-info overflowhidden">
                <li class="firstLi">
                    <i class="zz"></i>
                    <div class="textImgSeparation">
                        <h3>资质认证</h3>
                        <span>院校信息100%实名审核认证</span>
                    </div>
                </li>
                <li>
                    <i class="zf"></i>
                    <div class="textImgSeparation">
                        <h3>支付安全</h3>
                        <span>明码标价支付及信息安全</span>
                    </div>
                </li>
                <li>
                    <i class="fw"></i>
                    <div class="textImgSeparation">
                        <h3>保险赔付</h3>
                        <span>太平洋保险提供担保赔付</span>
                    </div>
                </li>
                <li>
                    <i class="sh"></i>
                    <div class="textImgSeparation">
                        <h3>售后无忧</h3>
                        <span>进修过程全程跟进</span>
                    </div>
                </li>
            </ul>
        </a>
    </div>
    <div class="minwidth">
        <div class="minwidthauto">
            <ul class="overflowhidden index-fo-link">
                <li class="margin0">
                    <h5>关于快学历</h5>
                    <a target="_blank" href="{{ url('help/about') }}" rel="nofollow">了解我们</a>
                    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=99618132&site=qq&menu=yes" rel="nofollow">品牌故事</a>
                    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=99618132&site=qq&menu=yes" rel="nofollow">加入我们</a>
                    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=99618132&site=qq&menu=yes" rel="nofollow">联系我们</a>
                </li>
                <li>
                    <h5>常见问题</h5>
                    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=99618132&site=qq&menu=yes" rel="nofollow">新手帮助</a>
                    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=99618132&site=qq&menu=yes" rel="nofollow">支付帮助</a>
                    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=99618132&site=qq&menu=yes" rel="nofollow">开具发票</a>
                    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=99618132&site=qq&menu=yes">知识库</a>
                </li>
                <li>
                    <h5>商务合作</h5>
                    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=99618132&site=qq&menu=yes" rel="nofollow">商务合作</a>
                    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=99618132&site=qq&menu=yes">友情链接</a>
                </li>
                <li>
                    <h5>服务商入口</h5>
                    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=99618132&site=qq&menu=yes" rel="nofollow">院校入驻</a>
                    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=99618132&site=qq&menu=yes" rel="nofollow">助学机构入驻</a>
                </li>
                <li>
                    <div class="floatLeft bd"></div>
                    <div class="floatLeft link-weixi">
                        <i></i>
                        <div class="textImgSeparation">
                            <span>扫描二维码</span>
                            <span>关注快学历微信</span>
                            <span>学历一手掌握</span>
                        </div>
                    </div>
                    <div class="floatLeft link-th">
                        <h5>服务支持</h5>
                        <h3>400-618-1106</h3>
                        <span>周一至周日<em>8:00-21:00</em></span>                						
                        <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=10139868&site=qq&menu=yes" class="mx-a-1">
                        	<div class="mx-button">售后服务</div>
                    	</a>

                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- 关于快学历平台 -->

<!-- footer -->
<div class="footer">
    <div class="footer-inner footer-index minwidth">
        <p>Copyright © 2011-2015 www.xueli985.com All Rights Reserved &nbsp; &nbsp;技术支持：北京维思天下教育科技有限公司&nbsp;京ICP备12002978号</p>
    </div>
</div>
<!-- footer -->

<!-- Scripts -->
<script src="/js/app.js"></script>
<script>
(function ($) {
    $.deserialize = function (str, options) {
        var pairs = str.split(/&amp;|&/i),
                h = {},
                options = options || {};
        for(var i = 0; i < pairs.length; i++) {
            var kv = pairs[i].split('=');
            kv[0] = decodeURIComponent(kv[0]);
            if(!options.except || options.except.indexOf(kv[0]) == -1) {
                if((/^\w+\[\w+\]$/).test(kv[0])) {
                    var matches = kv[0].match(/^(\w+)\[(\w+)\]$/);
                    if(typeof h[matches[1]] === 'undefined') {
                        h[matches[1]] = {};
                    }
                    h[matches[1]][matches[2]] = decodeURIComponent(kv[1]);
                } else {
                    h[kv[0]] = decodeURIComponent(kv[1]);
                }
            }
        }
        return h;
    };

    $.fn.deserialize = function (options) {
        return $.deserialize($(this).serialize(), options);
    };
})(jQuery);
</script>
</body>
</html>