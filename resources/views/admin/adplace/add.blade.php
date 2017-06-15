@extends('layouts.admin')
@section('content')
	<div class="main">
		<div class="title_big bline pb10"><span></span>位置添加</div>
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
		<form action="{{url('admin/adplace')}}" method="post" enctype="multipart/form-data">
			{{csrf_field()}}
			<div class="inner mt15">
				<div class="form_row">
					<label class="first">位置名称</label>
					<input type="text" class="w-300" name="ad_place_name" />
					<label class="info">不超过5个汉字</label>
				</div>
				<div class="form_row mt15">
					<input type="submit" value="添加" class="ml120 add_btn" />
				</div>
			</div>
		</form>
	</div>
</div>
@endsection