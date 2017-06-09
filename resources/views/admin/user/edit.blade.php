@extends('layouts.admin')
@section('content')
	<div class="main">
		<div class="title_big bline pb10">用户详情 - 编辑</div>
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
        <form action="{{url('admin/user/'.$data->id)}}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="put">
            {{csrf_field()}}
            <div class="inner mt15 cf">
                <div class="w-200 left">
                    <div class="add_info1 relative">
                        <input type="file" name="user_face" />
                        <div><img src="{{config('web.QINIU_URL')}}/{{$data->user_face}}" alt=""></div>
                    </div>
                </div>
                <div class="left ml30">
                    <div class="form_row">
                        <label class="only">身份</label>
                        @if($data->user_identity == 0)
                            <span class="btn choose">普通用户</span>
                        @else
                            <span class="btn ml5">商业用户</span>
                        @endif

                    </div>
                    <div class="form_row mt5">
                        <label class="only">手机号</label>
                        <input type="text" class="w-120" name="user_phone" value="{{$data->user_phone}}" />
                    </div>
                    <div class="form_row mt5">
                        <label class="only">昵称</label>
                        <input type="text" class="w-200" name="user_nickname" value="{{$data->user_nickname}}" />
                    </div>
                    <div class="form_row mt5">
                        <label class="only">余额变动（当前余额: ￥{{$data->user_balance}}）</label>
                        <select class="mr5" name="user_balance_type">
                            <option value="null">变动方式</option>
                            <option value="add">增加</option>
                            <option value="cut">减少</option>
                        </select>
                        <input type="text" class="w-120" name="user_balance" />
                        <label class="info">如无变动，请勿修改</label>
                    </div>
                    <div class="form_row mt15">
                        <input type="submit" value="添加" class="add_btn" />
                    </div>
                </div>
            </div>
        </form>
	</div>
</div>
<script>
if (typeof FileReader === 'undefined') {
	$(".add_info1 div").html("抱歉，你的浏览器不支持FileReader,但不影响上传图像...");
	input.setAttribute('disabled', 'disabled');
} else {
	$(".add_info1 input").change(function() {

		var file = this.files[0];
		if (!/image\/\w+/.test(file.type)) {
			alert("请确保文件为图像类型");
			return false;
		}
		var reader = new FileReader();
		reader.readAsDataURL(file);
		reader.onload = function(e) {
			$(".add_info1 div").html('<img src="' + this.result + '" />');
		}
	})
}
</script>
@endsection