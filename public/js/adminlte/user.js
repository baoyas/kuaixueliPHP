
function upDown (id, statues)
{
	var msg = (statues == 1)? '开启' : '禁用';
	layer.confirm('确认要'+msg+'吗？',function(index){
		var url = "/adminlte/user/upDown";
		$.post(url, {'_token':$(this).attr('data-token'), id:id, power:(statues==1 ? 0 : 1)}, function(data){
			if (data.status == 0)
			{
				layer.msg(data.msg,{icon:1,time:1000});
				setTimeout(function(){
					location.href = location.href;
				},2000);
			}
			else
			{
				layer.msg(data.msg,{icon:2,time:1000});
				setTimeout(function(){
					location.href = location.href;
				},2000);
			}
		});
	});
}
$(document).ready(function(){
	$('.grid-row-disabless').unbind('click').click(function() {
	    if(confirm("确认禁用")) {
	        $.ajax({
	            method: 'post',
	            url: '' + $(this).data('id'),
	            data: {
	                //_method:'delete',
	                //_token:LA.token,
	            },
	            success: function (data) {
	                $.pjax.reload('#pjax-container');
	                if (typeof data === 'object') {
	                    if (data.status) {
	                        toastr.success(data.message);
	                    } else {
	                        toastr.error(data.message);
	                    }
	                }
	            }
	        });
	    }
	    /*
	    var msg = (power == 1)?'禁用':'开启';
			layer.confirm('确认要'+msg+'吗？',function(index){
				//此处请求后台程序，下方是成功后的前台处理……
				var url = "http://api.ldlchat.com/admin/user/upDown";
				$.post(url, {'_token':'8MMVTC3LnV6mbuQnLCHQefVL71dCbJFCCl7l72J9', id:id, power:power}, function(data){
					if (data.status == 0)
					{
						layer.msg(data.msg,{icon:1,time:1000});
						setTimeout(function(){
							location.href = location.href;
						},2000);
					}
					else
					{
						layer.msg(data.msg,{icon:2,time:1000});
						setTimeout(function(){
							location.href = location.href;
						},2000);
					}
				});
			});
	     */
	});
});
