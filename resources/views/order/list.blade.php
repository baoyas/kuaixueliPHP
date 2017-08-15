@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="/css/user.css" />
<div class="userCont">
    <!-- left begin-->
    <div class="uLe">
        <ul class="uList">
            <li class="tit">全部订单</li>
            <li>
                <a href="{{ url('order/list?status=0') }}">待支付</a>
            </li>
            <li>
                <a href="{{ url('order/list?status=1') }}">已支付</a>
            </li>
            <li>
                <a href="{{ url('order/list?status=2') }}">已关闭</a>
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
                            <td>{{ $order->school->name }}</td>
                            <td>
                            	地区：{{ $order->school->province->name }}
                            </td>
                            <td>
                                <div>发票信息：--</div>
                                <div>优惠信息：-0元</div>
                                <div>支付金额：{{ $order->kxl_fee + $order->entry_fee }}元</div>
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
                                    <a class="toPay">已付款</a><br>
                                @elseif($order->status===2)
                                    <a class="toPay">已关闭</a><br>
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
                    alert(data.msg);
                } else {
                    alert('关闭成功');
                    $('[data-id="tr'+order_id+'"]').remove();
                }
            }
        });
    }
</script>
@endsection
