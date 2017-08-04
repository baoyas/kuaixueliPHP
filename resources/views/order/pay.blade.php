@extends('layouts.app')

@section('content')
<div class="main padB26 greyf5f5f5">
    <!-- 服务列表 -->
    <div class="minwidth marT40">
        <!-- 选择类型 -->
        <ul class="buySerList">
            <li>
                <div class="floatLeft listLe payListLe">
                    <div class="payokWrap">
                        <img src="../Image/payok.png" alt="付款成功">
                    </div>
                </div>
                <div class="floatLeft listRi payListRi odersIsOk">
                    <h2>订单提交成功，请尽快支付！</h2>
                    <ul class="clearfix marB20">
                        <li>
                            <span>订单号：</span>
                            <div>9170802550503</div>
                            <p></p>
                        </li>
                        <li id="infoDetails">
                            <span>详情：</span>
                            <div>
                                <p>合伙企业注册 X 1（上海-金山区）</p>
                                <p>合伙企业注册 X 1（上海-金山区）</p>
                                <p>合伙企业注册 X 1（上海-金山区）</p>
                            </div>
                        </li>
                    </ul>
                    <div class="orderPrice">应付总额：<em>4197元</em></div>
                </div>
            </li>
        </ul>
        <!-- 选择类型 -->
    </div>
    <!-- 服务列表 -->
    <div class="minwidth">
        <form id="payform" action="/pay/280695" method="post" target="_blank" onkeydown="if(event.keyCode==13){return false;}">
            <input type="hidden" name="id" value="280695">
            <!-- 服务列表 -->
            <div class="minwidth paywayCon">
                <!-- 选择类型 -->
                <ul class="buySerList buyBorder buyRadia-pay" id="buyRadia-pay" style="margin-top:0">
                    <!-- 余额支付开始 -->
                    <!-- 余额支付结束 -->
                    <!-- 支付平台 -->
                    <li class="t-payonlineway">
                        <h3 class="titleInside">微信/支付宝</h3>
                        <div class="listBorder">
                            <ol class="buyRadia overflowhidden payRadia">
                                <li class="t-bank" id="bd_alipay">
                                    <div class="radios radiosW marL40">
                                        <input type="radio" name="bankid" value="-1">
                                        <img alt="支付宝" src="../Image/icon-alipay.png">
                                        支付宝支付
                                    </div>
                                </li>
                                <li id="bd_weixin">
                                    <div class="radios radiosW marL40" id="nativeCodePayBtn">
                                        <input class="group" type="radio" name="bankid" value="-2">
                                        <img alt="微信" src="../Image/icon-weixin.png">
                                        微信支付
                                    </div>
                                </li>
                            </ol>
                        </div>
                    </li>
                    <!-- 支付平台 -->

                    <!-- 自助转账  -->
                    <li class="t-payonlineway">
                        <h3 class="titleInside">自助转账<span>因限额不能支付时，建议使用自助转账</span></h3>
                        <div>
                            <ul class="transferList clearfix">
                                <li>
                                    <table cellpadding="5" border="0" class="w440">
                                        <tbody><tr>
                                            <td><img src="../Image/icon-alipay-large.png"></td>
                                            <td class="mx-td">
                                                支付宝账号：<span class="color-red">p@kuaifawu.com</span><br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;校验全名：<span class="color-red">北京维思天下教育科技有限责任公司</span>
                                            </td>
                                        </tr>
                                        </tbody></table>
                                </li>
                                <li>
                                    <table cellpadding="5" border="0" class="w480">
                                        <tbody><tr>
                                            <td><img src="../Image/icon-cmb-large.png"></td>
                                            <td class="mx-td">
                                                &nbsp;&nbsp;&nbsp;&nbsp;开户账号：<span class="color-red">110910994010901</span><br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;开户名：<span class="color-red">北京维思天下教育科技有限责任公司</span><br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;开户行：<span class="color-red">招商银行股份有限公司北京海淀支行</span>
                                            </td>
                                        </tr>
                                        </tbody></table>
                                </li>
                            </ul>
                            <p class="transferTips">注：转账时请将订单编号备注在付款信息里；转账完成之后，请通知客服。</p>
                        </div>
                    </li>
                    <!-- 自助转账  -->
                </ul>
                <!-- 选择类型 -->
            </div>
            <div class="marT40 overflowhidden">
                <!--<input type="button" id="payButton" data-ordersno="9170802550503" value="去支付" class="btn-pay-ok floatRight" />-->
                <input type="button" id="payButton" data-ordersno="9170802550503" value="去支付" style="display:none;" class="btn-pay-ok floatRight">
            </div>
            <!-- 服务列表 -->
            <input type="hidden" name="__hash__" value="b475ac7874d304ce5bc20f1c9fd35ada_331d1e024c39901ea9c580181d9d4dd6"></form>
    </div>
</div>
@endsection
