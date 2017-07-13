<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body>
<div id="app">
    <p id="reward"></p>
    <button onclick="userreward()">抽奖啊!</button>
    <button onclick=javascript:{refresh();}>刷新</button>
    <p id="winhtml"></p>
</div>
<script type="text/javascript">
    window.Laravel = {
        csrfToken: "{{ csrf_token() }}"
    }
</script>
<script type="text/javascript" src="{{ URL::asset('js/jquery-1.10.2.js') }}"></script>
<script>
    var CInterface = {};
    if(typeof(RewardInterface)==="undefined") {

    } else {
        CInterface = RewardInterface;
    }
$(document).ready(function(){
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
    $.ajax({
        dataType:'json',
        type: 'GET',
        url: '/api/reward',
        beforeSend: function(request) {
            if(CInterface && CInterface.getToken) {
                //request.setRequestHeader("token", "MDAwMDAwMDAwMJewg2WSu4GgtM_JlISyqprJvrTOlqOYmZaMh86wmn_cgIt-rH6oeWmzqbfahaJ8pK7TvJaWfc-qjoh7m66Fi9t_e4pkft6bbLKtkp8");
                request.setRequestHeader("token", CInterface.getToken());
            }
        },
        success:function(data) {
            if(data.status=='error') {
                alert(data.error.message);
            } else {
                for(k in data.object.list) {
                    var d = data.object.list[k];
                    $('#reward').append("<p>"+d.rname+"</p>");
                }
            }
        }
    });
});
function userreward(){
    alert(typeof(RewardInterface));
    alert(CInterface.getToken);
    $.ajax({
        dataType:'json',
        type: 'POST',
        url: '/api/reward',
        beforeSend: function(request) {
            if(CInterface && CInterface.getToken) {
                //request.setRequestHeader("token", "MDAwMDAwMDAwMJewg2WSu4GgtM_JlISyqprJvrTOlqOYmZaMh86wmn_cgIt-rH6oeWmzqbfahaJ8pK7TvJaWfc-qjoh7m66Fi9t_e4pkft6bbLKtkp8");
                request.setRequestHeader("token", CInterface.getToken());
            }
        },
        success:function(data) {
            if(data.status=='error') {
                alert(data.error.message);
            } else {
                alert(data.object.rname);
            }
        }
    });
}
function refresh() {
    location.href = document.URL+"?rand="+Math.random();
}
</script>
</body>
</html>