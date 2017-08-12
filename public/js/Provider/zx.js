// 正在呼叫的动画效果
function movieCall()
{
	var thisObj = $("#diandian").children('.bigEm').eq(0);
	thisObj.removeClass('bigEm').addClass('smallEm');

	var _index = thisObj.index();
	if (_index == ($("#diandian").children('em').length - 1))
	{
		_index = 0;
	}
	else
	{
		_index ++;
	}
	var _obj = $("#diandian").children('em').eq(_index);
	_obj.removeClass('smallEm').addClass('bigEm');
	return ;
}

/**
 * js检查手机号是否合法
 */
function checkMobile(mobile)
{
	var mobile = $.trim(mobile);
	var reg = /^1[3-9][0-9]\d{8}$/;
	if (reg.test(mobile))
	{
		return true;
	}
	else
	{
		return false;
	}
}

//限制验证码位数
function ismaxlength(obj){
        var mlength=obj.getAttribute ? parseInt(obj.getAttribute("maxlength")) : "";
        if (obj.getAttribute && obj.value.length > mlength){
                obj.value=obj.value.substring(0,mlength)
        }
}


function dial(obj)
{
    var mobile = $("#zxcallmobile").val();
    var calltype = $("#calltype").val();
    var code = $("#zxcode").val();
    if (!mobile)
    {
        $("#zixunxinxi").text('手机号不能为空');
        return false;
    }
    if (!checkMobile(mobile))
    {
        $("#zixunxinxi").text('请输入正确的11位手机号');
        return false;
    }
    isjixuzx = openTwoWayCall(obj, mobile,calltype,code);
    if(!isjixuzx)
    {
        return false;
    }
    $("#callipt").html('您填写的手机号：'+mobile);
    //$("#Z-INVITE").show();
    doCallUser(mobile, '', calltype);
}

function dial400(obj) {
    var mobile = $("#zxcallmobile").val();
    var code = $("#zxcode").val();

    isjixuzx = open400Call(obj, mobile,"",code);
    if(!isjixuzx)
    {
        return false;
    }
}

function open400Call(obj, mobile, calltype,code) {
    //dial400

    mobile = mobile || '';
    calltype = calltype || '';
    code = code || '';
    var tel = $(obj).attr('tel');
    var userid = $(obj).attr('userid');
    var objecttype = $(obj).attr('objecttype');
    objecttype = (undefined == objecttype ? 1 : objecttype);

    $.ajax({
        type: "GET",
        url: "/api/twowaycall/dial400.html",
        async:false,
        data: {objecttype:objecttype, userid:userid, tel:tel, mobile:mobile, calltype:calltype, code:code},
        success: function(result){
            if(result.status){
                Base.alert(result.info, '免费电话咨询', '600', '300');

                if('' == calltype || undefined == calltype)
                    $('#TwoCall').show();
                else
                    $("#Z-INVITE").show();
            }
            else
            {
                if('' == calltype || undefined == calltype)
                {
                    Base.alertTime(result.info);
                }
                else
                {
                    $("#zixunxinxi").text('');
                    $("#xianshixinxi").css({color:"red"});
                    $("#xianshixinxi").text(result.msg);
                    isjixuzx = 1;
                }
            }
        }
    });
}

var _inter = null;
var _timer = null;
$(function(){
	//开始免费咨询  或  
	$("body").on('click', '.t-mfzx', function(){
            openTwoWayCall(this);
	}); 
	
	$("body").on('click', "#beginZixun", function (){
//		if(!checkVertifyCode($("#find_code").val())){
//			Base.alertTime('请输入正确的验证码');
//			return false;
//		}
                var callmobile = $("#callmobile").val();
                var code = $("#code").val();
			//	var calltype = 0;
			//	if ($("#calltype"))
			//	{
			//		calltype = $("#calltype").val();
			//	}
		//doCallUser(callmobile,code,calltype);
        
        var callType = $("#callCommon").val();
        callType = callType || '';
		doCallUser(callmobile,code,callType);
		return false;
	});

	
});

