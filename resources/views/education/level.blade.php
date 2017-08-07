@extends('layouts.app')

@section('content')
<div class="main bgWhite">
    <!-- breadcru -->
    <div class="minwidth">
        <ul class="breadcrumb">
            <li>
                <a href="../index.html">首页</a>
            </li>
            <li class="interval"></li>
            <li class="active">{{ $eLevel->name }}</li>
        </ul>
    </div>
    <!-- breadcru -->
    <!-- main start -->
    <div class="minwidth overflowvisible">
        <div class="product-intro clearfix">
            <!-- 轮播图 -->
            <div class="product-item-preview floatLeft">
                <div class="product-slider">
                    <ul id="productSlider" class="product-slider-content">
                        <li style="opacity: 1">
                            <p><img src="../Image/56ea845138329.jpg" alt="在线研究生"></p>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- 轮播图 -->
            <!-- 商品简介 -->
            <div class="product-item-inner floatLeft">
                <div class="name">
                    <h1>{{ $eLevel->name }}</h1>
                    <!--<p class="tips">股权、注册一步到位的服务</p>-->
                    <p class="tips" style="color: gray;">在职研究生是国家面向没能在高等院校或科研机构接受系统的全日制研究生教育、但具有一定学术和专门技术水平的在职人员所开展的硕士学位教育，是国家促进高层次专门人才成长的一项重要措施，使在职人员边工作、边学习提高业务水平的同时也能有机会获得硕士学位，有效满足了我国在职人员对高层次学历学位的需求。
                    </p>
                    <a href="javascript:;" class="share lickWeixin"></a>
                </div>

                <div id="productOnLine" style="display:block;">
                    <div class="type clearfix"></div>

                    <div class="region clearfix t-showarea" id="defaultRegion" style="display:block;">
                        <div class="dt">地&nbsp;&nbsp;&nbsp;&nbsp;区：</div>
                        <div class="dd">
                            <div class="region-selector">
                                <select name="" class="selArea">
                                    <option value="0">全部地区</option>
                                    <option value="1">北京</option>
                                    <option value="2">上海</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="region clearfix t-showarea" style="display:none;">
                        <div class="dt">地&nbsp;&nbsp;&nbsp;&nbsp;区：</div>
                        <div class="dd">
                            <div class="region-selector">
                                <div class="text">
                                    <span class="t-pcatitle"></span>
                                    <b></b>
                                </div>
                                <div class="content">
                                    <div class="stock-select">
                                        <div class="spacer"></div>
                                        <ul class="stock-tab clearfix" id="diqu1">
                                        </ul>
                                        <div class="stock-con">
                                        </div>
                                        <a href="javascript:void(0)" class="stock-close"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="default-btn">
                        <a href="#productDetail" class="payment">选择院校</a>
                        <a href="javascript:void(0)" class="consult">免费咨询</a>
                    </div>
                    <div class="btn" style="display: none;">
                        <a href="/provider/pf.html?pr=1&amp;ct=3302&amp;ar=39&amp;cts=3302" class="payment select-provider" target="_blank">选服务者</a>
                        <a href="/provider/pf.html?pr=1&amp;ct=3302&amp;ar=39&amp;cts=3302" class="consult select-provider" target="_blank" rel="nofollow">免费咨询</a>
                    </div>
                    <div class="guarantee">
                        <h2><b><img src="../Image/guaranteeIcon.png" height="16" width="16"></b>太平洋保险提供保障</h2>
                        <p class="txt">凡在快学历平台消费的用户，均可享受“平台责任险”项目保障，因过失、差错及疏忽行为导致用户直接经济损失，依法判决、裁定的平台责任或连带责任，将由太平洋保险在限额内进行赔付。</p>
                    </div>
                </div>

            </div>
            <!-- 商品简介 -->
        </div>
        <!-- 详情内容 -->
        <div class="wrapper">
            <div class="product-main floatRight">
                <div class="product-detail" id="productDetail">
                    <div class="product-detail-tab">
                        <div id="protabInner" class="clearfix">
                            <div class="tab-btn floatRight">
                                <div id="productOnLine2" style="display:none;">
                                    <a href="javascript:;" onclick="goSelectProvider()" class="inlineBlock butBlue">选服务者</a>
                                    <a href="javascript:;" class="inlineBlock butWhite" onclick="goSelectProvider()" rel="nofollow">免费咨询</a>
                                </div>
                                <div id="productOffLine2" style="display:none;">
                                    <a href="javascript:;" class="inlineBlock butBlue butBan">选服务者</a>
                                    <a href="javascript:;" class="inlineBlock butWhite butBan" rel="nofollow">免费咨询</a>
                                </div>
                            </div>
                            <ul class="detail-tab-trigger clearfix floatLeft">
                                <li class="firstLi active">
                                    <a href="javascript:;" data-href="#productDetail" rel="nofollow">院校选择</a>
                                </li>
                                <li>
                                    <a href="javascript:;" data-href="#proEvaluate" rel="nofollow">用户评价<em id="productEvaluationTotal">(0)</em></a>
                                </li>
                                <li>
                                    <a href="javascript:;" data-href="#proEnsure" rel="nofollow">服务保障</a>
                                </li>
                                <!--<li><a href="javascript:;" data-href="#proFAQ" rel="nofollow">热门问答</a></li>-->
                                <li>
                                    <a href="javascript:;" data-href="#proAboutus" rel="nofollow">关于快学历</a>
                                </li>
                            </ul>

                        </div>
                    </div>
                    <div class="product-detail-wrapper">
                        <!-- 服务介绍 -->
                        <div class="product-details-content bdTopNo">
                            <ul id="serviceInfo">
                                @foreach($edu as $k=>$v)
                                <li>
                                    <span class="floatRight">更多&gt;</span>
                                    <h3>{{ $v->school->name }}</h3>
                                    <p><span>学制：{{ $v->length }}年</span><span class="pdlr4"></span><span>户籍：山东</span></p>
                                    <p><span>报名费：<em class="price">{{ $v->entry_fee }}</em>元</span></p>
                                    <p><span>官方学费：<em class="price lThrow">{{ $v->market_fee }}</em>元<span class="pdlr4"></span>快学历学费：<em class="ourPrice">{{ $v->kxl_fee }}</em>元</span></p>
                                    <div class="showMoreBox">
                                        <p><span>是否全日制：{{ App\Model\Education::$fullTime[$v->fulltime_id] }}</span><span class="pdlr4"></span><span>进修方式：{{ App\Model\Education::$studyMode[$v->studymode_id] }}</span></p>
                                        <p><span>可选专业：{{ $v->major }}</span></p>
                                        <p class="require"><span>入学要求：</span>{{ $v->admission }}</p>
                                        <p><a href="{{ url('/education/info?education_id='.$v->id) }}"><span class="addToShoppingCar">我要报名</span></a></p>
                                    </div>
                                </li>
                                @endforeach
                            </ul>

                        </div>
                        <!-- 服务介绍 -->

                        <!-- 用户评价 -->
                        <div id="productEvaluatonInfo">

                            <div class="product-details-content" style="margin-bottom: 0;border-bottom: none">
                                <span class="targetPos" id="proEvaluate" style="top: -1px;"></span>
                                <h2 class="detailTit">用户评价</h2>
                                <div class="product-evaluate">



                                </div>

                            </div>
                            <div id="evaluatonList" style="border: 1px solid #eaedec;border-top: none;margin-bottom: 20px;">
                                <!-- 用户评价内容 -->

                                <ul class="ev-list clearfix aa-display">
                                    <li class="ev-le">
                                        <div>
                                            <p class="name">
                                                189****7200
                                                <em class="time">有限责任公司注册(北京-朝阳区)</em>
                                                <em class="time">2017.07.20</em>
                                            </p>
                                            <p class="info">讲解耐心，充分为客户照相，做事认真</p>
                                            <ol>
                                                <li>耐心讲解</li>
                                                <li>业务很熟练</li>
                                            </ol>
                                        </div>
                                    </li>
                                    <li class="ev-cen">
                                        <div class="start">
                                            <span class="active"></span>
                                            <span class="active"></span>
                                            <span class="active"></span>
                                            <span class="active"></span>
                                            <span class="active"></span>
                                        </div>
                                    </li>

                                </ul>
                                <ul class="ev-list clearfix aa-display">
                                    <li class="ev-le">
                                        <div>
                                            <p class="name">
                                                138****4833
                                                <em class="time">有限责任公司注册(上海-浦东新区)</em>
                                                <em class="time">2017.07.20</em>
                                            </p>
                                            <p class="info">认真负责，响应及时，态度很好，细心解答各种疑惑。非常感谢！</p>
                                            <ol>
                                                <li>做事靠谱</li>
                                                <li>诚信值得信赖</li>
                                                <li>业务很熟练</li>
                                            </ol>
                                        </div>
                                    </li>
                                    <li class="ev-cen">
                                        <div class="start">
                                            <span class="active"></span>
                                            <span class="active"></span>
                                            <span class="active"></span>
                                            <span class="active"></span>
                                            <span class="active"></span>
                                        </div>
                                    </li>

                                </ul>
                                <ul class="ev-list clearfix aa-display">
                                    <li class="ev-le">
                                        <div>
                                            <p class="name">
                                                188****9880
                                                <em class="time">有限责任公司注册(北京-朝阳区)</em>
                                                <em class="time">2017.07.20</em>
                                            </p>
                                            <p class="info">非常满意，专业耐心，还会继续合作。</p>
                                            <ol>
                                                <li>响应一流</li>
                                                <li>为人厚道</li>
                                                <li>微笑示人</li>
                                            </ol>
                                        </div>
                                    </li>
                                    <li class="ev-cen">
                                        <div class="start">
                                            <span class="active"></span>
                                            <span class="active"></span>
                                            <span class="active"></span>
                                            <span class="active"></span>
                                            <span class="active"></span>
                                        </div>
                                    </li>

                                </ul>
                                <ul class="ev-list clearfix aa-display">
                                    <li class="ev-le">
                                        <div>
                                            <p class="name">
                                                137****8123
                                                <em class="time">有限责任公司注册(广东省-深圳市-前海开发区)</em>
                                                <em class="time">2017.07.14</em>
                                            </p>
                                            <p class="info">非常专业，满足预期，专业。</p>
                                            <ol>
                                                <li>非常专业</li>
                                            </ol>
                                        </div>
                                    </li>
                                    <li class="ev-cen">
                                        <div class="start">
                                            <span class="active"></span>
                                            <span class="active"></span>
                                            <span class="active"></span>
                                            <span class="active"></span>
                                            <span class="active"></span>
                                        </div>
                                    </li>

                                </ul>
                                <ul class="ev-list clearfix aa-display">
                                    <li class="ev-le">
                                        <div>
                                            <p class="name">
                                                186****2925
                                                <em class="time">有限责任公司注册(北京-朝阳区)</em>
                                                <em class="time">2017.07.13</em>
                                            </p>
                                            <p class="info">很安心省心放心，赞一个</p>
                                            <ol>
                                                <li>耐心细致</li>
                                                <li>细心脾气好</li>
                                                <li>咨询的很棒！</li>
                                            </ol>
                                        </div>
                                    </li>
                                    <li class="ev-cen">
                                        <div class="start">
                                            <span class="active"></span>
                                            <span class="active"></span>
                                            <span class="active"></span>
                                            <span class="active"></span>
                                            <span class="active"></span>
                                        </div>
                                    </li>

                                </ul>

                                <div class="wrapPage">

                                </div>
                                <script type="text/javascript">
                                    var aDisplay = $(".aa-display");
                                    aDisplay.mouseover(function() {
                                        $(this).find(".nice").addClass("a-display-a");
                                    });
                                    aDisplay.mouseout(function() {
                                        $(this).find(".nice").removeClass("a-display-a");
                                    })
                                </script>
                            </div>
                        </div>

                        <!-- 用户评价 -->
                        <!-- 售后保障 -->
                        <div class="product-details-content">
                            <span class="targetPos" id="proEnsure" style="top: -1px;"></span>
                            <h2 class="detailTit">售后保障</h2>
                            <ul class="customer-service">
                                <li>
                                    <h2><span class="cu-icon icon1"></span>我们承诺</h2>
                                    <p class="cu-text">关于服务质量的反馈，我们会第一时间专人处理。保证及时解决您的问题。</p>
                                </li>
                                <li>
                                    <h2><span class="cu-icon icon2"></span>投诉渠道</h2>
                                    <p class="cu-text">1、拨打400-618-1106，选择投诉；</p>
                                    <p class="cu-text">2、点击在线投诉按钮，描述详情；</p>
                                    <p class="cu-text">3、通过微博、微信公众号等方式，联系我们</p>
                                </li>
                                <li>
                                    <h2><span class="cu-icon icon3"></span>处理流程</h2>
                                    <p class="cu-img"><img src="../Image/flowImg.png" alt=""></p>
                                </li>
                                <li>
                                    <h2><span class="cu-icon icon4"></span>平台保险</h2>
                                    <p class="cu-text">凡在快学历平台消费的用户，均可享受“平台责任险”项目保障，因过失、差错及疏忽行为导致用户直接经济损失，依法判决、裁定的平台责任或连带责任，将由太平洋保险在限额内进行赔付。</p>
                                </li>
                                <li class="lastLi">
                                    <h2><span class="cu-icon icon5"></span>开具发票</h2>
                                    <p class="cu-text">快学历所有产品均由服务商开具发票，请在服务完成后90天内，联系服务商开具。</p>
                                </li>
                            </ul>
                        </div>
                        <!-- 售后保障 -->

                        <!-- 关于快学历 -->
                        <div class="product-details-content">
                            <span class="targetPos" id="proAboutus" style="top: -1px;"></span>
                            <h2 class="detailTit">关于快学历</h2>
                            <div class="about-kfw">
                                <p>快学历只做最靠谱的学历教育，最快取得本科学历，只需8个月，教育部终身可查，快学历本科学历快速取证，快速拿到本科学历。</p>
                                <p>学历进修的路上，希望与您携手同行！</p>
                                <!--<div class="pikachoose">
                            <ul id="pikame" class="jcarousel-skin-pika">
                                <li><a href="javascript:;"><img src="../Image/Product_new/aboutImg1.png"/></a><span>快学历第二届创业年会，感谢有你！</span></li>
                                <li><a href="javascript:;"><img src="../Image/Product_new/aboutImg2.png"/></a><span>快学历CEO夏文奇：共享是本质</span></li>
                                <li><a href="javascript:;"><img src="../Image/Product_new/aboutImg3.png"/></a><span>快学历公开课：全程干货助您突围创业难关</span></li>
                            </ul>
                        </div>-->
                            </div>
                        </div>
                        <!-- 关于快学历 -->
                    </div>
                </div>
            </div>
            <div class="product-recommend floatLeft">
                <!-- 服务者 -->
                <div class="product-details-content provider-list"></div>
                <!-- 相关产品推荐 -->
                <div class="recommend-goods bordereaedec">
                    <h2>相关产品推荐</h2>

                </div>
            </div>
            <!-- 详情内容 -->
        </div>
        <!-- main end -->
    </div>

    <!-- 分享到微信 begin-->
    <div class="knowPoper" id="knowPoperwx">
        <span class="colse" id="colse"></span>
        <h4>打开微信“扫一扫”，打开网页后点击屏幕右上角分享按钮</h4>
        <img src="../Image/Home_index/indexweixin.jpg" alt="">
    </div>
    <!-- 分享到微信 end-->
    <script type="text/javascript" src="/js/jquery.validate.js"></script>
    <script type="text/javascript" src="/js/Home/reg.js"></script>



    <div id="pcaDiv" style="display:none;">

    </div>
    <input type="hidden" value="3302,3304,283,285,361" id="areaids">




    <!-- main end -->

    <script type="text/javascript">
        $("#serviceInfo").on("click","li span.floatRight",function(e){
            if(!$(e.target).hasClass("active")){
                $(e.target).addClass("active");
                $(e.target).html("收起&gt;");
                $(e.target).closest("li").find(".showMoreBox").show("1000");
            }else{
                $(e.target).removeClass("active");
                $(e.target).html("更多&gt;");
                $(e.target).closest("li").find(".showMoreBox").hide("1000");
            }

        });
    </script>
</div>
@endsection
