@extends('layouts.admin')
@section('content')
	<div class="main">
		<div class="title_big bline pb10"><span></span>城市添加</div>
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
		<form action="{{url('admin/cityList')}}" method="post">
			{{csrf_field()}}
			<div class="inner mt15">
				<div class="form_row">
					<label class="first">城市编号</label>
					<input type="text" class="w-300" name="city_code" />
					<label class="info">列如 010</label>
				</div>
				<div class="form_row">
					<label class="first">城市名称</label>
					<input type="text" class="w-300" name="city_name" />
					<label class="info">列如 北京</label>
				</div>
				<div class="form_row">
					<label class="first">城市首字母</label>
					<input type="text" class="w-300" name="letter" />
					<label class="info">列如 北京 B</label>
				</div>
				<div class="form_row mt15">
					<label class="first">是否热门</label>
					<input type="radio" name="is_hot" value="1" >是
					<input type="radio" name="is_hot" value="0" checked>否
				</div>
				<div class="form_row mt15">
					<label class="first">是否开启</label>
					<input type="radio" name="power" value="1" checked>开启
					<input type="radio" name="power" value="0">关闭
				</div>
				<div class="form_row mt15">
					<input type="submit" value="添加" class="ml120 add_btn" />
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
