@extends('layouts.admin')
@section('content')
	<div class="main">
		<div class="title_big bline pb10"><span><a href="{{url('admin/cate')}}">更多</a></span>分类编辑</div>
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
		<form action="{{url('admin/cate')}}" method="post" enctype="multipart/form-data">
			{{csrf_field()}}
			<div class="inner mt15">
				<div class="form_row">
					<label class="first">分类名称</label>
					<input type="text" class="w-300" name="cate_name" />
					<label class="info">不超过5个汉字</label>
				</div>
				<div class="form_row mt15">
					<label class="first">分类排序</label>
					<input type="text" class="w-60" name="cate_sort" value="1" />
				</div>
				<div class="form_row mt15">
					<input type="hidden" name="pid" value="{{$pid}}">
					<input type="submit" value="添加" class="ml120 add_btn" />
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
