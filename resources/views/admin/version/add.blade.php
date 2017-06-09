@extends('layouts.admin')
@section('content')
	<div class="main">
		<div class="title_big bline pb10"><span></span>版本更新添加</div>
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
		<form action="{{url('admin/version')}}" method="post">
			{{csrf_field()}}
			<div class="inner mt15">
				<div class="form_row">
					<label class="first">版本号</label>
					<input type="text" class="w-300" name="ver_number" />
					<label class="info">列如 1.0.1</label>
				</div>
				<div class="form_row mt15">
					<label class="first">终端</label>
					<input type="radio" name="ver_terminal" value="1" checked>IOS
					<input type="radio" name="ver_terminal" value="0">ANDROID
				</div>
				<div class="form_row mt15">
					<label class="first">更新内容</label>
					{{--<input type="text" class="w-300" />--}}
					<textarea name="ver_content" class="w-300" id="" cols="30" rows="10"></textarea>
				</div>
				<div class="form_row mt15">
					<input type="submit" value="添加" class="ml120 add_btn" />
				</div>
			</div>
		</form>
	</div>
</div>
@endsection