@extends('layouts.app')

@section('content')
<div class="loginLayout">
    <div class="llMain">
        <div class="web-login-pass">
            <a class="passC pass-1">密码找回</a>
            <a class="pass-2">完成</a>
        </div>
        <div class="back-pass" style="width: 53%;">
            <input type="hidden" name="step" value="1">
            <ul class="relative" style="left:100px;">
                <li class="register-li">
                    <span><input type="text" id="forgetmobile" maxlength="11" name="forget[mobile]" onfocus="huodejiaodian(this)" onblur="shiqujiaodian(this,'tishi-1')"></span>
                    <label for="forgetmobile">手机号码</label>

                    <div class="register-li-1 tishi-1" style="display: none">请输入手机号</div>
                    <div class="register-li-3 chenggong-1"></div>
                </li>
                <li class="register-li">
                    <span><input type="text" class="reg-dxyzm" id="validate_code" name="forget[duanxincode]" onfocus="huodejiaodian(this)" onblur="shiqujiaodian(this,'tishi-3')"></span>
                    <label for="validate_code">短信验证码</label>
                    <a class="reg-click" href="javascript:void(0);" id="user_getcode">点击获取</a>
                    <div class="register-li-1 tishi-3" style="display: none">请输入短信验证码</div>
                    <div class="register-li-3 chenggong-3"></div>

                </li>
                <li class="register-li">
                    <a class="form-buttom" href="javascript:;" id="submitbtn1">下一步</a>
                </li>
            </ul>
            <!-- <h1>找回密码成功</h1> -->
            <ul class="inputLayout back-panel form-panel relative" id="succPop" style="display:none;">
                <li class="isOk">
                    <img src="../Image/passOk.png" alt="">
                    <h2>密码已成功发送到您的手机！</h2>
                    <h4>现在，您可以去登录了！</h4>
                </li>
                <li class="login-li">
                    <a class="" href="../login.html">去登录</a>
                </li>
            </ul>
        </div>

    </div>
</div>
@endsection
