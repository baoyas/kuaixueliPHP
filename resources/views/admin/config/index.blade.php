@extends('layouts.admin')
@section('content')

	<div class="main">
		<div class="title_big bline pb10">系统配置</div>
		<a href="{{url('admin/config/create')}}" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加配置项</a> 　其他配置请自行添加
		<form class="form form-horizontal" id="form-article-add" action="{{url('admin/config/changecontent')}}" method="post">
			{{csrf_field()}}
			<table class="mt15">
				<tr class="head">
					<td>排序</td>
					<td class="w-120">ID</td>
					<td class="w-180">标题</td>
					<td class="w-180">名称</td>
					<td>内容</td>
					<td class="w-100">操作</td>
				</tr>
				@foreach ($data as $key => $value)
					<tr>
					  <td><input type="text" style="width: 35px" onchange="changeOrder(this,{{$value->conf_id}})" value="{{$value->conf_order}}"></td>
						<td>{{$value->conf_id}}</td>
					  <td title="提示：{{$value->conf_tips}}">{{$value->conf_title}}</td>
					  <td>{{$value->conf_name}}</td>
					  <td><input type="hidden" name="conf_id[]" value="{{$value->conf_id}}">{!!$value->_html!!}</td>
					  <td>
						  <a href="{{url('admin/config/'.$value->conf_id.'/edit')}}">修改</a>
						  <a onclick="delConf({{$value->conf_id}})" href="javascript:;" title="删除">删除</a>
					  </td>
					</tr>
				@endforeach
			</table>
			<button class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存并提交</button>
		</form>
	</div>
</div>
	<script>
		function changeOrder (obj, conf_id)
		{
			var url = "{{url('admin/config/changeorder')}}";
			var conf_order = $(obj).val();
			$.post(url, {conf_id:conf_id, conf_order:conf_order,'_token':'{{csrf_token()}}'}, function (data) {
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
		//删除配置项
		function delConf (conf_id)
		{
			layer.confirm('您确定要删除此配置项吗？', {
				btn: ['确定','取消'] //按钮
			}, function(){
				var url = "{{url('admin/config')}}"+'/'+conf_id;
				$.post(url, {'_method':'delete', 'conf_id':conf_id, '_token':'{{csrf_token()}}'}, function (data) {
					if (data.status == 0)
					{
						layer.msg(data.msg, {icon:1});
						setTimeout(function(){
							location.href = location.href;
						},2000);
					}
					else
					{
						layer.msg(data.msg, {icon:2});
					}
				}, 'json');
			}, function(){

			});
		}
	</script>
@endsection
