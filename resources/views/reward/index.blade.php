<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">
    <title>抽奖</title>
    <link rel="stylesheet" type="text/css" href="css/styleCJ.css" />
    <style>
        html{font-size: 20px;}
    </style>
</head>
<body>
    <div id="title">
        <h2 class="title">幸运抽大奖</h2>
    </div>
    <div class="my_integral">
        我的积分：<span class="integral_number" data-tk="points"></span>
    </div>
    <div id="luck">
        <!-- luck -->
        <h3 class="luck_time">您今日还可以抽 <span class="number" data-tk="can_use_count"></span> 次</h3>
        <ul class="luck_ul" data-tk="reward">
        </ul>
        <!-- luckEnd -->
    </div>
    <div class="rules">
        <a onclick="ldl_rule()"><img src="img/rules.png"></a>
    </div>
</body>
<script type="text/javascript" src="{{ URL::asset('js/jquery-1.10.2.js') }}"></script>
<script>
var ldl = {};

ldl.core = (function ($) {
    return {
        confirm: function (tipsContent, isTip, options) {
            var options = options || {};
            options.confirm = options.confirm || '确定';
            options.cancel = options.cancel   || '返回';
            options.confirmCallBack = options.confirmCallBack || function(){};
            if($('head').find('style[id="confirmCss"]').length==0) {
                var style = $("<style type='text/css' id='confirmCss'>\
                    .maskBg{touch-action: none;background:#000; position:fixed; z-index:999; display:none;left:0px; top:0px; bottom: 0px; width:100%; height:100%;opacity: 0.5; filter: alpha(opacity=50);}\
                    .maskBox{width: 80%; text-align: center; position:absolute; z-index:1000; background: #fff; border-radius: .4rem; padding: .2rem 0; display:none;}\
                    .maskBox .tipsContent{text-align: center; padding: .8rem .4rem; font-size: .6rem;}\
                    .maskBox .tipsSure{width: 100%; height: 1.2rem; color: #248DFE; line-height: 1.2rem; border-top: 1px solid #dbdbde; margin-top: .2rem;}\
                    .maskBox .tipsSure .btnHalf{width: 50%; height: 1.2rem; line-height: 1.2rem; font-size: .6rem; text-align: center; color: #222; display: block; float: left; margin-top: 0.2rem;}\
                    </style>");
                $('head').append(style);
            }

            var defer = $.Deferred();
            if(isTip == 1) {
                var box = $('\
                    <div id="MaskBox" class="maskBox">\
                        <div class="tipsContent"><ul style="width:100%;margin:0 auto;overflow:hidden;"><li style="float:left;width:100%;">\
                        <p style="display:table;margin:0 auto;text-align:left;word-break:break-all;word-wrap:break-word;font-size:0.8rem;"></p>\
                        </li></ul></div>\
                        <div class="tipsSure"><p onclick="javascript:void(0);" class="btn-confirm">确定</p></div>\
                    </div>\
                    <div id="maskBg" class="maskBg"></div>');
            } else if(isTip == 2) {
                var box = $('\
                    <div id="MaskBox" class="maskBox">\
                        <div class="tipsContent"><ul style="width:100%;margin:0 auto;overflow:hidden;"><li style="float:left;width:100%;">\
                        <p style="display:table;margin:0 auto;text-align:left;word-break:break-all;word-wrap:break-word;font-size:0.8rem;"></p>\
                        </li></ul></div>\
                        <div class="tipsSure">\
                            <span class="btnHalf btn-cancel" onclick="javascript:void(0);">'+options.cancel+'</span>\
                            <span class="btnHalf btn-confirm"  onclick="javascript:void(0);">'+options.confirm+'</span>\
                        </div>\
                    </div>\
                    <div id="maskBg" class="maskBg"></div>');
                box.find('.btn-cancel').css('border-right','1px solid #dbdbde');
                box.find('.btnHalf').css('width', '49%');
            }

            if($('#MaskBox').length==0) {
                $("body").after(box);
            }else{
                $('#MaskBox').remove();
                $('#maskBg').remove();
                $("body").after(box);
            }
            var boxConfirm = $('#MaskBox');
            $("#maskBg").show();//fadeIn("slow");
            $("#maskBg").css({ display: "block"});
            boxConfirm.find('.tipsContent>ul>li>p:first').html(tipsContent);

            var top = ($(window).height() - $(boxConfirm).height())/2;
            var left = ($(window).width() - $(boxConfirm).width())/2;
            var scrollTop = $(document).scrollTop();
            var scrollLeft = $(document).scrollLeft();
            $(boxConfirm).css({position:'absolute','top':top+scrollTop, left:left+scrollLeft}).show();

            // 绑定确认、取消按钮
            boxConfirm.find('.btn-confirm').bind('click', function () {
                boxConfirm.hide(0, function () {
                    defer.resolve(boxConfirm.find('.box-confirm-content').val());
                    boxConfirm.remove();
                    boxConfirm = null;
                });
                $("#maskBg").remove();
                options.confirmCallBack();
            });
            boxConfirm.find('.btn-cancel').one('click', function () {
                boxConfirm.hide(0, function () {
                    defer.reject();
                    boxConfirm.remove();
                    boxConfirm = null;
                });
                $("#maskBg").remove();
            });
            boxConfirm.show();
            return defer.promise();
        }
    };
})(jQuery);
</script>
<script>
var luck = {
    index: -1, //当前转动到哪个位置，起点位置
    count: 0, //总共有多少个位置
    timer: 0, //setTimeout的ID，用clearTimeout清除
    speed: 20, //初始转动速度
    times: 0, //转动次数
    cycle: 50, //转动基本次数：即至少需要转动多少次再进入抽奖环节
    prize: -1, //中奖位置
    start: function(id) {
        this.index = -1; //当前转动到哪个位置，起点位置
        this.count = 0; //总共有多少个位置
        this.timer = 0; //setTimeout的ID，用clearTimeout清除
        this.speed = 20; //初始转动速度
        this.times = 0; //转动次数
        this.cycle = 50; //转动基本次数：即至少需要转动多少次再进入抽奖环节
        this.prize = -1; //中奖位置
        this.running = true;
        if ($("#" + id).find(".luck-unit").length > 0) {
            $luck = $("#" + id);
            $units = $luck.find(".luck-unit");
            this.obj = $luck;
            this.count = $units.length;
            $luck.find(".luck-unit-" + this.index).addClass("active");
        };
    },
    roll: function() {
        var index = this.index;
        var count = this.count;
        var luck = this.obj;
        $(luck).find(".luck-unit-" + index).removeClass("active");
        index += 1;
        if (index > count - 1) {
            index = 0;
        };
        $(luck).find(".luck-unit-" + index).addClass("active");
        this.index = index;
        return false;
    },
    stop: function() {
        this.prize = -1;
        this.running = false;
        return false;
    },
    prizeIndex: function(index) {
        this.prize = index;
        return false;
    },
    isStart: function() {
        return this.running==true;
    }
};
window.Laravel = {
    csrfToken: "{{ csrf_token() }}"
}
var CInterface = {};
if(typeof(RewardInterface)==="undefined") {

} else {
    CInterface = RewardInterface;
}
$(document).ready(function(){
    /*
    var html = '';
    for(var k in window) {
        if(k == 'RewardInterface') {
            html += k + ':' + typeof(k) + '<br/>';
            for(kk in window[k]) {
                html += kk + ':' + typeof(kk) + '<br/>';
            }
        }
    }
    $('#winhtml').html(html);
    */
    $.ajax({
        dataType:'json',
        type: 'GET',
        url: '/api/reward?rand='+Math.random(),
        beforeSend: function(request) {
            if(CInterface && CInterface.getToken) {
                request.setRequestHeader("token", CInterface.getToken());
            } else {
                //request.setRequestHeader("token", "MDAwMDAwMDAwMJewg2WSu4GgtM_N2oR8qprJvrTOlqOYmZaMh86wmn_cgIt-rH6oeWmyqaPZhIx8pK7TvJaWfc-qjoh7m66Fi9t_e5ytfriFrrK9kp8");
            }
        },
        success:function(data) {
            if(data.status=='error') {
                ldl.core.confirm(data.error.message, 1);
            } else {
                $('[data-tk=points]').html(data.object.points);
                $('[data-tk=can_use_count]').html(data.object.can_use_count);
                for(k in data.object.list) {
                    var d = data.object.list[k];
                    $('[data-tk=reward]').append('<li class="luck-unit luck-unit-'+(parseInt(k)+0)+'" data-index="'+k+'" data-id="'+d.id+'"><img src="'+d.img_uri+'"></li>');
                }
                var luckunit = 'luck-unit-'+parseInt((data.object.list.length-1)/2);
                $('[data-tk=reward]>.'+luckunit).after('<li rowspan="2" colspan="2" class="cjBtn" id="btn" onclick="userreward()"><img src="img/5.png"></li>');
            }
        }
    });
});
function userreward() {
    if(parseInt($('[data-tk=points]').html()) < 20) {
        ldl.core.confirm('积分不足', 1);
        return;
    }
    if(parseInt($('[data-tk=can_use_count]').html()) <=0 ) {
        ldl.core.confirm('次数已用完', 1);
        return;
    }
    if(luck.isStart()) {
        return;
    }
    luck.start('luck');
    var rname = '';
    var roll = function() {
        luck.times += 1;
        luck.roll();
        if(luck.isStart()==false) {
            clearTimeout(luck.timer);
            luck.prize = -1;
            luck.times = 0;
            luck.obj.find(".luck-unit").removeClass("active");
            return;
        }
        if (luck.times > luck.cycle + 9 && luck.prize == luck.index) {
            clearTimeout(luck.timer);
            luck.prize = -1;
            luck.times = 0;
            luck.stop();
            if(rname) {
                ldl.core.confirm(rname, 1);
                rname = '';
            }
        } else {
            if (luck.times < luck.cycle) {
                luck.speed -= 9;
            } else if (luck.times == luck.cycle) {
                //var index = Math.random() * (luck.count) | 0;
                //luck.prize = index;
            } else {
                if (luck.times > luck.cycle + 9 && ((luck.prize == 0 && luck.index == 7) || luck.prize == luck.index + 1)) {
                    luck.speed += 110;
                } else {
                    luck.speed += 20;
                }
            }
            if (luck.speed < 40) {
                luck.speed = 40;
            };
            luck.timer = setTimeout(roll, luck.speed);
        }
    }
    roll();
    $.ajax({
        dataType:'json',
        type: 'POST',
        //url: '/api/reward?rand='+Math.random(),
        url: '/api/reward',
        beforeSend: function(request) {
            if(CInterface && CInterface.getToken) {
                request.setRequestHeader("token", CInterface.getToken());
            } else {
                //request.setRequestHeader("token", "MDAwMDAwMDAwMJewg2WSu4GgtM_N2oR8qprJvrTOlqOYmZaMh86wmn_cgIt-rH6oeWmyqaPZhIx8pK7TvJaWfc-qjoh7m66Fi9t_e5ytfriFrrK9kp8");
            }
        },
        success:function(data) {
            if(data.status=='error') {
                luck.stop(-1);
                ldl.core.confirm(data.error.message, 1);
            } else {
                var index = $('[data-tk=reward]>li[data-id='+data.object.reward_id+']').attr('data-index');
                luck.prizeIndex(index);
                $('[data-tk=points]').html(parseInt($('[data-tk=points]').html())-20);
                $('[data-tk=can_use_count]').html(parseInt($('[data-tk=can_use_count]').html())-1);
                rname = data.object.rname;
            }
        }
    });
}
function refresh() {
    location.href = location.pathname+"?rand="+Math.random();
}
function ldl_rule() {
    location.href = "/content/2?rand="+Math.random();
}
</script>
</html>