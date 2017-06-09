@extends('layouts.admin')
@section('content')
	<link rel="stylesheet" href="{{asset('css/app.css')}}">
	<div class="main">
		<div class="title_big bline pb10">意见反馈列表</div>
        <div class="mt15 cf"></div>
		<table class="mt15">
			<tr class="head">
			  <td>ID</td>
			  <td>用户昵称</td>
			  <td>意见</td>
				<td>提交时间</td>
			  <td class="w-120">操作</td>
			</tr>
			@foreach($data as $k=>$v)
				<tr class="order">
					<td>{{$v->id}}</td>
				  <td>{{$v->nickname}}</td>
				  <td class="article_title" date="{{$v->findback_handle}}">{!! \App\Helpers\Helpers::replace_str($v->findback_content) !!}</td>
					<td>{{date('Y-m-d H:i:s', $v->findback_time)}}</td>
				  <td>
					  @if($v->findback_handle == null)
						  <a href="javascript:;" onclick="UpDown('{{$v->id}}')">处理</a>
					  @endif
					  <a href="javascript:;" onclick="Del('{{$v->id}}')">删除</a>
				  </td>
				</tr>
			@endforeach
  		</table>
		{{$data->links()}}
	</div>
</div>
	<script>

		function Del (id)
		{
			layer.confirm('确认要删除该意见吗？',function(index){
				//此处请求后台程序，下方是成功后的前台处理……
				var url = "{{url('admin/findback/')}}"+'/'+id;
				$.post(url, {'_method':'delete', '_token':'{{csrf_token()}}', id:id}, function(data){
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

		function UpDown (id) {
			layer.confirm('处理将会给提意见者发送系统通知，确认要处理吗？',function(index){
				layer.close(index);
				layer.prompt({title: '请填写处理结果！', formType: 2}, function(text, index){
					layer.close(index);
					//此处请求后台程序，下方是成功后的前台处理……
					var url = "{{url('admin/findback/updown')}}";
					$.post(url, {'_token':'{{csrf_token()}}', id:id, content:text}, function(data){
						if (data.status == 0)
						{
							layer.msg(data.msg,{icon:1,time:1000});
							setTimeout(function(){
//								location.href = location.href;
							},2000);
						}
						else
						{
							layer.msg(data.msg,{icon:2,time:1000});
							setTimeout(function(){
//								location.href = location.href;
							},2000);
						}
					});
				});
			});
		}


		/**
		 * 鼠标经过显示图片
		 */
		$('.article_title').hover(function () {
			var img_url = $(this).attr('date');
			if (img_url != '')
			{
				var imgs = '<h5>'+img_url+'</h5>';
				layer.tips(imgs, $(this), {
					tips: [1, '#4EB2FF']
				});
			}
		}, function () {

		});

	</script>
@endsection
