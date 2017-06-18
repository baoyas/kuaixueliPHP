<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{config('web.web_title')}} - {{config('web.seo_title')}}</title>
    <link href="{{asset('style/admin/css/framework.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('style/admin/css/style.css')}}" rel="stylesheet" type="text/css" />
    <script src="{{asset('style/admin/js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{asset('style/admin/layer/layer.js')}}"></script>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
</head>
<body>
<div class="header">
    <div class="width-2 center cf">
        <div class="system">{{config('web.web_title')}}APP管理系统</div>
        <div class="admin"><span>欢迎您,系统管理员</span><a href="{{url('admin/pass')}}">修改密码</a> | <a href="{{url('admin/quit')}}">退出登录</a></div>
        <div class="right top"><a href="{{url('admin/user/create')}}">添加用户</a><a href="{{url('admin/sell/create')}}" class="ml5">发布</a></div>
    </div>
</div>
<!-- -->
<div class="width-2 center mt30 relative">
    <div class="menu">
        <a href="{{url('admin/index')}}">管理首页</a>
        <a href="{{url('admin/config')}}">系统设置</a>
        <a href="{{url('admin/cityList')}}">城市管理</a>
        <a href="{{url('admin/user')}}">用户管理</a>
        <a href="{{url('admin/sell')}}">出售/购买</a>
        <a href="{{url('admin/cate')}}">分类管理</a>
        <a href="{{url('admin/ad')}}">广告管理</a>
        <a href="{{url('admin/push')}}">APP推送</a>
        <a href="{{url('admin/findback')}}">意见反馈</a>
        <a href="{{url('admin/report')}}">投诉管理</a>
        <a href="{{url('admin/version')}}">版本管理</a>
        <a href="https://console.easemob.com/index.html#/login" target="_black">环信统计</a>
        <a href="https://i.umeng.com/?" target="_black">友盟统计</a>
        <a href="{{url('admin/ceditor')}}">内容管理</a>
    </div>
@yield('content')

<div class="width-2 center text-c pt10 footer mt30"> {{config('web.copyright')}} </div>
</body>
</html>
