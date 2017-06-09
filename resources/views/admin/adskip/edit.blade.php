@extends('layouts.admin')
@section('content')
	<div class="main">
		<div class="title_big bline pb10"><span></span>跳转方式编辑</div>
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
		<form action="{{url('admin/adskip/' . $file->id )}}" method="post" enctype="multipart/form-data">
			<input type="hidden" name="_method" value="put">
			{{csrf_field()}}
			<div class="inner mt15">
				<div class="form_row">
					<label class="first">位置名称</label>
					<input type="text" class="w-300" name="ad_skip_name" value="{{$file->ad_skip_name}}" />
					<label class="info">*</label>
				</div>
				<div class="form_row mt15">
					<label class="first">跳转方式</label>
					<input type="text" class="w-300" name="ad_skip_describe"  value="{{$file->ad_skip_describe}}" />
					<label class="info">*</label>
				</div>
				<div class="form_row mt15">
					<input type="submit" value="添加" class="ml120 add_btn" />
				</div>
			</div>
		</form>
	</div>
</div>
@endsection