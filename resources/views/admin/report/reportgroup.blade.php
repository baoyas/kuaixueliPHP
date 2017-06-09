@extends('layouts.admin')
@section('content')
	<div class="main">
		<div class="title_big bline pb10">投诉列表 <span><a href="{{url('/admin/report')}}">用户投诉</a></span></div>
        <div class="mt15 cf"></div>
		<table class="mt15">
			<tr class="head">
			  <td>ID</td>
              <td>投诉人</td>
              <td class="w-400">投诉内容</td>
              <td>被投群</td>
			  <td>状态</td>
			  <td class="w-100">操作</td>
			</tr>
			@foreach($data as $v)
				<tr>
				  <td>{{$v->id}}</td>
					<td><a href="{{url('admin/user/'.$v->report_uid)}}">{{$v->nickname}}</a></td>
				  <td class="article_title" date="{{$v->report_handle}}">{{$v->report_content}}</td>
				  <td><a href="{{url('admin/user/'.$v->owner_uid)}}" title="查看群管理员">{{$v->group_name}}</a></td>
				  <td>
					  @if ($v->report_statues == 1)
						  <span class="stat_1">已处理</span>
					  @else
						  <span class="stat_0">未处理</span>
					  @endif

				  </td>
				  <td>
					  @if($v->report_statues == 0)
						  <a href="javascript:;" onclick="UpDown('{{$v->id}}')">处理</a>
					  @else

					  @endif

				  </td>
				</tr>
			@endforeach
  		</table>
		{{$data->links()}}
	</div>
</div>
	<script>
		function UpDown (id) {
			layer.confirm('处理将会给被处理者发送系统通知，确认要处理吗？',function(index){
				layer.close(index);
				layer.prompt({title: '被投诉群处理结果！', formType: 2}, function(text, index){
					layer.close(index);
					layer.prompt({title: '投诉人处理结果！', formType: 2}, function(texts, index){
						layer.close(index);
						//此处请求后台程序，下方是成功后的前台处理……
						var url = "{{url('admin/reportGroup/updown')}}";
						$.post(url, {'_token':'{{csrf_token()}}', id:id, content:text, contents:texts}, function(data){
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
