@extends('layouts.admin')
@section('content')
	<link rel="stylesheet" href="{{asset('css/app.css')}}">
	<div class="main">
		<div class="title_big bline pb10">广告跳转位置列表</div>
        <div class="mt15 cf"><a href="{{url('admin/adskip/create')}}" class="btn_add right ml50">添加跳转</a></div>
		<table class="mt15">
			<tr class="head">
			  <td>ID</td>
			  <td>跳转名称</td>
			  <td>跳转方式</td>
			  <td class="w-120">操作</td>
			</tr>
			@foreach($data as $k=>$v)
				<tr class="order">
				  <td>{{$v->id}}</td>
				  <td>{{$v->ad_skip_name}}</td>
					<td>{{$v->ad_skip_describe}}</td>
				  <td>
					  <a href="{{url('admin/adskip/'.$v->id.'/edit')}}">修改</a>
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
			layer.confirm('确认要删除该分类吗？',function(index){
				//此处请求后台程序，下方是成功后的前台处理……
				var url = "{{url('admin/adskip/')}}"+'/'+id;
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
	</script>
@endsection
