function upDown (id, statues)
{
	var msg = (statues == 1)? '开启' : '禁用';
	layer.confirm('确认要'+msg+'吗？',function(index){
		var url = "/adminlte/user/"+id;
		$.ajax({
			  type: 'POST',
			  url: url,
			  data: {'_token':LA.token, _method:'put',id:id, power:(statues==1 ? 0 : 1)},
			  success: function(response){
				  $('#result').html(response);
			  }
		});
	});
}
$(document.body).on('click', '.grid-row-statues', function(){
	var id = $(this).attr('data-id');
	var statues = $(this).attr('data-statues');
	var msg = statues==1? '开启' : '禁用';
	var indexLayer = layer.confirm('确认要['+msg+']吗？',function(index){
		$.ajax({
			method: 'post',
			url: '/adminlte/user/' + id,
			data: {
				_method:'put',
				_token:LA.token,
				statues:statues==1?0:1
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
		layer.close(indexLayer);
	});
});
$(document.body).on('click', '.grid-row-delete', function(){
	var id = $(this).attr('data-id');
	var indexLayer = layer.confirm('确认要[删除]吗？',function(index){
		$.ajax({
			method: 'post',
			url: '/adminlte/user/' + id,
			data: {
				_method:'delete',
				_token:LA.token
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
		layer.close(indexLayer);
	});
});
$(document).ready(function(){

});
