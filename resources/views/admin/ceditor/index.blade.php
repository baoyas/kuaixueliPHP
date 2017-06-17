@extends('layouts.admin')
@section('content')
	@include('UEditor::head')
	<link rel="stylesheet" href="{{asset('css/app.css')}}">
	<div class="main">
		<div class="title_big bline pb10">内容列表</div>
        <div class="mt15 cf"><a href="{{url('admin/ceditor/create')}}" class="btn_add right ml50">添加内容</a></div>
		<table class="mt15">
			<tr class="head">
				<td>ID</td>
				<td>跳转URL</td>
				<td class="w-160">操作</td>
			</tr>
			@foreach($data as $k=>$v)
				<tr class="order">
					<td>{{$v->id}}</td>
					<td>{{url('content/'.$v->id)}}</td>
					<td>
					    <a href="{{url('content/'.$v->id)}}" target="_blank">查看</a>
						<a href="{{url('admin/ceditor/'.$v->id.'/edit')}}">修改</a>
						<a href="javascript:;" onclick="Del('{{$v->id}}')">删除</a>
					</td>
				</tr>
			@endforeach
		</table>
		{{$data->links()}}
	</div>
</div>
@endsection
