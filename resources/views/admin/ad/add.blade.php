@extends('layouts.admin')
@section('content')
	<div class="main cf">
		<div class="title_big bline pb10"><span></span>添加图片广告</div>
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
		<form action="{{url('admin/ad')}}" method="post" enctype="multipart/form-data" >
			{{csrf_field()}}
			<div class="left ml30 mt30">
				<div class="add_info2 relative">
					<input type="file" name="ad_object_thumb" />
					<div></div>
				</div>
				<label class="info">尺寸：720*180px，其他尺寸会导致客户端图片扭曲变形</label>
			</div>
			<div class="left ml30 mt20">
				<div class="form_row">
					<label class="only">广告名称</label>
					<input type="text" class="w-300" name="ad_object_name" />
				</div>
				<div class="form_row mt15">
					<label class="only">显示位置</label>
					@foreach($adplace as $k=>$v)
						<input type="radio" name="ad_place_id" id="" value="{{$v->id}}">{{$v->ad_place_name}}
						{{--<a class="btn @if($k==0) choose @else mt15 @endif">{{$v->ad_place_name}}</a>--}}
					@endforeach
				</div>
				<div class="form_row mt15">
					<label class="only">跳转方式</label>
					@foreach($adskip as $k=>$v)
						<input type="radio" name="ad_skip_id" id="" value="{{$v->id}}" title="描述：{{$v->ad_skip_describe}}" dd="{{$v->ad_skip_describe}}">{{$v->ad_skip_name}}
						{{--<a class="btn choose">打开网页</a><a class="btn ml5">用户主页</a><a class="btn ml5">文章详情</a>--}}
					@endforeach
				</div>
				<div class="form_row mt15">
					<label class="only">目标</label>
					<input type="text" class="w-500" name="ad_object_aim" placeholder="" />
				</div>
				<div class="form_row mt15">
					<label class="only">显示时间</label>
					<input type="text" class="w-100" id="start" placeholder="开始日期" name="ad_start_at" /><label>-</label><input type="text" id="end" name="ad_end_at" class="w-100" placeholder="结束日期" />
				</div>
				<div class="form_row">
					<label class="only">排序</label>
					<input type="text" class="w-100" name="ad_object_sort" value="100" />
				</div>
				<div class="form_row mt15">
					<input type="submit" value="添加" class="add_btn" />
				</div>
			</div>
		</form>
	</div>
</div>
	<script type="text/javascript" src="{{asset('style/admin/js/jquery.datetimepicker.js')}}"></script>
	<link rel="stylesheet" type="text/css" href="{{asset('style/admin/css/jquery.datetimepicker.css')}}"/>
<script>
if (typeof FileReader === 'undefined') {
	$(".add_info2 div").html("抱歉，你的浏览器不支持FileReader,但不影响上传图像...");
	input.setAttribute('disabled', 'disabled');
} else {
	$(".add_info2 input").change(function() {

		var file = this.files[0];
		if (!/image\/\w+/.test(file.type)) {
			alert("请确保文件为图像类型");
			return false;
		}
		var reader = new FileReader();
		reader.readAsDataURL(file);
		reader.onload = function(e) {
			$(".add_info2 div").html('<img src="' + this.result + '" />');
		}
	})
}
$(function(){
 $('#start').datetimepicker({
  format:'Y-m-d',
  onShow:function( ct ){
   this.setOptions({
    maxDate:$('#end').val()?$('#end').val():false
   })
  },
  timepicker:false
 });
 $('#end').datetimepicker({
  format:'Y-m-d',
  onShow:function( ct ){
   this.setOptions({
    minDate:$('#start').val()?$('#start').val():false
   })
  },
  timepicker:false
 });
});

	$('input[name=ad_skip_id]').click(function () {
		var skip = $(this).attr('dd') + ' 添加参数即可，用户请填写用户手机号';
		$('input[name=ad_object_aim]').attr('placeholder', skip);
	});
</script>
@endsection
