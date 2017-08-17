@extends('layouts.app')

@section('content')
<div class="loginLayout">
    <div class="llMain">
        <div class="web-login-pass">
            <a class="passC pass-1">密码重置</a>
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
					<span>
						<input type="password" id="newPassword" name="reg[userpass]"  onkeyup="this.value=this.value.replace(/^ +| +$/g,'')" onFocus="huodewenan(this)" onBlur="shiquwenan(this,'tishi-div-3')" value="">
					</span>
                    <label for="newPassword">设置新密码</label>

                    <div class="register-li-2 tishi-div-2">请设置一个6到18位的密码</div>
                    <div class="register-li-1 tishi-div-3" style="display: none;">请输入新密码</div>
                    <div class="register-li-3"></div>
                </li>
                <li class="register-li">
					<span>
						<input type="password" id="newSPassword" name="regS[userpass]"  onkeyup="this.value=this.value.replace(/^ +| +$/g,'')" onFocus="huodewenan(this)" onBlur="shiquwenan(this,'tishi-div-3')" value="">
					</span>
                    <label for="newSPassword">确认新密码</label>

                    <div class="register-li-2 tishi-div-2">请设置一个6到18位的密码</div>
                    <div class="register-li-1 tishi-div-3" style="display: none;">请确认密码</div>
                    <div class="register-li-3"></div>
                </li>
                <li class="register-li">
                    <a class="form-buttom" href="javascript:;" id="submitbtn1">下一步</a>
                </li>
            </ul>
            <!-- <h1>找回密码成功</h1> -->
            <ul class="inputLayout back-panel form-panel relative" id="succPop" style="display:none;">
                <li class="isOk">
                    <img src="../Image/passOk.png" alt="">
                    <h2>密码已成功修改！</h2>
                    <h4>现在，您可以去登录了！</h4>
                </li>
                <li class="login-li">
                    <a class="" href="{{ url('auth/login') }}">去登录</a>
                </li>
            </ul>
        </div>

    </div>
</div>
<script type="text/javascript">
    //手机号 验证码 通过跳转提示
    $("#submitbtn1").on("click", function(){
    	if($('#newPassword').val() != $('#newSPassword').val()){
    		alert("操作失败！","两次密码输入不一致",function(){},{type: 'info', confirmButtonText: '我知道了'});
    		return false;
    	}
        var submitObj = $("#submitbtn1");
        if($('#forgetmobile').val().length!=11 || $('#validate_code').val().length!=6) {
            return;
        }
        $.ajax({
            method: 'get',
            url: '/auth/resetpass',
            data: {
                _token: '{{ csrf_token() }}',
                mobile: $('#forgetmobile').val(),
                verifycode: $('#validate_code').val(),
                password: $('#newPassword').val(),
            },
            dataType:'json',
            success: function (data) {
                if(data.code != 0) {
                    alert("操作失败!", data.msg, function () {}, {type: 'error', confirmButtonText: '确定'});
                } else {
                   alert("操作成功!", "短信验证成功", function () {
                   		$(submitObj).closest("ul").hide();
	                    $(submitObj).closest(".loginLayout").find("a.pass-1").removeClass("passC");
	                    $(submitObj).closest(".loginLayout").find("a.pass-2").addClass("passC");
	                    $(submitObj).closest(".loginLayout").find("#succPop").show();
                   }, {type: 'success', confirmButtonText: '确定'});   
                }
            }
        });
    });
    function huodejiaodian(obj){
        var aa = $(obj).parent().parent();
        aa.children("div.register-li-1").hide();
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
            if($(obj).attr("id")=="forgetmobile"){
                var length = $(obj).val().length;
                if (11 == length && /^(1[3|4|5|7|8]\d{9})$/.test($(obj).val())){
                }else{
                    console.log("1");
                    $("." + obj1).html("手机号格式不正确!");
                    $("." + obj1).show();
                }
            }
        }
    }

    $(function(){
        $("#validate_code").val('');
        $("#validate_mobile").val('');
		$("#newPassword").val('');
		$("#newSPassword").val('');
        //$("#forgetmobile").focus();
        $(".register-li input").focus(function(){
            $(this).parents('.register-li').find('label').addClass("register-li-span")
        });
    });
    $(document).ready(function(){
        $('#user_getcode').click(function(){
            if($('#forgetmobile').val().length!=11) {
                return;
            }
            $.ajax({
                method: 'get',
                url: '/sms/send?type=forget',
                data: {
                    _token: '{{ csrf_token() }}',
                    mobile: $('#forgetmobile').val()
                },
                dataType:'json',
                success: function (data) {
                    if(data.code != 0) {
                    	alert("操作失败!", data.msg, function () {}, {type: 'error', confirmButtonText: '我知道了'});
                    } else {
                    	alert("操作成功!", "验证码已成功发送", function () {}, {type: 'success', confirmButtonText: '我知道了'});
                    }
                }
            });
        });
    });
</script>
@endsection
