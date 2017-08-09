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
                            <img class="floatLeft marL40 imgW50" src="/upload/{{ $edu->school->logo_url }}" alt="服务图片">
                            <div class="textImgSeparation goods-text">
                                <div>
                                    <span>{{ $edu->school->name }}</span>
                                </div>
                                <div class="area">
                                    <em>地区：</em>
                                    <span>北京</span>
                                </div>
                            </div>
                        </li>
                        <li class="unit-price">
                            <span>{{ $edu->kxl_fee + $edu->entry_fee }}元</span>
                        </li>
                        <li class="shopp-num">
                            <div class="numAddSubtract overflowhidden">
                                <a class="subtract " href="javascript:;" id="productReduce_0" onclick="changeNum('product', -1, 0);">-</a>
                                <input type="text" value="1" onkeyup="numberFilter(this);" name="productnums[0]" id="productNum_0" style="width:40px;" class="t-productNum" category="product" key="0" pprice="1399" vprice="0" data-productid="165">
                                <a href="javascript:;" onclick="changeNum('product', 1, 0);">+</a>
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

                    <!-- 自定义支付 -->
                    <!-- 自定义支付 -->

                    <!-- 套餐类型 -->
                    <!-- 套餐类型 -->

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
                    <a class="butpadding21 inlineBlock floatRight butWhiteGreyborder marR20" href="index.html">继续逛逛</a>
                </div>
                <!-- 如何获取发票 -->
                <div class="invoiceWrap">
                    <div class="invoiceTip">
                        如何获取发票？
                        <div class="invoiceInner">
                            <span></span>
                            1、发票由为您提供服务的服务商所在公司开具；
                            2、服务完成后90天内，可联系服务商索取发票。
                        </div>
                    </div>
                </div>
                <!-- 如何获取发票 -->
            </div>

        </div>
        <!-- 购物车信息 -->
        <input type="hidden" name="__hash__" value="ddc94d7572c88646fbada304ba7c131b_532b506724d22ae4045f3b1f17b0226e"></form>
</div>
<!--购物车 有东西-->
@endsection
