@extends('layouts.admin')
@section('content')
	<div class="main">
		<div class="title_big bline pb10">用户详情</div>
		<div class="user_view b1s mt15 cf">
			<div class="u_v_left">
				<img src="{{config('web.QINIU_URL')}}/{{$user->user_face}}" />
				<p class="mt10"><b>{{$user->nickname}}</b></p>
				<p>{{$user->phone}}</p>
				<p>ID:{{$user->id}}</p>
				<p class="identity">
					普通用户
				</p>
				<p class="pb10 bline mb10">{{$user->autograph}}</p>
				<p>注册时间：{{date('Y-m-d H:i:s', $user->user_reg_time)}}</p>
				<p>地区：{{$user->area}}</p>
				<p>地址：{{$user->address}}</p>
				<p class="mb15">-</p>
				<a class="btn mr5" style="width:80px" href="{{url('admin/user/Reset?uid='.$user->id)}}">修改密码</a>
				{{--<a class="btn mr5" href="{{url('admin/user/'.$user->id.'/edit')}}">编辑</a>--}}
				@if($user->statues == 1)
					<a href="javascript:;" class="btn mr5" onclick="upDown('{{$user->id}}', 0)">开启</a>
				@else
					<a href="javascript:;" class="btn mr5" onclick="upDown('{{$user->id}}', 1)">禁用</a>
				@endif
				<a class="btn"  href="javascript:;" onclick="del('{{$user->id}}')">删除</a>
			</div>
			<div class="u_v_right">
				<div class="title_small mt10 bline pb10">TA的发布<span><a href="{{url('admin/sell/create')}}">发布</a></span></div>
					<table class="mt15">
						<tr class="head">
							<td class="w-60">ID</td>
							<td class="w-120">类别</td>
							<td>标题</td>
							<td class="w-160">发布时间</td>
							<td class="w-100">编辑</td>
						</tr>
						</table>
                        <div class="scroll">
						<table class="data_list">
							@foreach($sell as $k=>$v)
								<tr>
									<td class="w-60">{{$v->id}}</td>
									<td class="w-120">
										@if ($v->is_sell == 1 && $v->is_circle == 0)
											出售
										@elseif($v->is_circle == 1)
											朋友圈
										@else
											购买
										@endif
									</td>
									<td>
										@if($v->sell_title == null)
											朋友圈
										@else
											{{$v->sell_title}}
										@endif
									</td>
									<td class="w-160">{{date('Y-m-d H:i:s', $v->sell_time)}}</td>
									@if ($v->is_circle == 1)
										<td class="w-100"><a href="javascript:;" onclick="delFriends('{{$v->id}}')">删除</a></td>
									@else
										<td class="w-100"><a href="{{url('admin/sell/'.$v->id)}}">查看</a></td>
									@endif
								</tr>
							@endforeach
  						</table>
                    </div>
                    {{--{{$redpackage->links()}}--}}
			</div>
		</div>
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
							location.href = "{{url('admin/user')}}";
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

		function delFriends (id)
		{
			layer.confirm('确认要删除该朋友圈吗？',function(index){
				//此处请求后台程序，下方是成功后的前台处理……
				var url = "{{url('admin/sell/')}}"+'/'+id;
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
