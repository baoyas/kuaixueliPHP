@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="/css/user.css" />
<div class="userCont">
    <!-- left begin-->
    <div class="uLe">
        <ul class="uList">
            <li class="tit">
            	<a href="{{ url('order/list') }}">全部订单({{ empty(array_sum($stat)) ? 0 : array_sum($stat) }})</a>
        	</li>
            <li>
                <a href="{{ url('order/list?status=0') }}">待支付({{ empty($stat[0]) ? 0 : $stat[0] }})</a>
            </li>
            <li>
                <a href="{{ url('order/list?status=1') }}">已支付({{ empty($stat[1]) ? 0 : $stat[1] }})</a>
            </li>
            <li>
                <a href="{{ url('order/list?status=2') }}">已关闭({{ empty($stat[2]) ? 0 : $stat[2] }})</a>
            </li>
        </ul>
    </div>

    <!-- left end-->
    <!-- right begin-->
    <div class="uRi">
        <div class="uTop">
            <span></span>
            <h1>订单管理</h1>
        </div>
        <div class="uCont">
            <div class="tabWrap">
                <div class="tabCont">
                    <table class="orderManage">
                        <thead>
                        <tr>
                            <td width="12%">订单编号</td>
                            <td width="16%">选择院校</td>
                            <td width="16%">选择地区</td>
                            <td width="12%">支付信息</td>
                            <td width="12%">下单时间</td>
                            <td width="10%">当前状态</td>
                            <td width="10%">操作</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($eduOrder as $order)
                        <tr data-id="tr{{$order->id}}">
                            <td>{{ $order->order_no }}</td>
                            <td>
                                @foreach($order->products as $key=>$prod)
                                    {{ $prod->school->name }}
                                    @if($key<count($order->products)-1)
                                    <hr style="border: 1px solid #fff;"/>
                                    @endif
                                @endforeach
                        	</td>
                            <td>
                                @foreach($order->products as $key=>$prod)
                                    地区：{{ $prod->school->province->name }}
                                    @if($key<count($order->products)-1)
                                        <hr style="border: 1px solid #fff;"/>
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <div>优惠信息：-0元</div>
                                <div>支付金额：{{ $order->fee}}元</div>
                            </td>
                            <td>{{ $order->created_at }}</td>
                            <td>
                                @if($order->status==0)
                            		【 待付款 】
                                @elseif($order->status==1)
                                	【 已支付 】
                                @elseif($order->status==2)
                                	【 已关闭 】
                                @endif
                            </td>
                            <td class="oM-cz">
                                @if($order->status===0)
                                    <!--待付款-->
                                    <a href="{{ url('order/pay?order_id='.$order->id) }}" class="toPay">去付款</a><br>
                                    <span class="fc-curp relative cancelOrder">关闭订单
                                        <div class="popShadow newWL" style="display: none;">
                                            <div>你确定要关闭订单吗？</div>
                                            <div class="mt10">
                                                <a class="colorBg1 mr10 popBtn" onclick="cancelOrders('{{ $order->id }}');">确定</a>
                                                <a href="javascript:void(0)" class="colorBg2 cancelBtn popBtn">取消</a>
                                            </div>
                                            <i class="arrow" style="top:-7px;right:26px;"></i>
                                        </div>
                                    </span>
                                @elseif($order->status===1)
                                    <a href="javascript:void(0);" onclick="detailOrders({{ join(',', $order->products->pluck('level_1_id','id')->toArray()) }});" class="toPay">详情</a><br/><a href="javascript:void(0);" onclick="deleteOrders('{{ $order->id }}');">删除订单</a>
                                @elseif($order->status===2)
                                    <a onclick="uncancelOrders('{{ $order->id }}');" class="toPay">恢复</a><br/><a href="javascript:void(0);" onclick="deleteOrders('{{ $order->id }}');">删除订单</a>
                                @endif
                                <!--已付款-->
                                <!--交易成功-->
                                <!--取消订单-->
                                <!--<a href="#" class="toPay">恢复</a><br>	-->
                            </td>
                        </tr>
                        @endforeach
                        @if(count($eduOrder)==0)
                        <tr>
                            <td colspan="7">暂无数据</td>
                        </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(".popShadow,.cancelOrder").hover(function(){
        $(this).closest(".oM-cz").find(".popShadow").show();
    },function(){
        $(this).closest(".oM-cz").find(".popShadow").hide();
    });
    $(".cancelBtn").click(function(event){
        $('.popShadow').hide();
        event.stopPropagation();
    });
    function cancelOrders(order_id) {
        $.ajax({
            method: 'post',
            url: '/order/cancel',
            data: {
                _token: '{{ csrf_token() }}',
                order_id: order_id,
                _method: 'put'
            },
            dataType:'json',
            success: function (data) {
                if(data.code != 0) {
                	alert("操作失败!", data.msg, function () {}, {type: 'error', confirmButtonText: '确定'});
                } else {
            	 	alert("操作成功!", "您已成功关闭订单", function () {
	            	 		$('[data-id="tr'+order_id+'"]').remove();
	                    	location.reload();
	            	 	}, {type: 'success', confirmButtonText: '确定'});       
                }
            }
        });
    }

    function uncancelOrders(order_id) {
        $.ajax({
            method: 'post',
            url: '/order/uncancel',
            data: {
                _token: '{{ csrf_token() }}',
                order_id: order_id,
                _method: 'put'
            },
            dataType:'json',
            success: function (data) {
                if(data.code != 0) {
                    alert("操作失败!", data.msg, function () {}, {type: 'error', confirmButtonText: '确定'});
                } else {
                	alert("操作成功!", "订单恢复成功", function () {
                		location.reload();
                	}, {type: 'success', confirmButtonText: '确定'});
                }
            }
        });
    }

    function deleteOrders(order_id) {
    	 confirm("Are you sure?", "You will not be able to recover this imaginary file!", function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
			            method: 'post',
			            url: '/order/delete',
			            data: {
			                _token: '{{ csrf_token() }}',
			                order_id: order_id,
			                _method: 'put'
			            },
			            dataType:'json',
			            success: function (data) {
			                if(data.code != 0) {
			                    alert("操作失败!", data.msg, function () {}, {type: 'error', confirmButtonText: '确定'});
			                } else {
			                 	alert("操作成功!", "订单已成功删除", function () {
			                 		 $('[data-id="tr'+order_id+'"]').remove();
			                    location.reload();
			                 	}, {type: 'success', confirmButtonText: '确定'});   
			                }
			            }
			        });
                } else {
                    //after click the cancel
                }
            }, {confirmButtonText: '确定', cancelButtonText: '取消', width: 400});
        
    }

    function detailOrders() {
        var url = '{{ url('/education/level?level_id=') }}';
        for(var k in arguments){
            setTimeout((function(url, k, arguments){
                window.open(url+arguments[k], "win"+arguments[k]);
            }).bind(this, url, k, arguments), 1000);
        }
    }
</script>
@endsection
