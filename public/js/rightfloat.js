$(function(){
    //点击关闭按钮关闭左侧框
    $("#complainColose").click(function(){
        $("#askComplain").hide();
        $("#askES").children("i").removeClass("current");
        //$("#askES").removeClass("current");
    });

    //判断返回顶部出现的位置
    $(window).scroll(function(){
        if ($(document).scrollTop() >= $(window).height() ) {
            $(".complainLe").css("display","block");
        }else{
            $(".complainLe").css("display","none");
        }
    });
    //点击返回顶部
    $(".complainLe").on("click",function(){
        $('html,body').animate({scrollTop: '0'}, 600);
    });
    $('#complainSubmit').click(function(){
        var note = $('#complainNote').val();
        var mobile = $('#complainMobile').val();
        if (!mobile)
        {
            $("#complainMobile").focus();
            $('#complainMobile').addClass('complainError');
            return false;
        }
        else
        {
            if(11 != mobile.length && !(/^(1[3-9]\d{9})$/.test(mobile)))
            {
                $("#complainMobile").focus();
                $('#complainMobile').addClass('complainError');
                return false;
            }
        }
        $("#complainMobile").removeClass("complainError");
        $.post("/index/complainSubmit", $("#fromComplain").serialize(), function(result){
            if (result.ret)
            {
                $('#askComplain').addClass('ask-show');
                $('#fromComplain').hide();
                $('#complainDiv').show(0,function(){
                    setTimeout(function(){
                        $('#complainDiv').parent().fadeOut(500,function(){
                            $('#complainNote').val('');
                            $('#complainMobile').val('');
                            $("#complainDiv").hide();
                            $('#askComplain').removeClass('ask-show');
                            $("#fromComplain").show();
                        });
                        $("#askES").removeClass("current");
                    },800);
                });
            }
            else
            {
                $("#complainMobile").focus();
                $('#complainMobile').addClass('complainError');
                Base.alertTime(result.msg);
            }
        });
    })

    // 电话咨询
    $(".li-2").bind("hover",function(){
        $('.mx-phone').toggleClass('mx-visible');
    });
    $('.mx-phone').hover(function(){
        $(".li-2>a").css({backgroundColor:"#f0f0f0"})
    },function(){
        $(".li-2>a").css({backgroundColor:""})
    })
    $('.li-1').hover(function(){
        $('.mx-kefuclass').toggleClass('mx-visible');
    });
    $(".mx-kefuclass").hover(function(){
        $(".li-1>a").css({backgroundColor:"#f0f0f0"})
    },function(){
        $(".li-1>a").css({backgroundColor:""})
    })

    $('body').on('click', "#zxSendCode", getZXPassword);

//  getAjaxPageData("/index/getcartinfo/d/myCartInfo", 'myCartInfo', false);mx-kefuclass

});

setTimeout(function(){ $("#zxcallmobile").focus(); },50);
var endTime;
function getZxCode(){
    var nowTime = new Date().getTime()/1000;
    var time = Math.floor(endTime - nowTime);
    $(".zxcountdown").text(time.toString()+'秒后重新发送');
    if (time > 0) {
        setTimeout("getZxCode()",1000);
    }else{
        $("#zxSendCode").css("display","block");
        $(".zxcountdown").hide();
    }

}
function getZXPassword(){
    $(".newstip").remove();
    var mobile = $("#zxcallmobile").val();
    if (mobile == "") {
        $("#zixunxinxi").text('手机号不能为空');
        return;
    }
    var reg = /^(13[0-9]|15[0-9]|17[678]|18[0-9]|14[57])[0-9]{8}$/;

    if (!reg.test(mobile)){
        $("#zixunxinxi").text('手机号格式有误，请重新输入');
        return;
    }
    $("#zixunxinxi").text('');
    var sendtype = 'twocallsend';
    var data = {'mobile': mobile, 'type': sendtype};
    $.post("/communal/mobileauth", data, function(result)
    {
        if(result.ret)
        {
            $("#xianshixinxi").css({color:""});
            $("#xianshixinxi").text('验证码已发送，请查收短信');
        }
    }, 'json');

    $("#zxSendCode").hide();
    $(".zxcountdown").css("display","inline-block");

    endTime = new Date().getTime()/1000 + 60;
    getZxCode();
}