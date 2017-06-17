@extends('layouts.admin')
@section('content')
	<div class="main">
		<div class="title_big bline pb10"><span></span>添加内容</div>
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
		@include('UEditor::head')
		<!-- 加载编辑器的容器 -->
		<script id="container" name="content" type="text/plain">
		</script>
		<!-- 实例化编辑器 -->
		<script type="text/javascript">
	        var ue = UE.getEditor('container');
		</script>
		<form id="editorForm" action="{{url('admin/ceditor')}}" method="post" enctype="multipart/form-data">
			{{csrf_field()}}
			<input type="hidden" name="richtext" value="">
		</form>
		<input type="submit" value="添加" class="ml120 add_btn" onclick="saveRichtext()"/>
	</div>
</div>

<script>
function saveRichtext() {
	$('#editorForm').find('[name=richtext]').val(UE.getEditor('container').getAllHtml());
	$('#editorForm').submit();
}
</script>
@endsection