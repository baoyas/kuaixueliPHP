<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">
    <title>抽奖</title>
    <link rel="stylesheet" type="text/css" href="css/styleCJ.css" />
    <link rel="stylesheet" type="text/css" href="js/layer/mobile/need/layer.css"/>
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
<script type="text/javascript" src="js/layer/layer.js"></script>
<script>
var ldl = {};
ldl.alert = function(title, content) {
    var title = title || '';
    var content = content || '';
    $('#ldl_tips').remove();
    var html = $('\
        <div class="main" id="ldl_tips">\
            <div class="layer_content" id="ldl_tips_content">\
                <h1>'+title+'</h1>\
                <p>'+content+'</p>\
                <a class="okey">\
                    <img src="img/icon_btn_queding@3x.png"/>\
                </a>\
            </div>\
        </div>');
    html.find('.okey').click(function(){
        html.remove();
        $("#layui-layer-shade1").remove();
    });
    $('body').append(html);
    var outHight = "";
    $(window).resize(function(){
        outHight = $(".layui-layer").outerHeight()
    });
    layer.open({
        type: 1,
        title:false,
        closeBtn: 0, //不显示关闭按钮
        anim: 2,
        shadeClose: true, //开启遮罩关闭
        area:['80%', outHight],
        content: $('#ldl_tips_content')
    });
};
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
                alert(data.error.message);
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
        alert('积分不足');
        return;
    }
    if(parseInt($('[data-tk=can_use_count]').html()) <=0 ) {
        alert('次数已用完');
        return;
    }
    if(luck.isStart()) {
        return;
    }
    luck.start('luck');
    var reward = {};
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
            if(!$.isEmptyObject(reward)) {
                if(reward.type==0) {
                    ldl.alert('谢谢参与！', '不要气馁，也许幸运之神会在下次抽奖时降临呢！快去再抽一次吧～');
                } else if(reward.type==1){
                    ldl.alert('中奖啦！', '恭喜您！获得了<span>'+reward.rname+'</span>，红包已存入您的账户，您简直是幸运之星！快去再抽一次吧～');
                } else if(reward.type==2){
                    ldl.alert('中奖啦！', '恭喜您！获得了<span>'+reward.rname+'</span>，请在“我的“-“个人信息“填写好收货地址，我们将尽快为您发出~');
                } else if(reward.type==4){
                    ldl.alert('中奖啦！', '恭喜您！获得了<span>'+reward.rname+'</span>，积分已存入您的账户，您简直是幸运之星！快去再抽一次吧～');
                }
                reward = {};
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
                alert(data.error.message);
            } else {
                var index = $('[data-tk=reward]>li[data-id='+data.object.reward_id+']').attr('data-index');
                luck.prizeIndex(index);
                $('[data-tk=points]').html(parseInt($('[data-tk=points]').html())-20);
                $('[data-tk=can_use_count]').html(parseInt($('[data-tk=can_use_count]').html())-1);
                reward = data.object;
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