@extends('layouts.admin')
@section('content')
	<div class="main">

		<div class="title_big bline pb10"><span><a href="{{url('admin/adplace')}}">广告显示位置</a> | <a href="{{url('admin/adskip')}}">广告跳转方式</a></span>广告列表</div>
			<ul class="ad mt15">
			<li><a href="{{url('admin/ad/create')}}">添加图片广告<span>图片广告在客户端首页顶部或分类列表页面顶部显示，可以设定不同的跳转方式</span></a></li>
		</ul>
		<table class="mt15">
			<tr class="head">
			  <td>ID</td>
			  <td>名称</td>
			  <td>位置</td>
			  <td>类型/目标</td>
			  <td>有效日期</td>
			  <td>排序</td>
			  <td>状态</td>
			  <td class="w-120">操作</td>
			</tr>
			@foreach($data as $k=>$v)
				<tr>
				  <td>{{$v->id}}</td>
				  <td class="obj_name" date="{{config('web.QINIU_URL')}}/{{$v->ad_object_thumb}}">{{$v->ad_object_name}}</td>
				  <td>{{$v->ad_place_name}}</td>
				  <td><p class="green">{{$v->ad_skip_name}}</p><p>{{$v->ad_skip_describe}}{{$v->ad_object_aim}}</p></td>
				  <td><p>{{date('Y-m-d H:i:s', $v->ad_start_at)}}</p><p>{{date('Y-m-d H:i:s', $v->ad_end_at)}}</p></td>
				  <td><input type="text" value="{{$v->ad_object_sort}}" style="width:50px" onchange="changeOrder(this,'{{$v->id}}')"></td>
					@if ($v->ad_object_power)
						<td class="stat_0">禁用</td>
					@else
						<td class="stat_1">开启</td>
					@endif
				  <td>
					  <a href="{{url('admin/ad/'.$v->id.'/edit')}}">修改</a>
					  @if($v->ad_object_power)
						  <a href="javascript:;" onclick="UpDown('{{$v->id}}',0)">开启</a>
					  @else
						  <a href="javascript:;" onclick="UpDown('{{$v->id}}',1)">禁用</a>
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
		//开启或关闭
		function UpDown (id, power) {
			var msg = (power == 1)?'禁用':'开启';
			layer.confirm('确认要'+msg+'吗？',function(index){
				//此处请求后台程序，下方是成功后的前台处理……
				var url = "{{url('admin/ad/upDown/')}}";
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

		/**
		 * 修改排序
		 * @param obj
         * @param id
         */
		function changeOrder (obj, id)
		{
			var url = "{{url('admin/ad/changeorder')}}";
			var conf_order = $(obj).val();
			$.post(url, {id:id, ad_object_sort:conf_order,'_token':'{{csrf_token()}}'}, function (data) {
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

		/**
		 * 鼠标经过显示图片
         */
		$('.obj_name').hover(function () {
			var img_url = $(this).attr('date');
			var imgs = '<img src="'+img_url+'" style="width: 150px;height:80px" alt="">';
			layer.tips(imgs, $(this), {
				tips: [1, '#4EB2FF']
			});
		}, function () {

		});
		/**
		 * 广告删除
		 * @param id
         * @constructor
        */
		function Del (id)
		{
			layer.confirm('确认要删除该广告吗？',function(index){
				//此处请求后台程序，下方是成功后的前台处理……
				var url = "{{url('admin/ad/')}}"+'/'+id;
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
