@extends('layouts.admin')
@section('content')
	<div class="main">
		<div class="title_big bline pb10">修改密码</div>
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
        <form action="{{url('admin/user/ResetPass')}}" method="post">
            {{csrf_field()}}
            <div class="inner mt15 cf">
                <div class="left ml30">
                    <div class="form_row mt5">
                        <label class="only">帐号</label>
                        <label class="info">{{$user->nickname}}</label>
                    </div>
                    <div class="form_row mt5">
                        <label class="only">新密码</label>
                        <input type="password" class="w-160" name="password" />
                    </div>
                    <div class="form_row mt5">
                        <label class="only">确认密码</label>
                        <input type="password" class="w-160" name="password_confirmation" />
                    </div>
                    <div class="form_row mt15">
                        <input type="hidden" name="uid" value="{{$user->id}}">
                        <input type="submit" value="确定" class="add_btn" />
                    </div>
                </div>
            </div>
        </form>
	</div>
</div>
@endsection
