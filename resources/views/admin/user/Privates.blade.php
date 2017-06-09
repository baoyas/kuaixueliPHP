@extends('layouts.admin')
@section('content')
	<div class="main">
		<div class="title_big bline pb10">与 “ {{$user->nickname}} ” 私聊</div>
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
		<form action="{{url('admin/user/Private')}}" method="post" enctype="multipart/form-data" >
			{{csrf_field()}}
			<div class="inner cf">
				<div class="form_row">
					<label class="only">图片</label>
					<div class="add_info2 relative">
						<input type="file" name="thumb" />
						<div></div>
					</div>
				</div>
				<div class="form_row mt15">
					<label class="only">内容</label>
					<textarea wrap="hard" placeholder="发送内容" name="content"></textarea>
				</div>
				<div class="form_row mt25">
					<input type="hidden" name="uid" value="{{$uid}}">
					<input type="submit" value="发送" class="add_btn" />
				</div>
			</div>
		</form>
	</div>
</div>
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
	</script>
@endsection
