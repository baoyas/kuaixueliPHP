@extends('layouts.admin')
@section('content')
	<div class="main">
		<div class="title_big bline pb10">用户列表</div>
		@if (count($errors) > 0)
			<div class="cl pd-5 bg-1 bk-gray mt-20" style="text-align: center;color: red">
				@if (is_object($errors))
					@foreach($errors->all() as $error)
						{{$error}}<br />
					@endforeach
				@else
					{{$errors}}<br />
				@endif
			</div>
		@endif
		<div class="mt15">
			<a href="{{url('admin/user/create')}}" class="btn_add right ml10">添加用户</a>
			<form action="{{url('admin/user/search')}}" method="post">
				{{csrf_field()}}
				<div class="seek right">
					<input type="text" placeholder="输入用户手机号或昵称" name="search_name" />
					<input type="submit" class="iconfont" value="&#xe65c;" />
					<input type="hidden" name="show" value="{{$show}}">
				</div>
			</form>
			<a class="btn mr5" href="{{url('admin/user')}}">全部用户</a>
			<a class="btn mr5" href="{{url('admin/user/users')}}">禁用用户</a>
		</div>
		<table class="mt15">
			<tr class="head">
			  <td>ID</td>
			  <td>手机号</td>
			  <td>头像</td>
			  <td>昵称</td>
			  <td>注册时间</td>
			  <td>状态</td>
			  <td class="w-200">操作</td>
			</tr>
			@if($data->total() == 0)
				<tr>
					<td colspan="9" style="text-align: center;color: red">没有查找到该用户...<a href="{{url('admin/user')}}">返回用户列表</a></td>
				</tr>
			@else
				@foreach($data as $k=>$v)
					<tr>
						<td>{{$v->id}}</td>
						<td>{{$v->phone}}</td>
						<td class="icon48"><img src="{{config('web.QINIU_URL')}}/{{$v->user_face}}" /></td>
						<td>{{$v->nickname}}</td>
						<td class="time w-120">{{date('Y-m-d H:i:s', $v->user_reg_time)}}</td>
						<td>
							@if($v->statues == 0)
								<span class="stat_1">正常</span>
							@else
								<span class="stat_0">禁用</span>
							@endif
						</td>
						<td>
							@if ($v->phone == config('web.DEFAULT_UID'))
							@else
								<a href="{{url('admin/user/'.$v->id)}}">查看</a>
								{{--<a href="{{url('admin/user/'.$v->id.'/edit')}}">修改</a>--}}
								@if($v->statues == 1)
									<a href="javascript:;" onclick="upDown('{{$v->id}}', 0)">开启</a>
								@else
									<a href="javascript:;" onclick="upDown('{{$v->id}}', 1)">禁用</a>
								@endif
								<a href="javascript:;" onclick="del({{$v->id}})">删除</a>
								<a href="{{url('admin/user/Private?uid='.$v->id)}}" >私聊</a>
							@endif
						</td>
					</tr>
				@endforeach
			@endif
  		</table>
		{{$data->links()}}
	</div>
</div>
	<script>
		function upDown (id, power)
		{
			var msg = (power == 1)?'禁用':'开启';
			layer.confirm('确认要'+msg+'吗？',function(index){
				//此处请求后台程序，下方是成功后的前台处理……
				var url = "{{url('admin/user/upDown/')}}";
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
		function del (id)
		{
			layer.confirm('确认要删除该用户吗？',function(index){
				//此处请求后台程序，下方是成功后的前台处理……
				var url = "{{url('admin/user/')}}"+'/'+id;
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
