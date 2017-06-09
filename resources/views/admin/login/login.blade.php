<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>{{config('web.web_title')}} - {{config('web.seo_title')}} - 登录</title>
<link href="{{asset('style/admin/css/framework.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('style/admin/css/style.css')}}" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="biz_login">
  <h1>管理登录</h1>
  @if(session('msg'))
    <p style="text-align: center;color: red;">{{session('msg')}}</p>
  @endif

  <form action="" method="post">
    {{csrf_field()}}
    <div class="form_row mt30"><em class="log_tel"></em>
      <input type="text" class="log_input" name="username" />
    </div>
    <div class="form_row mt15"><em class="log_pwd"></em>
      <input type="password" class="log_input" name="password" />
    </div>
    <div class="form_row">
      <button class="log_submit mt15 ml30">登录</button>
    </div>
  </form>
</div>
<div class="copy">{{config('web.copyright')}}</div>
</body>
</html>
