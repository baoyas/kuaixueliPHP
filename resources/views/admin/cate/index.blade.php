@extends('layouts.admin')
@section('content')
	<link rel="stylesheet" href="{{asset('css/app.css')}}">
	<div class="main">
		<div class="title_big bline pb10">分类列表</div>
        <div class="mt15 cf"><a href="{{url('admin/cate/create?pid=0')}}" class="btn_add right ml50">添加分类</a></div>
		<table class="mt15">
			<tr class="head">
			  <td>ID</td>
			  <td>名称</td>
			  <td>排序</td>
			  <td>状态</td>
			  <td class="w-120">操作</td>
			</tr>
			@foreach($data as $k=>$v)
				<tr class="order">
				  <td>{{$v->id}}</td>
				  <td>{{$v->_cate_name}}</td>
				  <td><input type="text" style="width: 35px" onchange="changeOrder(this,{{$v->id}})" value="{{$v->cate_sort}}"></td>
				  <td>
					  @if($v->cate_power == 1)
						  <span class="stat_1">可用</span>
					  @else
						  <span class="stat_0">禁用</span>
					  @endif

				  </td>
				  <td>
					  @if ($v->over)
					  @else
					  	<a href="{{url('admin/cate/create?pid='.$v->id)}}">添加</a>
					  @endif
					  <a href="{{url('admin/cate/'.$v->id.'/edit')}}">修改</a>
					  @if($v->cate_power == 1)
						  <a href="javascript:;" onclick="UpDown('{{$v->id}}',0)">禁用</a>
					  @else
						  <a href="javascript:;" onclick="UpDown('{{$v->id}}',1)">开启</a>
					  @endif
					  <a href="javascript:;" onclick="Del('{{$v->id}}')">删除</a>
				  </td>
				</tr>
			@endforeach
  		</table>
		{{--{{$data->links()}}--}}
	</div>
</div>
	<script>
		function changeOrder (obj, conf_id)
		{
			var url = "{{url('admin/cate/changeorder')}}";
			var conf_order = $(obj).val();
			$.post(url, {id:conf_id, conf_order:conf_order,'_token':'{{csrf_token()}}'}, function (data) {
				if (data.status == 0)
				{
					layer.msg(data.msg, {icon:6});
				}
				else
				{
					layer.msg(data.msg, {icon:5});
				}
			}, 'json');
		}
		function UpDown (id, power) {
			var msg = (power == 0)?'禁用':'开启';
			layer.confirm('确认要'+msg+'吗？',function(index){
				//此处请求后台程序，下方是成功后的前台处理……
				var url = "{{url('admin/cate/upDown/')}}";
				$.post(url, {'_token':'{{csrf_token()}}', id:id, power:power}, function(data){
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

		function Del (id)
		{
			layer.confirm('确认要删除该分类吗？',function(index){
				//此处请求后台程序，下方是成功后的前台处理……
				var url = "{{url('admin/cate/')}}"+'/'+id;
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
