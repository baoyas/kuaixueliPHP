<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">
    <title>抽奖</title>
    <link rel="stylesheet" type="text/css" href="css/styleCJ.css" />
</head>

<body>

    <div id="title">
        <h2 class="title">幸运抽大奖</h2>
    </div>
    <div class="my_integral">
        我的积分：<span class="integral_number" data-tk="points">3329</span>
    </div>
    <div id="luck">
        <!-- luck -->
        <h3 class="luck_time">您今日还可以抽 <span class="number" data-tk="can_use_count">10</span> 次</h3>
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
    stop: function(index) {
        this.prize = index;
        this.running = false;
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
                request.setRequestHeader("token", "MDAwMDAwMDAwMJewg2WSu4GgtM_N2oR8qprJvrTOlqOYmZaMh86wmn_cgIt-rH6oeWmyqaPZhIx8pK7TvJaWfc-qjoh7m66Fi9t_e5ytfriFrrK9kp8");
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
    if(luck.isStart()) {
        return;
    }
    luck.start('luck');
    var roll = function() {
        luck.times += 1;
        luck.roll();
        if (luck.times > luck.cycle + 9 && luck.prize == luck.index) {
            clearTimeout(luck.timer);
            luck.prize = -1;
            luck.times = 0;
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
            }
        },
        success:function(data) {
            if(data.status=='error') {
                alert(data.error.message);
            } else {
                //alert(data.object.rname);
                var index = $('[data-tk=reward]>li[data-id='+data.object.id+']').attr('data-index');
                luck.stop(index);
                $('[data-tk=points]').html(parseInt($('[data-tk=points]').html())-20);
                $('[data-tk=can_use_count]').html(parseInt($('[data-tk=can_use_count]').html())-1);
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