function openTwoWayCall(obj, mobile, calltype,code)
{
    mobile = mobile || '';
    calltype = calltype || '';
    code = code || '';
    var providerid = $(obj).attr('providerid');
    var productid = $(obj).attr('productid');
    var objecttype = $(obj).attr('objecttype');
    var userid = $(obj).attr('userid');
    var realname = $(obj).attr('realname');
    var tel = $(obj).attr('tel');
    objecttype = (undefined == objecttype ? 1 : objecttype);
    userid = (undefined == userid ? 0 : userid);
    realname = (undefined == realname ? '' : realname);
    tel = (undefined == tel ? '' : tel);
    var isjixuzx = 0;
    $.ajax({
            type: "GET",
            //url: "/api/twowaycall/linebusy.html",
            url: "/api/twowaycall/trtwowaycall.html",
            async:false,
            data: {id:providerid, productid:productid, objecttype:objecttype, userid:userid, realname:realname, tel:tel, mobile:mobile, calltype:calltype, code:code},
            success: function(result){
                    if(result.status){
                        Base.alert(result.info, '免费电话咨询', '600', '300');

                        if('' == calltype || undefined == calltype)
                        {
                            $('#TwoCall').show();
                        }
                        else
                        {
                            $("#Z-INVITE").show();
                        }
                     }
                     else
                     {
                        if('' == calltype || undefined == calltype)
                        {
                            Base.alertTime(result.info);
                        }
                        else
                        {
                            $("#zixunxinxi").text('');
                            $("#xianshixinxi").css({color:"red"});
                            $("#xianshixinxi").text(result.msg);
                            isjixuzx = 1;
                        }
                     }
       }
    });
    if(1 == isjixuzx)
    {
        return false;
    }
    return true;
}

function doCallUser(callmobile,code,calltype)
{
    code = code || '';
    calltype = calltype || '';
    var providerid = $("#zixun_providerid").val();
    var productid = $("#zixun_productid").val();
    var objecttype = $("#zixun_objecttype").val() ? $("#zixun_objecttype").val() : 1;
    var displaynbr = $("#zixun_displaynbr").val();
    $(".newstip").remove();

    if ('' == callmobile)
    {
            $("#callmobile").focus();
            $("#callmobile").parent().after('<p class="newstip errortip w240">手机号不能为空</p>');
            return false;
    }
    if (!checkMobile(callmobile))
    {
            $("#callmobile").focus();
            $("#callmobile").parent().after('<p class="newstip errortip w240">请输入正确的11位手机号</p>');
            return false;
    }
    
    //$.getJSON("/api/twowaycall/dial?mobile=" + callmobile + "&code=" + code + "&providerid=" + providerid + "&productid=" + productid + '&objecttype=' + objecttype + '&displaynbr=' + displaynbr + '&calltype=' + calltype,
    $.getJSON("/api/twowaycall/startcall?mobile=" + callmobile + "&code=" + code + "&providerid=" + providerid + "&productid=" + productid,
            function(result){
                    if (result.ret)
                    {
                            //alert(result.data);
                            $(".phone-counseling").hide();
                            $("#callipt").html('您填写的手机号：'+callmobile);
                            $("#Z-INVITE").show();
                            //html = $('.th-consulting1').val();
                            //Base.dialog.close().remove();
                            //Base.alert(html, '呼叫中...',605,280);
                            //clearInterval(_inter);
                            //_inter = setInterval(movieCall, 500);
                            if (result.data) {
                                    //getNewStart();
                                    //_getNew = setInterval("getNewStart()",1500);
                                    _intval = setInterval('getCallStatus()', 1000);
                            };
                    }
                    else
                    {
                        if(10001 == result.code)
                        {
                            var avatar1 = '<?php echo $providerInfo["avatar"]; ?>';
                            var realname1 = '<?php echo $providerInfo["realname"]; ?>';
                            var mobilemy = $("#zixun_mobile").val();
                            var str = '<div class="consultName" id="providerImg" style="display:none"><img class="imgW80 percentRadius50 marL20" src="'+avatar1+'" alt=""><span>'+realname1+'</span></div><div class="consultBtn margtop53"><p><input type="text" id="callmobile" class="callipt w240" value="'+mobilemy+'" placeholder="请您输入手机号"/></p><p><input type="text" maxlength="6" onkeyup="return ismaxlength(this)" id="code" class="callipt w110" placeholder="请输入验证码"/><a href="javascript:;" class="inlineBlock butpadding40 getCode" id="sendCode" onclick="getPassword()">获取验证码</a><span class="countdown inlineBlock butpadding40 getCode"></span></p><p><a href="javascript:;" id="beginZixun" class="inlineBlock w240 butpadding40 butgreen">开始免费咨询</a></p></div><p class="consultTips colorb0b padtop10">本次电话咨询完全免费，我们将对您的号码严格保密，请放心使用</p>';
                            $(".checkcode").html(str);
                            //getPassword();
                        }
                        else
                        {
                            $("#sendCode").parent().parent().find('.errortip').remove();
                            $("#sendCode").parent().after('<p class="newstip errortip w240">'+result.msg+'</p>');
                            //Base.alertTime(result.msg);
                        }

                    }
            }
    );
}

