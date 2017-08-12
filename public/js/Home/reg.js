function getCoupons()
{
	$.ajax({
	   type: "GET",
	   url: "/cart/getcoupons.html",
	   data: "",
	   dataType: "JSON",
	   async: true,
	   success: function(result){
			if ('' != result.htm)
			{
				$("#mycoupons").html(result.htm);
			}
			if ('undefined' != typeof(result.num))
			{
				if (result.num > 0)
				{ 
					$("#couponCanUse").html(result.num).addClass("percentRadius50").show();
					$("#couponCanUse").parent().click();
					$("#couponTitle").click();
				}
				else
				{   
					$("#couponCanUse").hide();
					$("#coupon2").html('<p style="font-size: 12px;color: #e85555;text-align: center;">未找到可使用的代金券</p>');
				}
			}
	   }
	});
}
$(function(){
	var submitTimes = 0;
	var submitTimes2 = 0;
	$("#btn1").click(function (){
		submitTimes = 0;
		submitTimes2 = 0;
		Base.alert($("#quReg1").val(), '登录 ', 825, 365);
    });
	$("#btn").click(function (){
		submitTimes = 0;
		submitTimes2 = 0;
    	Base.alert($("#quReg").val(), '快速注册 ', 825, 480);
    });
	$("body").on('click', "#toLogin", function (){
		submitTimes = 0;
		submitTimes2 = 0;
		Base.dialog.remove();
		Base.alert($("#quReg1").val(), '登录 ', 825, 365);
	});
	$("body").on('click', "#toRegister", function (){
		submitTimes = 0;
		submitTimes2 = 0;
		Base.dialog.remove();
		Base.alert($("#quReg").val(), '快速注册 ', 825, 480);
	});
	$("body").on('click', "#regButton", function (){
		$(".tishi-div-2").hide();
		$(".tishi-div-3").hide();
		if (0 == submitTimes)
		{
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
							//url :"{:U('/communal/checkmobile')}",
							url :'/communal/checkmobile',
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
						mobile: "请输入11位手机号",
						remote: "此号已注册，请<a href='javascript:;' style='color: #00c9ff' id='toLogin'> 登录 </a>或<a href='/communal/forgetpass.html' style='color: #00c9ff'> 找回密码 </a>"
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
					label.parent().after(error);			
				},
				submitHandler:function(form){
				    submitForm("/signup/register", 'regForm', function(result){
				    	if (result.ret)
					    {
							$(".verifycodetishi").show();

				    		// 聚效统计代码【注册回传代码】
			        		if (result.data.userid)
			        		{
			            		var _mvq = window._mvq || [];
			            		window._mvq = _mvq;
			            		_mvq.push(['$setAccount', 'm-108577-0']);
			            		_mvq.push(['$setGeneral', 'registered', '', /*用户名*/ result.data.mobile, /*用户id*/ result.data.userid]);
			            		_mvq.push(['$logConversion']);
			        		}
				    		Base.dialog.close().remove();
							$("#couponDiv").show();
							loadLoginState();
							getCoupons();
							// ga统计注册成功代码
							ga('send', 'event', 'userregister', 'click', 'userregister', 1);
							if (typeof afterAction == 'function')
					    	{
								afterAction();
					    	}
							return;
					    }
				    	// 加载错误提示
						var errObj = $("div[for='reg[verifycode]']");
						if (errObj.length > 0)
						{
							errObj.html(result.msg).show();
						}
						else
						{
							$(".verifycodetishi").hide();
							$("input[name='reg[verifycode]']").eq(0).parent().after('<div for="reg[verifycode]" generated="true" style="display:block" class="register-li-1">'+result.msg+'</div>');
						}
				    });
					return false;
				},
				success:function(error, label){
					//console.log(error);
					if($(label).attr('id') != 'find_code' && $(label).attr('id') != 'rdxyzm'){
						$(error).parent().find('.register-li-3').show();
					}
					$(error).remove();

				}
			});
			submitTimes = 1;
		}
		$("#regForm").submit();
	});
	
	$("body").on('click', "#loginButton", function (){
		if (0 == submitTimes2)
		{
			$("#loginForm").validate({
				onkeyup: false,
				ignore: "",
				errorElement: "div",
				errorClass: "register-li-1",
				rules: {
					'user[mobile]':{
						required: true,
						mobile: true,
						remote: {
							url :'/communal/checkmobile',
							type:'post',
							dataType: "json",
							data: {
								mobile: function() {
									return $("#phone").val();
								},
								reverse: 1
							}
						}
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
						remote: "此号尚未注册，请<a href='javascript:;' style='color: #00c9ff' id='toRegister'> 注册 </a>后再登录"
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
				    submitForm("/signup/login", 'loginForm', function(result){
					    if (result.ret)
					    {
							$(".chenggong-2").show();

					    	if (typeof afterAction == 'function')
					    	{
								afterAction();
								return ;
					    	}
					    	window.location.reload();
					    	/*
					    	Base.dialog.close().remove();
							$("#couponDiv").show();
							loadLoginState();
							getCoupons();
							*/
							return;
					    }
						else
						{
							$(".tishi-1").hide();
							$(".tishi-2").hide();
							if(100 == result.code)
							{
								$(".chenggong-1").hide();
								$(".tishi-1").html("此号尚未注册，请<a href='javascript:;' style='color: #00c9ff' id='toRegister'> 注册 </a>后再登录");
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

						/*// 加载错误提示
						var errObj = $("div[for='user[verifycode]']");
						if (errObj.length > 0)
						{
							errObj.html(result.msg).show();
						}
						else
						{
							$("input[name='user[verifycode]']").eq(0).parent().after('<div for="user[verifycode]" generated="true" style="display:block" class="register-li-1">'+result.msg+'</div>');
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
			submitTimes2 = 1;
		}
		$("#loginForm").submit();
	});
	
});