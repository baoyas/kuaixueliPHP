@extends('layouts.admin')
@section('content')
	<div class="main">
		<div class="title_big bline pb10">城市管理</div>
        <div class="mt15 cf"><a href="{{url('admin/cityList/create')}}" class="btn_add right ml50">添加城市</a></div>
		<table class="mt15">
			<tr class="head">
			  <td>城市编号</td>
              <td>城市名称</td>
              <td>首字母</td>
              <td>添加时间</td>
              <td>是否热门</td>
			  <td>状态</td>
			  <td class="w-100">操作</td>
			</tr>
			@foreach($city as $v)
				<tr>
					<td>{{$v->city_code}}</td>
					<td>{{$v->city_name}}</td>
					<td>{{$v->letter}}</td>
					<td>{{date('Y-m-d H:i:s', $v->dredge_time)}}</td>
					<td>
						@if($v->is_hot == 1)
							(热门)<a class="stat_0" href="javascript:;" onclick="IsHot('{{$v->id}}',0)">普通</a>
						@else
							(普通)<a class="stat_1" href="javascript:;" onclick="IsHot('{{$v->id}}',1)">热门</a>
						@endif
					</td>

					<td>
						@if ($v->power == 0)
							<a class="stat_1" href="javascript:;" onclick="UpDown('{{$v->id}}',1)">开启</a>
						@else
							<a class="stat_0" href="javascript:;" onclick="UpDown('{{$v->id}}',0)">禁用</a>
						@endif

					</td>
					<td>
						<a href="{{url('admin/cityList/'.$v->id.'/edit')}}">修改</a>
						<a href="javascript:;" onclick="Del('{{$v->id}}')">删除</a>
					</td>
				</tr>
			@endforeach
  		</table>
		{{$city->links()}}
	</div>
</div>
	<script>
		function UpDown (id, power) {
			var msg = (power == 0)?'禁用':'开启';
			layer.confirm('确认要'+msg+'吗？',function(index){
				//此处请求后台程序，下方是成功后的前台处理……
				var url = "{{url('admin/city/updown')}}";
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

		function IsHot (id, power) {
			var msg = (power == 0)?'普通':'热门';
			layer.confirm('确认要转成 ' + msg + ' 吗？',function(index){
				//此处请求后台程序，下方是成功后的前台处理……
				var url = "{{url('admin/city/ishot')}}";
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
			layer.confirm('确认要删除该城市吗？',function(index){
				//此处请求后台程序，下方是成功后的前台处理……
				var url = "{{url('admin/cityList/')}}"+'/'+id;
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
