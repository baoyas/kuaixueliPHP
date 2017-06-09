@extends('layouts.admin')
@section('content')
	<div class="main">
		<div class="title_big bline pb10">版本记录</div>
        <div class="mt15 cf"><a href="{{url('admin/version/create')}}" class="btn_add right ml50">记录版本</a></div>
		<table class="mt15">
			<tr class="head">
			  <td class="w-60">版本号</td>
			  <td class="w-60">终端</td>
			  <td>版本记录</td>
			  <td class="w-120">时间</td>
			</tr>
			@foreach($data as $k=>$v)
				<tr>
				  <td>{{$v->ver_number}}</td>
				  <td>
					  @if($v->ver_terminal == 1)
						  ios
					  @else
						  android
					  @endif
				  </td>
				  <td>{!! App\Helpers\Helpers::replace_str($v->ver_content) !!}</td>
				  <td>{{date('Y-m-d H:i:s', $v->ver_create_at)}}</td>
				</tr>
			@endforeach
  		</table>
		{{$data->links()}}
	</div>
</div>
@endsection