function getCallStatus()
{
    $.ajax({
        type: "GET",
        url: "/api/twowaycall/getCallNewStatus",
        success: function(result){
            console.log(result);
            if (21 == result)
            {
                // 主叫未接听
                showTwoCallWindcow("Z-FAILED");
                clearInterval(_intval);
            }
            else if (3 == result)
            {
                // 主叫接听 呼叫服务商
                showTwoCallWindcow("B-INVITE");
            }
            else if (22 == result)
            {
                // 主叫接听未转接
                showTwoCallWindcow("B-FAILED");
                clearInterval(_intval);
            }
            else if (23 == result)
            {
                // 服务商未接听
                showTwoCallWindcow("B-FAILED");
                clearInterval(_intval);
            }
            else if (4 == result)
            {
                // 开始通话
                showTwoCallWindcow("B-ANSWERED");
            }
            else if (24 == result)
            {
                // 通话结束
                showTwoCallWindcow("Evaluate-END");
                clearInterval(_intval);
            }
        }
    });
}

function getNewStart(){
	$.getJSON("/api/twowaycall/getnewstatus" , 
		function(result){
			if (result.ret)
			{	
				var settime = 0;
				var cell = new Array; 
				cell[0] = 'Z';
				cell[1] = 'B';
				if (result.data.callstate==1){ //发起呼叫
					showTwoCallWindcow(cell[result.data.istransfer]+"-INVITE");
					settime = 1000;
				}else if (result.data.callstate==2){ //振铃
					showTwoCallWindcow(cell[result.data.istransfer]+"-INVITE");
					settime = 1000;				
				}else if (result.data.callstate==3){ //用户摘机接听
					showTwoCallWindcow(cell[result.data.istransfer]+"-ANSWERED");
					if (result.data.istransfer && _timer == null) {
						holdingTime();
					};
					settime = 2000;				
				}else if (result.data.callstate==4){ //结束呼叫
					showTwoCallWindcow("Evaluate-END");
					endTwoCall();		
				}else if (result.data.callstate==5){ //呼叫失败
					showTwoCallWindcow(cell[result.data.istransfer]+"-FAILED");
					_timer = null;
					clearInterval(_timer);
				}else if (result.data.callstate==6){ //主叫侧取消呼叫
					showTwoCallWindcow("END");
					endTwoCall();				
				}else if (result.data.callstate==7){ //呼叫转移中
					settime = 1500;				
				}
				if (settime) {
					setTimeout('getNewStart()',settime);
				};
			}else{
				setTimeout('getNewStart()',1000);
			}
			
		}
	);
	return false;
}
function showTwoCallWindcow(wid){
	if (!$("#"+wid).is(":visible")){
		$(".phone-counseling").hide();
		$("#"+wid).show();
	}
}
function endTwoCall(){
	clearInterval(_timer);
	_timer = null;
	$("#allholdingTime").html($("#holdingTime").html());
	$("#holdingTime").html(" ");
}
function holdingTime(){
	var mm = 0;
	var ss = 0;
	var str = '';
	clearInterval(_timer);
	_timer = null;
	_timer = setInterval(function(){
		str = "";

		if(++ss==60){
			if(++mm==60){
				mm=0;
			}
			ss=0;
		}

		str+=mm<10?"0"+mm:mm;
		str+=":";
		str+=ss<10?"0"+ss:ss;
		$('#holdingTime').html("通话时长 "+ str);
	},1000);
}

function checkVertifyCode(code){
	if(!code){
		return false;
	}
	var ret = true;
	$.ajax({
		url:"/communal/checkverifycode?timeout=1&code=" + code,
		async:false,
		success:function(result){
			if (!result.ret){
				ret = false;
				$("#verifyCode").click();
			}
		}
	});
	return ret;
}

function getCodeTest(){
	var mobile = $("#callmobile").val();
	if (!checkMobile(mobile))
	{
		$("#callmobile").focus();
		Base.alertTime('请输入正确的11位手机号');
		return false;
	}
}