@extends('layouts.admin')
@section('content')
	<div class="main">
		<div class="title_big bline pb10"><span><a href="{{url('admin/sell')}}">列表</a></span>信息详情</div>
		<div class="user_view b1s mt15 cf">
			<div class="u_v_left">
				<img src="{{config('web.QINIU_URL')}}/{{$data->user_face}}" />
				<p class="mt10"><b>{{$data->nickname}}</b></p>
				<p>{{$data->phone}}</p>
				<p class="pb10 bline mb10">ID:{{$data->id}}</p>
                <p>发布时间：{{date('Y-m-d H:i:s', $data->sell_time)}}</p>
				<p>点赞量: 1565523</p>
				<p>评论量: 5555</p>
                <p class="mb15">是否推荐:
					@if ($data->recommend ==1)
						<span class="stat_1">是</span>
					@else
						<span class="stat_0">否</span>
					@endif
				</p>
                <a class="btn mr5" href="{{url('admin/sell/'.$data->id.'/edit')}}">修改</a>
				@if($data->recommend == 1)
					<a href="javascript:;" class="btn mr5" onclick="UpDown('{{$data->id}}',0)">禁用</a>
				@else
					<a href="javascript:;" class="btn mr5" onclick="UpDown('{{$data->id}}',1)">开启</a>
				@endif
			</div>
			<div class="u_v_right pb15">
				<div class="title_small mt20 bline pb10">{{$data->sell_title}}</div>
                <div class="article_content">
                    <p>{!! \App\Helpers\Helpers::replace_str($data->sell_describe) !!}</p>
                    <div class="article_pic">
						@if($data->sell_pic == null)
							<video src="{{$data->sell_video}}"></video>
						@else
							@foreach($data->sell_pic as $v)
								<img src="{{config('web.QINIU_URL')}}/{{$v}}" class="photo" />
							@endforeach
						@endif
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
	<script>
		function UpDown (id, power) {
			var msg = (power == 0)?'禁用':'开启';
			layer.confirm('确认要'+msg+'吗？',function(index){
				//此处请求后台程序，下方是成功后的前台处理……
				var url = "{{url('admin/sell/updown')}}";
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
	</script>
@endsection
