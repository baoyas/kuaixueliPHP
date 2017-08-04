@extends('layouts.app')

@section('content')
    <div class="loginLayout">
        <div class="llMain">
            <div class="group mlLe">
                <form action="" id="regForm">
                    {{ csrf_field() }}
                    <ul class="relative" style="left:100px;">
                        <li class="register-li">
                            <p><input type="text" value="" id="reg_mobile" name="reg[mobile]" maxlength='11' onkeyup="this.value=this.value.replace(/^ +| +$/g,'')" onFocus="huodewenan(this,'tishi-div-1','tishi-div-2','tishi-div-3')" onBlur="shiquwenan(this,'tishi-div-1','tishi-div-2','tishi-div-3')"></p>
                            <label for="reg_mobile">手机号码</label>

                            <div class="register-li-2 tishi-div-2">重要，服务专员通过此号联系您</div>
                            <div class="register-li-1 tishi-div-3" style="display: none;">请输入手机号</div>
                            <div class="register-li-3 mobiletishi"></div>
                        </li>
                        <!--<li class="register-li tupianyzm">
                            <span><input type="text" class="reg-yzm" id="yzm" name="forget[code]" onkeyup="this.value=this.value.replace(/^ +| +$/g,'')" onFocus="huodewenan(this,'tishi-div-1','tishi-div-2','tishi-div-3')" onBlur="shiquwenan(this,'tishi-div-1','tishi-div-2','tishi-div-3')"></span>
                            <label for="yzm">验证码</label>
                            <img class="yzimg" src="/communal/verifycode.html" alt="" id="verifyCode" onclick="$('#verifyCode').attr('src','/communal/verifyCode/'+Math.random());" />
                            <img src="/Public/Image/Login/refresh.png" alt="" class="reimg" onclick="$('#verifyCode').attr('src','/communal/verifycode/'+Math.random());">

                            <div class="register-li-2 tishi-div-2">请输入图片上的验证码</div>
                            <div class="register-li-1 tishi-div-3" style="display: none;">请输入验证码</div>
                            <div class="register-li-3 codetishi"></div>
                        </li>-->
                        <li class="register-li">
                            <span><input type="text" class="reg-dxyzm" id="dxyzm" name="reg[verifycode]" onkeyup="this.value=this.value.replace(/^ +| +$/g,'')" onFocus="huodewenan(this,'tishi-div-1','tishi-div-2','tishi-div-3')" onBlur="shiquwenan(this,'tishi-div-1','tishi-div-2','tishi-div-3')"></span>
                            <label for="dxyzm">短信验证码</label>
                            <a href="javascript:;" class="reg-click"  id="user_getcode" style="width:86px;">点击获取</a>

                            <div class="register-li-2 tishi-div-2">请输入手机收到的短信验证码</div>
                            <div class="register-li-1 tishi-div-3" style="display: none;">请输入短信验证码</div>
                            <div class="register-li-3 verifycodetishi"></div>
                        </li>
                        <!--
                        <li class="register-li-p">
                            <p>没有收到短信？试试语音验证码。<a href="javascript:;" class="reg-start">开始呼叫</a></p>
                        </li>
                         -->
                        <li class="register-li">
                            <span><input type="text" id="mail" name="reg[email]" onkeyup="this.value=this.value.replace(/^ +| +$/g,'')" onFocus="huodewenan(this,'tishi-div-1','tishi-div-2','tishi-div-3')" onBlur="shiquwenan(this,'tishi-div-1','tishi-div-2','tishi-div-3')"></span>
                            <label for="mail">常用邮箱</label>

                            <div class="register-li-2 tishi-div-2">重要，服务中的文件将发送到此邮箱</div>
                            <div class="register-li-1 tishi-div-3" style="display: none;">请输入常用邮箱</div>
                            <div class="register-li-3"></div>
                        </li>
                        <li class="register-li">
                            <span><input type="text" name="reg[realname]" id="name" onkeyup="this.value=this.value.replace(/^ +| +$/g,'')" onFocus="huodewenan(this,'tishi-div-1','tishi-div-2','tishi-div-3')" onBlur="shiquwenan(this,'tishi-div-1','tishi-div-2','tishi-div-3')"></span>
                            <label for="name">姓名/称呼</label>

                            <div class="register-li-2 tishi-div-2">如何称呼您？</div>
                            <div class="register-li-1 tishi-div-3" style="display: none;">请填写姓名或称呼</div>
                            <div class="register-li-3"></div>
                        </li>
                        <li class="register-li">
						<span>
														<input type="text" id="password" name="reg[userpass]" autocomplete="off" onkeyup="this.value=this.value.replace(/^ +| +$/g,'')" onFocus="huodewenan(this,'tishi-div-1','tishi-div-2','tishi-div-3')" onBlur="shiquwenan(this,'tishi-div-1','tishi-div-2','tishi-div-3')" value="">
													</span>
                            <label for="password">设置密码</label>

                            <div class="register-li-2 tishi-div-2">请设置一个6到18位的密码</div>
                            <div class="register-li-1 tishi-div-3" style="display: none;">请设置密码</div>
                            <div class="register-li-3"></div>
                        </li>
                        <li class="register-li">
                            <a href="javascript:;" class="reg-now" id="regButton">立即注册</a>
                        </li>
                    </ul>
                    <input type="hidden" name="__hash__" value="a96d9b2d1554323c7056511d2580f1f8_f6bd61b1acc8d31f743836cfc2c8740e" /></form>
            </div>
            <div class="group mlRi mlRibg1">
                <p>已有快学历账号？</p>
                <p class="reg-login-p"><a href="{{ url('/auth/login') }}" class="reg-login">直接登录 >></a></p>
            </div>
        </div>
    </div>

    {{--
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Sign Up</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Nickname</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">Email</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="Password" class="col-md-4 control-label">password</label>

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
                                <label for="password-confirm" class="col-md-4 control-label">Confirm password</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Sign Up
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    --}}


    <script type="text/javascript">
        $(function($){
            //$("#reg_mobile").focus();

            $(".register-li input").val('');

            var mobile = '';
            if('' != mobile)
            {
                $("#reg_mobile").parents('.register-li').find('label').animate({left:'-90px'},200);
                $("#reg_mobile").val(mobile);
            }

            $("#regButton").click(function (){
                $(".tishi-div-2").hide();
                $(".tishi-div-3").hide();
//		$("#regForm").submit();
                $.post('/auth/register', $("#regForm").serialize(), function (result) {
                    if( result.ret ){
                        window.location.href = result.url;
                    } else {
                        alert(result.msg);
                    }
                });
            });
            $("#regForm").validate({
                onkeyup: false,
                ignore: "",
                errorElement: "div",
                errorClass: "register-li-1 tishi-div-1",
                rules: {
                    'reg[mobile]':{
                        required: true,
                        mobile: true,
                        remote: {
                            url :"/communal/checkmobile.html",
                            type:'post',
                            dataType: "json",
                            data: {
                                mobile: function() {
                                    return $("#reg_mobile").val();
                                }
                            }
                        }
                    },
                    'reg[email]':{
                        required: true,
                        email: true
                    },
                    'reg[verifycode]':{
                        required: true
                    },
                    'reg[realname]':{
                        required: true
                    },
                    'reg[userpass]':{
                        required: true,
                        userpass: true
                    },
                    'forget[code]':{
                        required:true
                    }
                },
                messages: {
                    'reg[mobile]':{
                        required: "请输入手机号",
                        mobile: "手机号格式不正确",
                        remote: "此号已注册，请<a href='/signup/login.html' style='color: #00c9ff'> 登录 </a>或<a href='/communal/forgetpass.html' style='color: #00c9ff'> 找回密码 </a>"
                    },
                    'reg[verifycode]':{
                        required: "请输入短信验证码"
                    },
                    'reg[email]':{
                        required: "请输入常用邮箱",
                        email: "请输入正确的邮箱号"
                    },
                    'reg[realname]':{
                        required: "请填写姓名或称呼"
                    },
                    'reg[userpass]':{
                        required: "请设置密码",
                        userpass: "密码为6-18位的数字或字符"
                    },
                    'forget[code]':{
                        required:"请输入验证码"
                    }
                },
                errorPlacement: function(error, label) {
                    $(".tishi-div-2").hide();
                    label.parent().after(error);
                    //label.parent().find('.register-li-3').hide();
                },
                submitHandler:function(form) {
                    submitForm("auth/register", 'regForm', function (result) {
                        if (true == result.ret) {
                            $(".verifycodetishi").show();

                            // 聚效统计代码【注册回传代码】
                            if (result.data.userid) {
                                var _mvq = window._mvq || [];
                                window._mvq = _mvq;
                                _mvq.push(['$setAccount', 'm-108577-0']);
                                _mvq.push(['$setGeneral', 'registered', '', /*用户名*/ result.data.mobile, /*用户id*/ result.data.userid]);
                                _mvq.push(['$logConversion']);
                            }

                            ga('send', 'event', 'userregister', 'click', 'userregister', 1);
                            if ('undefined' != typeof(result.url) && '' != result.url) {
                                //window.location.href = '/signup/registersuccess?returnUrl=' + result.url;
                                window.location.href = '/signup/registersuccess';
                            }
                        }
                        else {
                            //Base.alertTime(result.msg);
                            if($("#dxyzm").parent().next('div').html())
                            {
                                $("#dxyzm").parent().next('div').html('短信验证码不正确');
                            }
                            else
                            {
                                $("#dxyzm").parent().after('<div class="register-li-1 tishi-div-1" for="dxyzm" generated="true" style="display:block">短信验证码不正确</div>');
                            }
                        }
                    });
                    return false;
                },
                success:function(error, label){
                    //console.log(error);
                    if($(label).attr('id') != 'yzm' && $(label).attr('id') != 'dxyzm'){
                        $(error).parent().find('.register-li-3').show();
                    }
                    $(error).remove();

                }
            });
        }(jQuery));

        function huodewenan(obj,obj1,obj2,obj3)
        {
            var aa = $(obj).parent().parent();
            //alert(aa.children("div." + obj2).html());
            aa.children("div.tishi-div-1").hide();
            aa.children("div.tishi-div-3").hide();
            aa.children("div.register-li-3").hide();
            aa.children("div.tishi-div-2").show();
            aa.children('label').animate({left:'-90px'},200);
            aa.children(".verCodePrompt").remove();

            if($(obj).attr('id') == 'password'){
                obj.type = 'password'
            }
        }

        function shiquwenan(obj,obj1,obj2,obj3)
        {
            var aa = $(obj).parent().parent();
            aa.children("div.tishi-div-2").hide();
            if('' == $(obj).val())
            {
                aa.children("div.tishi-div-1").hide();
                aa.children("div.tishi-div-3").show();
            }
        }
    </script>

    <!-- 聚效统计代码 -->
    <script type="text/javascript">
        var _mvq = window._mvq || [];window._mvq = _mvq;
        _mvq.push(['$setAccount', 'm-108577-0']);

        _mvq.push(['$setGeneral', 'register', '', /*用户名*/ '', /*用户id*/ '']);
        _mvq.push(['$logConversion']);

        var init = {
            send_type:'registersend',
            mobile_bind:'reg_mobile',
            wrong_mobile:function(){
                $(".mobiletishi").hide();
                if(getCode.mobile.parent().next('div').html())
                {
                    getCode.mobile.parent().next('div').html('请输入手机号码');
                    return false;
                }
                getCode.mobile.parent().after('<div class="register-li-1 tishi-div-1" for="reg_mobile" generated="true" style="display:block">请输入手机号码</div>');
            },
            wrong_mobile_format:function(){
                $(".mobiletishi").hide();
                if(getCode.mobile.parent().next('div').html())
                {
                    getCode.mobile.parent().next('div').html('手机号码格式不正确');
                    return false;
                }
                getCode.mobile.parent().after('<div class="register-li-1 tishi-div-1" for="reg_mobile" generated="true" style="display:block">手机号码格式不正确</div>');
            },
            wrong_mobile_exists:function(){
                $(".mobiletishi").hide();
                if(getCode.mobile.parent().next('div').html())
                {
                    getCode.mobile.parent().next('div').html('此号已注册，请<a href="/signup/login.html" style="color: #00c9ff"> 登录 </a>或<a href="/communal/forgetpass.html" style="color: #00c9ff"> 找回密码 </a>');
                    return false;
                }
                getCode.mobile.parent().after('<div class="register-li-1 tishi-div-1" for="reg_mobile" generated="true" style="display:block">此号已注册，请<a href="/signup/login.html" style="color: #00c9ff"> 登录 </a>或<a href="/communal/forgetpass.html" style="color: #00c9ff"> 找回密码 </a></div>');
            },
            show_button_after:'已发送，1分钟后可重新获取。',
            check_exists:true,
            before_send:function(){
                if(!getCode.canSend){
                    return false;
                }
                var re = false;
                if(!$("#yzm").val())
                {
                    //$(".reimg").click();
                    $(".tupianyzm .tishi-div-3").show();
                    $(".codetishi").hide();
//			if(!$("#yzm").parent().next('div').html())
//			{
//				$("#yzm").parent().after('<div class="register-li-1 tishi-div-1" for="yzm" generated="true" style="display:block">验证码不正确或已过期</div>');
//			}
                    return false;
                }
                $.ajax({
                    url:'/communal/checkverifycode',
                    data:{
                        code:$("#yzm").val()
                    },
                    async:false,
                    success:function(ret){
                        if(false == ret.ret){
                            $(".reimg").click();
                            $('.notSee').click();
                            $(".codetishi").hide();
                            if(!$("#yzm").parent().next('div').html()){
                                $("#yzm").parent().after('<div class="register-li-1 tishi-div-1" for="yzm" generated="true" style="display:block">验证码不正确或已过期</div>');
                            }else{
                                $("#yzm").parent().next('div').show();
                            }

                            re = false;
                        }
                        else{
                            $(".codetishi").show();
                            $("#yzm").parent().next('div').remove();
                            re = true;
                        }
                    }
                });
                return re;
            },
            send_to_msg_code:function (mobile)
            {
                getCode.moveNotice();
                var sendtype = getCode.init.send_type;
                var mobile = mobile || '';
                var data = {'mobile': mobile, 'type': sendtype, 'code' :$("#yzm").val()};
                $.post("/communal/mobileauth", data, function(result)
                {
                    if(result.ret)
                    {
                        getCode.msgSendToTip();
                    }
                    else if(sendtype == 'login'){
                        $('#mobile_not_register').html(result.msg);
                    }
                    else
                    {
                        alert(result.msg);
                    }
                }, 'json');
            }
        };
        $(function(){
            getCode.setinit(init);
        })

    </script>
@endsection
