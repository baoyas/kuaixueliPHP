function upDown (id, statues)
{
	var msg = (statues == 1)? '开启' : '禁用';
	var _token = $('meta[name="csrf-token"]').attr('content');
	layer.confirm('确认要'+msg+'吗？',function(index){
		var url = "/adminlte/user/"+id;
		$.ajax({
			  type: 'POST',
			  url: url,
			  headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
			  data: {'_token':_token, _method:'put',id:id, power:(statues==1 ? 0 : 1)},
			  success: function(response){
				  $('#result').html(response);
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
	});
});
