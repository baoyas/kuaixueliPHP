@extends('layouts.admin')
@section('content')
		<div class="main">
			<ul class="data cf" style="padding-top: 130px">
				<li><a>{{$user_count}}</a>用户</li>
				<li><a class="rmb"><span>{{$sell_count}}</span></a>出售/购买</li>
				<li><a>{{$cate_sell}}</a>分类数量</li>
				<li><a>{{$group_count}}</a>群组数量</li>
			</ul>
			{{--<div class="title_big"><span><a href="{{url('admin/attestation')}}">更多</a></span>最新商户认证申请</div>--}}
			{{--<table class="mt15">--}}
				{{--<tr class="head">--}}
					{{--<td>用户</td>--}}
					{{--<td>申请时间</td>--}}
					{{--<td>状态</td>--}}
					{{--<td class="w-60">操作</td>--}}
				{{--</tr>--}}
				{{--@if (count($attArr) == 0)--}}
					{{--<tr class="order">--}}
						{{--<td colspan="5" style="text-align: center;color: red">暂时还没有申请</td>--}}
					{{--</tr>--}}
				{{--@else--}}
					{{--@foreach($attArr as $k=>$v)--}}
						{{--<tr class="order">--}}
							{{--<td><a href="{{url('admin/user/'.$v->uid)}}">{{$v->user_nickname}}</a></td>--}}
							{{--<td class="time">{{date('Y-m-d H:i:s', $v->ide_create_at)}}</td>--}}
							{{--<td><span class="stat_0">待审核</span></td>--}}
							{{--<td><a href="{{url('admin/attestation/'.$v->id)}}">查看</a></td>--}}
						{{--</tr>--}}
					{{--@endforeach--}}
				{{--@endif--}}
			{{--</table>--}}
			{{--<div class="title_big mt30"><span><a href="{{url('admin/withdralas/index')}}">更多</a></span>最新提现申请</div>--}}
			{{--<table class="mt15">--}}
				{{--<tr class="head">--}}
					{{--<td>用户</td>--}}
					{{--<td>申请时间</td>--}}
					{{--<td>提现金额</td>--}}
					{{--<td>申请状态</td>--}}
					{{--<td class="w-60">操作</td>--}}
				{{--</tr>--}}
				{{--@if(count($withdralasArr) == 0)--}}
					{{--<tr class="order">--}}
						{{--<td colspan="5" style="text-align: center;color: red">没有提现申请</td>--}}
					{{--</tr>--}}
				{{--@else--}}
					{{--@foreach($withdralasArr as $k=>$v)--}}
						{{--<tr class="order">--}}
							{{--<td><a href="{{url('admin/user/'.$v->wd_uid)}}">{{$v->user_nickname}}</a></td>--}}
							{{--<td class="time">{{date('Y-m-d H:i:s', $v->wd_create_at)}}</td>--}}
							{{--<td>￥{{$v->wd_money}}</td>--}}
							{{--<td><span class="stat_0">待审核</span></td>--}}
							{{--<td><a href="{{$v->id}}">查看</a></td>--}}
						{{--</tr>--}}
					{{--@endforeach--}}
				{{--@endif--}}
			{{--</table>--}}
		</div>
	</div>
@endsection
