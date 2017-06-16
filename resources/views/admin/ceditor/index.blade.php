@extends('layouts.admin')
@section('content')
	@include('UEditor::head')
	<link rel="stylesheet" href="{{asset('css/app.css')}}">
	<div class="main">
		<div class="title_big bline pb10">广告跳转位置列表</div>
        <div class="mt15 cf"><a href="{{url('admin/ceditor/create')}}" class="btn_add right ml50">添加内容</a></div>
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
						<a href="{{url('admin/ceditor/'.$v->id.'/edit')}}">修改</a>
						<a href="javascript:;" onclick="Del('{{$v->id}}')">删除</a>
					</td>
				</tr>
			@endforeach
		</table>
		{{$data->links()}}
		<!-- 加载编辑器的容器 -->
		<script id="container" name="content" type="text/plain">
			这里写你的初始化内容
		</script>
		<!-- 实例化编辑器 -->
		<script type="text/javascript">
			var ue = UE.getEditor('container');
		</script>
	</div>
</div>
@endsection
