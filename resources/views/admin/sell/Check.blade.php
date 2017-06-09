@extends('layouts.admin')
@section('content')
	<div class="main">
		<div class="title_big bline pb10">出售/购买</div>
        <div class="mt15 cf">
			<form action="{{url('admin/sell/Check')}}" method="post">
				{{csrf_field()}}
				<div class="seek right">
					<input type="text" name="search" placeholder="输入用户手机号" />
					<input type="submit" class="iconfont" value="&#xe65c;" />
				</div>
			</form>
		</div>
		<table class="mt15">
			<tr class="head">
			  <td>ID</td>
              <td>排序</td>
              <td>类别</td>
              <td class="w-400">标题</td>
              <td>用户</td>
			  <td>是否推荐</td>
			  <td class="w-100">操作</td>
			</tr>
			@if ($data->total() == 0)
				<tr>
					<td colspan="7" style="text-align: center">没有查找到数据！</td>
				</tr>
			@else
				@foreach($data as $v)
					<tr>
						<td>{{$v->id}}</td>
						<td><input type="text" value="{{$v->sell_order}}" style="width:50px" onchange="changeOrder(this,'{{$v->id}}')"></td>
						<td>
							@if ($v->is_sell == 1 && $v->is_circle == 0)
								购买
							@elseif($v->is_sell == 3)
								朋友圈
							@elseif ($v->is_sell = 2)
								出售
							@endif
						</td>
						<td class="article_title">
							@if($v->sell_title == null)
								朋友圈
							@else
								{{$v->sell_title}}
							@endif
						</td>
						<td><p>{{$v->nickname}}</p><p>{{$v->phone}}</p></td>
						<td>
							@if ($v->recommend == 1)
								<span class="stat_1">是</span>
							@else
								<span class="stat_0">否</span>
							@endif

						</td>
						<td>
							<a href="{{url('admin/sell/'.$v->id)}}">查看</a>
							<a href="{{url('admin/sell/'.$v->id.'/edit')}}">修改</a>
							@if($v->recommend == 1)
								<a href="javascript:;" onclick="UpDown('{{$v->id}}',0)">禁用</a>
							@else
								<a href="javascript:;" onclick="UpDown('{{$v->id}}',1)">开启</a>
							@endif

						</td>
					</tr>
				@endforeach
			@endif
  		</table>
		{{$data->appends(['search'=>$search])->links()}}
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

        /**
         * 修改排序
         * @param obj
         * @param id
         */
        function changeOrder (obj, id)
        {
            var url = "{{url('admin/sell/changeorder')}}";
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
	</script>

@endsection
