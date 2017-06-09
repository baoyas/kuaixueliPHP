@extends('layouts.admin')
@section('content')
	<div class="main">
		<div class="title_big bline pb10">系统配置</div>
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
        <form action="{{url('admin/config/' . $field->conf_id)}}" method="post">
            <input type="hidden" name="_method" value="put">
            {{csrf_field()}}
            <div class="inner mt15 cf">
                <div class="left ml30">
                    <div class="form_row mt5">
                        <label class="only">标题</label>
                        <input type="text" class="w-120" name="conf_title" value="{{$field->conf_title}}" />
                    </div>
                    <div class="form_row mt5">
                        <label class="only">名称</label>
                        <input type="text" class="w-160" name="conf_name" value="{{$field->conf_name}}" />
                    </div>
                    <div class="form_row">
                        <label class="only">类型</label>
                        <input type="radio"  value="input" id="" @if($field->field_type == 'input') checked @endif  checked onclick="showtr()" name="field_type"> input　
                        <input type="radio"  value="textarea" id="" @if($field->field_type == 'textarea') checked @endif onclick="showtr()" name="field_type"> textarea　
                        <input type="radio"  value="checkbox" id="" @if($field->field_type == 'checkbox') checked @endif onclick="showtr()" name="field_type"> checkbox
                    </div>
                    <div class="form_row mt5" id="field_value">
                        <label class="only">类型值</label>
                        <input type="text" class="w-200" name="field_value" value="{{$field->field_value}}" />
                        <label class="info">类型值只有在checkbox的情况下才需要配置，格式1|开启,0|关闭</label>
                    </div>
                    <div class="form_row mt5">
                        <label class="only">排序</label>
                        <input type="text" class="w-200" name="conf_order" value="{{$field->conf_order}}" />
                    </div>
                    <div class="form_row mt5">
                        <label class="only">说明</label>
                        <textarea name="conf_tips" id="" cols="10" placeholder="配置项描述..." rows="5">{{$field->conf_tips}}</textarea>
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
        showtr();
        function showtr ()
        {
            var type = $('input[name=field_type]:checked').val();
            if (type == 'checkbox')
            {
                $('#field_value').show();
            }
            else
            {
                $('#field_value').hide();
            }
        }
    </script>
@endsection
