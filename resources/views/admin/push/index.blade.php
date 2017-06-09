@extends('layouts.admin')
@section('content')
	<div class="main">
		<div class="title_big bline pb10">推送通知</div>
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
		<form action="{{url('admin/push')}}" method="post">
			{{csrf_field()}}
			<div class="inner cf">
				<div class="form_row">
					<label class="only">推送标题</label>
					<input type="text" class="w-300" name="push_title" />
					<label class="info">不超过10个汉字</label>
				</div>
				<div class="form_row mt15">
					<label class="only">推送内容</label>
					<textarea wrap="hard" placeholder="不超过25个汉字" name="push_content"></textarea>
				</div>
				<div class="form_row mt15">
					<label class="only">目标</label>
					<input type="radio" name="push_model" value="1" checked>iOS用户
					<input type="radio" name="push_model" value="0">Android用户
					{{--<a class="btn choose">iOS用户</a><a class="btn ml5">Android用户</a>--}}
				</div>
				<div class="form_row mt25">
					<input type="submit" value="推送" class="add_btn" />
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
