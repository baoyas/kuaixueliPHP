@extends('layouts.admin')
@section('content')
	<div class="main">
		<div class="title_big bline pb10"><span><a href="{{url('admin/sell')}}">更多</a></span>编辑信息</div>
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
		<form action="{{url('admin/sell/'.$data->id)}}" method="post" enctype="multipart/form-data">
			<input type="hidden" name="_method" value="put">
			{{csrf_field()}}
			<div class="inner mt15">
				<div class="form_row">
					<label class="first">类别</label>
					<input type="radio" name="is_sell" onclick="showtr()" value="1" @if($data->is_sell == 1) checked @else @endif>我要买
					<input type="radio" name="is_sell" onclick="showtr()" value="2" @if($data->is_sell == 2) checked @else @endif>我要卖
				</div>
				<div class="form_row mt15">
					<label class="first">标题</label>
					<input type="text" class="w-300" name="sell_title" value="{{$data->sell_title}}" />
				</div>
				<div class="form_row mt15">
					<label class="first">分类</label>
					<select name="cate_id" id="">
						<option value="">--请选择分类--</option>
						@foreach($cate as $v)
							<option value="{{$v->id}}" @if($data->cate_id == $v->id)selected @else @endif @if($v->over == 0) disabled="disabled" style="color:red" @else @endif>{{$v->_cate_name}}</option>
						@endforeach
					</select>
				</div>
				<div class="form_row mt15">
					<label class="first">描述</label>
					<textarea wrap="hard" class="w-300" name="sell_describe" placeholder="描述">{!! \App\Helpers\Helpers::textarea_replace_str($data->sell_describe) !!}</textarea>
				</div>
				<div class="form_row mt15">
					<label class="first">价格</label>
					<input type="text" class="w-300" name="sell_price" value="{{$data->sell_price}}" />
				</div>
				<div class="form_row mt15 max" style="display:none">
					<label class="first">最大价格</label>
					<input type="text" class="w-300" name="sell_price_max" value="{{$data->sell_price_max}}" />
				</div>
				<div class="form_row mt15">
					<label class="first">位置</label>
					<input type="text" class="w-300" name="sell_area" value="{{$data->sell_area}}" />
				</div>
				<div class="form_row">
					<label class="first">推荐</label>
					<input type="radio" name="recommend" value="1" @if($data->recommend == 1) checked @else @endif>是
					<input type="radio" name="recommend" value="0" @if($data->recommend == 0) checked @else @endif>否
				</div>
				<div class="form_row auth" style="display:none">
					<label class="first">权限</label>
					<input type="radio" name="sell_auth" value="1" @if($data->sell_auth == 1) checked @else @endif >所有人可见
					<input type="radio" name="sell_auth" value="2" @if($data->sell_auth == 2) checked @else @endif >仅限好友可见
					<input type="radio" name="sell_auth" value="3" @if($data->sell_auth == 3) checked @else @endif >仅限自己可见
				</div>
				<div class="form_row">
					<label class="first">用户</label>
					<input type="text" class="w-300" name="search_name" placeholder="请填写用户手机号，进行查询" value="{{$data->nickname}}" /> [<a href="javascript:;" onclick="searchs()">查询</a>]
					<input type="hidden" name="sell_uid" value="{{$data->sell_uid}}">
				</div>
				<div class="form_row mt15">
					<label class="first">排序</label>
					<input type="text" class="w-60" name="sell_order" value="{{$data->sell_order}}" />
				</div>
				@if ($data->sell_pic != null)
					@foreach($data->sell_pic as $k=>$v)
						<div class="form_row mt15 duo">
							<label class="first left">图片</label>
							<div class="add_info1 left relative">
								<input type="file" name="sell_pic[]" />
								<div><img src="{{config('web.QINIU_URL')}}/{{$v}}" alt=""></div>
							</div>
							<label class="info">尺寸：180*180px</label>
							@if($k == 0)
								<a href="javascript:;" id="add_img">[添加图片集]</a> 　
							@else
								<span class="del_obj"><a href='javascript:;' onclick="dels(this,'{{$data->id}}', '{{$v}}')">删除</a></span>
							@endif
							注:最多九张图片
						</div>
					@endforeach
				@else
					<video src="{{$data->sell_video}}"></video>
				@endif
				<input type="hidden" id="add">
				<div class="form_row mt15">
					<input type="submit" value="更新" class="ml120 add_btn" />
				</div>
			</div>
		</form>
	</div>
</div>
<script>
if (typeof FileReader === 'undefined') {
	$(".add_info1 div").html("抱歉，你的浏览器不支持FileReader,但不影响上传图像...");
	input.setAttribute('disabled', 'disabled');
} else {
	$(".add_info1 input").change(function() {
		pic = $(this);
		var file = this.files[0];
		if (!/image\/\w+/.test(file.type)) {
			alert("请确保文件为图像类型");
			return false;
		}
		var reader = new FileReader();
		reader.readAsDataURL(file);
		reader.onload = function(e) {
			pic.next().html('<img src="' + this.result + '" />');
		}
	})
}
</script>
<script type="text/javascript">
	//添加图片集

	$(function(){
		$('#add_img').click(function(){
			if ($('.duo').length >= 9)
			{
				alert('服务图片最多为九张！');
				return;
			}
			var obj=$(this).parents('.form_row').clone(true).insertAfter('#add');
			obj.find('.del_obj').html("<a href='javascript:;' onclick='del(this)'>删除</a>");
			obj.find('#add_img').remove();

		})
	});
	
	function dels (obj, id, key) {
		layer.confirm('删除将会删除数据库中的图片，还要进行吗？',function(index){
			//此处请求后台程序，下方是成功后的前台处理……
			var url = "{{url('admin/sell/delPic')}}";
			$.post(url, {'_token':'{{csrf_token()}}', id:id, key:key}, function(data){
				if (data.status == 0)
				{
					layer.msg(data.msg,{icon:1,time:1000});
					del(obj);
//					setTimeout(function(){
//						location.href = location.href;
//					},2000);
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

	function del (obj) {
		$(obj).parents().parents('.form_row').remove();
	}
	
	function searchs () {
		var search_name = $('input[name=search_name]').val();
		var url = "{{url('admin/sell/search')}}";
		$.post(url, {search_name:search_name, '_token':'{{csrf_token()}}'}, function (data) {
			if (data.status == 0)
			{
				$('input[name=search_name]').val(data.nickname);
				$('input[name=sell_uid]').val(data.uid);
				layer.msg(data.msg, {icon:6});
			}
			else
			{
				layer.msg(data.msg, {icon:5});
			}
		}, 'json');
	}

</script>
<script>
	showtr();
	function showtr ()
	{
		var type = $('input[name=is_sell]:checked').val();
		if (type == '2')
		{
			$('.auth').show();
			$('.max').hide();
		}
		else
		{
			$('.auth').hide();
			$('.max').show();
		}
	}
</script>
@endsection
