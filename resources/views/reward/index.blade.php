<!DOCTYPE html>
<html>
<head>
    <title>{{ config('app.name') }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="icon" href="https://www.qiyichao.cn/image/fire-orange.png">
    <link rel="apple-touch-icon" href="https://www.qiyichao.cn/image/fire-orange.png">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/app.css') }}">
</head>
<body>
<div id="app">
    <p id="reward"></p>
    <button onclick="userreward()">抽奖</button>
</div>
<script type="text/javascript">
    window.Laravel = {
        csrfToken: "{{ csrf_token() }}"
    }
</script>
<script type="text/javascript" src="{{ URL::asset('js/jquery-1.10.2.js') }}"></script>
<script>
$(document).ready(function(){
    $.ajax({
        dataType:'json',
        type: 'GET',
        url: '/api/reward',
        beforeSend: function(request) {
            //request.setRequestHeader("token", "MDAwMDAwMDAwMJewg2WSu4GgtM_JlISyqprJvrTOlqOYmZaMh86wmn_cgIt-rH6oeWmzqbfahaJ8pK7TvJaWfc-qjoh7m66Fi9t_e4pkft6bbLKtkp8");
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

}
</script>
</body>
</html>