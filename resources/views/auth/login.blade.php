@extends('layouts.app')

@section('content')
    <div class="loginLayout">
        <div class="llMain">
            <div class="group mlLe paddingTop">
                <form id="regForm" autocomplete="off" class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
                        {{ csrf_field() }}
                    <ul class="relative" style="left:100px;">
                        <!--<li class="register-li-radio">-->
                        <!--<input type="radio" checked="checked" class="rlr-input" name="login">账号登录-->
                        <!--<input type="radio" class="rlr-input" name="login">短信快捷登录-->
                        <!--</li>-->
                        <li class="register-li">
						<span>
                                                            <input type="text" id="usermobile"  maxlength='11' name="user[mobile]" autocomplete="off" onfocus="huodejiaodian(this)" onBlur="shiqujiaodian(this,'tishi-1')">
                                                    </span>
                            <label class="register-li-span" for="usermobile">手机号码</label>
                            <!-- <div class="register-li-1">请输入11位手机号码</div> -->
                            <div class="register-li-1 tishi-1" style="display: none">请输入手机号</div>
                            <div class="register-li-3 chenggong-1"></div>
                        </li>
                        <li class="register-li">
                            <span><input type="password" id="password" name="user[userpass]" onfocus="huodejiaodian(this)" onBlur="shiqujiaodian(this,'tishi-2')"></span>
                            <label class="register-li-span" for="password">密码</label>
                            <!-- <div class="register-li-1">密码为6~8位的数字或字符</div> -->
                            <div class="register-li-1 tishi-2" style="display: none">请输入密码</div>
                            <div class="register-li-3 chenggong-2"></div>
                        </li>
                        <input type="hidden" name="returnUrl" value="/" />
                        <li class="register-li-checkbox">
                            <label for="">
                                <input type="checkbox" name="user[autologin]" value="1">一周内自动登录
                            </label>
                            <a href="/communal/forgetpass.html" class="forget-pass" target="_blank">忘记密码？</a>
                        </li>
                        <li class="register-li">
                            {{--<a href="javascript:;" class="reg-now" id="regButton">登录</a>--}}
                            <a class="reg-now" id="regButton">
                                登录
                            </a>
                        </li>
                    </ul>
                    <input type="hidden" name="__hash__" value="47383934bdf1df76bab4493c66d043a1_a5bcaf5d66c6e521ee42649325378572" /></form>
            </div>
            <div class="group mlRi mlRibg2">
                <p>还没有快学历账号？</p>
                <p class="reg-login-p"><a href="{{ url('/auth/register') }}" class="reg-login">立即注册 >></a></p>
            </div>
        </div>
    </div>
    {{--
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">login</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">email</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember"> Remember me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        login
                                    </button>

                                    <a class="btn btn-link" href="{{ url('/password/email') }}">
                                        forgot password?
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
     --}}

    <script>
        function huodejiaodian(obj)
        {
            var aa = $(obj).parent().parent();
            aa.children("div.register-li-3").hide();
        }

        function shiqujiaodian(obj,obj1)
        {
            var aa = $(obj).parent().parent();
            if('' == $(obj).val())
            {
                aa.children("div.register-li-1").hide();
                $("." + obj1).show();
            }
            else
            {
                //aa.children("div.register-li-1").html("");
                $("." + obj1).hide();
            }
        }

        $(function($){

//	$(".register-li input").val(''); //用autocomplete="off"代替

            $(".register-li input").focus(function(){
                $(this).parents('.register-li').find('label').addClass("register-li-span")
            })
            //$("#usermobile").focus();

            $("#regButton").click(function (){
                $(".tishi-1").hide();
                $(".tishi-2").hide();
                $("#regForm").submit();
            });
            $("#regForm").validate({
                onkeyup: false,
                ignore: "",
                errorElement: "div",
                errorClass: "register-li-1",
                rules: {
                    'user[mobile]':{
                        required: true,
                        mobile: true
                        /*
                        remote: {
                            url :"/communal/checkmobile.html",
                            type:'post',
                            dataType: "json",
                            data: {
                                mobile: function() {
                                    return $("#usermobile").val();
                                },
                                reverse: 1
                            }
                        }
                        */
                    },
                    'user[userpass]':{
                        required: true,
                        userpass: true
                    }
                },
                messages: {
                    'user[mobile]':{
                        required: "请输入手机号",
                        mobile: "手机号格式有误，请重新输入",
                        remote:function() {
                            return "此号尚未注册，请<a href='/signup/register?mobile="+$('#usermobile').val()+"' style='color: #00c9ff'> 注册 </a>后再登录";
                        }

                    },
                    'user[userpass]':{
                        required: "请输入密码",
                        userpass: "密码为6-18位的数字或字符"
                    }
                },
                errorPlacement: function(error, label) {
                    label.parent().after(error);
                },
                submitHandler:function(form){
                    submitForm("/signup/login.html", 'regForm', function(result){
                        if (result.ret)
                        {
                            $(".chenggong-2").show();

                            window.location.href = result.url;
                            return;
                        }
                        else
                        {
                            $(".tishi-1").hide();
                            $(".tishi-2").hide();
                            if(100 == result.code)
                            {
                                $(".chenggong-1").hide();
                                var usermobile = $("#usermobile").val();
                                $(".tishi-1").html("此号尚未注册，请<a href='/signup/register?mobile="+usermobile+"' style='color: #00c9ff'> 注册 </a>后再登录");
                                $(".tishi-1").show();
                            }
                            else if(7 == result.code || 101 == result.code)
                            {
                                $(".chenggong-1").hide();
                                $(".tishi-1").html(result.msg);
                                $(".tishi-1").show();
                            }
                            else
                            {
                                $(".chenggong-2").hide();
                                $(".tishi-2").html(result.msg);
                                $(".tishi-2").show();
                            }
                        }
                        // 加载错误提示
                        /*var errObj = $("div[for='user[userpass]']");
                         if (errObj.length > 0)
                         {
                         errObj.html(result.msg).show();
                         }
                         else
                         {
                         $("input[name='user[userpass]']").eq(0).parent().after('<div for="user[userpass]" generated="true" style="display:block" class="register-li-1">'+result.msg+'</div>');
                         }*/
                    });
                    return false;
                },

                success:function(error, label){
                    //console.log(error);
                    if($(label).attr('id') != 'password'){
                        $(error).parent().find('.register-li-3').show();
                    }
                    $(error).remove();
                }
            });

        }(jQuery));
    </script>
@endsection
