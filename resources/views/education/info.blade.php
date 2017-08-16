@extends('layouts.app')

@section('content')
<!--购物车 有东西-->
<div class="minwidth">
    <form id="cartForm" noenter="1">
        <!-- 服务列表 -->
        <div class="minwidth marT40">

            <!-- 当一个列表中有多个选项时，要加class:buyBorder并且第一个li要加class:noBuyBorder -->
            <!-- 优惠套餐 -->
            <ul class="buySerList bordere0e0e0">
                <li class="shopp-list">
                    <!-- 表头 -->
                    <div class="shoppTitle">
                        <ol class="shoppFirst">
                            <li class="goods">商品信息</li>
                            <li class="unit-price">单价</li>
                            <li class="shopp-num">数量</li>
                            <li class="shopp-subtotal">小计</li>
                            <li class="operation">操作</li>
                        </ol>
                    </div>
                    <!-- 表头 -->

                    <!-- 普通类型 -->

                    <ol>
                        <li class="goods">
                            <img class="floatLeft marL40 imgW50" src="/upload/{{ $edu->school->logo_url }}" alt="学校logo">
                            <div class="textImgSeparation goods-text">
                                <div>
                                    <span title="{{ $edu->school->name }}">{{ $edu->school->name }}</span>
                                </div>
                                <div class="area">
                                    <em>地区：</em>
                                    <span>{{ $edu->school->province->name }}</span>
                                </div>
                            </div>
                        </li>
                        <li class="unit-price">
                            <span>{{ $edu->kxl_fee + $edu->entry_fee }}元</span>
                        </li>
                        <li class="shopp-num">
                            <div class="numAddSubtract overflowhidden">
                                <a class="subtract notChoose" href="javascript:;" id="productReduce_0" onclick="changeNum('product', -1, 0);">-</a>
                                <input type="text" value="1" name="productnums[0]" id="productNum_0" style="width:40px;" class="t-productNum">
                                <a class="notChoose" href="javascript:;" onclick="changeNum('product', 1, 0);">+</a>
                            </div>
                        </li>
                        <li class="shopp-subtotal">
                            <span id="productTotalPrice_0">{{ $edu->kxl_fee + $edu->entry_fee }}元</span>
                        </li>
                        <li class="operation">
                            <a href="javascript:void(0)">删除</a>
                            <!--<span>x</span>-->
                        </li>
                    </ol>
                    <!-- 普通类型 -->
                </li>
            </ul>
            <!-- 优惠套餐 -->
        </div>
        <!-- 服务列表 -->
        <!-- 购物车信息 -->
        <div class="minwidth">
            <!--优惠券信息-->

            <!--优惠券信息-->
            <div class="floatRight marT30 checkoutWrap marB40" style="position:relative;">
                <ul class="checkout floatRight">
                    <li>
                        <span class="checkout-le">金额合计：</span>
                        <span class="checkout-ri" id="totalPrice">{{ $edu->kxl_fee + $edu->entry_fee }}元</span>
                    </li>
                    <li>
                        <span class="checkout-le">代金券抵扣：</span>
                        <span class="checkout-ri" id="couponPrice">0元</span>
                    </li>
                    <li>
                        <span class="checkout-le">应付总额：</span>
                        <span class="checkout-ri"><em id="totalPayPrice">{{ $edu->kxl_fee + $edu->entry_fee }}元</em></span>
                    </li>
                </ul>
                <div class="marT30 floatRight">
                    <a class="butpadding64 inlineBlock floatRight butRed" href="{{ url('/order/pay?education_id='.$edu->id) }}" id="orderButton" data-urlkey="">去结账</a>
                    <a class="butpadding21 inlineBlock floatRight butWhiteGreyborder marR20" href="{{ url('/') }}">继续逛逛</a>
                </div>
                <!-- 如何获取发票 -->
                <div class="invoiceWrap">
                    <div class="invoiceTip">
                		如何获取发票？
                        <div class="invoiceInner">
                            <span></span>
                            1、发票由北京维思天下教育科技有限公司统一提供发票；
                            2、请在汇款或在线付款后联系在线客服确定邮寄地址。
                        </div>
                    </div>
                </div>
                <!-- 如何获取发票 -->
            </div>

        </div>
        <!-- 购物车信息 -->
        </form>
</div>
<script type="text/javascript">
	$(function(){ 
		//改变数量
		function changeNum(type, num, pId){
			return false;
			var domId = "#" + type + "Num_" + pId;
			var pNum = parseInt($(domId).val());
			pNum += num;
			if (pNum <= 0)
			{
				return false;
			}
			$(domId).val(pNum);
			if (pNum == 1)
			{
				$("#" + type + "Reduce_" + pId).addClass('notChoose');
			}
			else
			{
				$("#" + type + "Reduce_" + pId).removeClass('notChoose');
			}
		}

		$(".invoiceTip").hover(
			function(){
				$(this).find(".invoiceInner").show();
			},function(){
				$(this).find(".invoiceInner").hide();
			});
	});
</script>	
<!--购物车 有东西-->
@endsection
