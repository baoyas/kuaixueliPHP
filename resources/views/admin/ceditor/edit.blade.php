@extends('layouts.admin')
@section('content')
	<div class="main">
		<div class="title_big bline pb10"><span></span>编辑内容</div>
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
			{{$file->richtext}}
		</script>
		<!-- 实例化编辑器 -->
		<script type="text/javascript">
			var ue = UE.getEditor('container');
		</script>
		<input type="button" value="保存" class="ml120 add_btn" onclick="saveRichtext()">
		<form id="editorForm" action="{{url('admin/ceditor/' . $file->id )}}" method="post" enctype="multipart/form-data" style="display: none;">
			<input type="hidden" name="_method" value="put">
			<input type="hidden" name="richtext" value="">
			{{csrf_field()}}
		</form>
	</div>
</div>
<script>
function saveRichtext() {
	$('#editorForm').find('[name=richtext]').val(UE.getEditor('container').getAllHtml());
	$('#editorForm').submit();
}
</script>
@endsection