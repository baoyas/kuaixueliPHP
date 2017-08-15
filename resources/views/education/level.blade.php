@extends('layouts.app')

@section('content')
<div class="main bgWhite">
    <!-- breadcru -->
    <div class="minwidth">
        <ul class="breadcrumb">
            <li>
                <a href="/">首页</a>
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
                    <p class="tips" style="color: gray;">{!! $eLevel->desc !!}</p>
                    <a href="javascript:;" class="share lickWeixin"></a>
                </div>

                <div id="productOnLine" style="display:block;">
                    <div class="type clearfix"></div>

                    <div class="region clearfix t-showarea" id="defaultRegion" style="display:block;">
                        <div class="dt">地&nbsp;&nbsp;&nbsp;&nbsp;区：</div>
                        <div class="dd">
                            <div class="region-selector">
                                <select name="" class="selArea" id="selProvince" value="{{ $province_id }}">
                                    <option value="0">全部地区</option>
                                    @foreach($provinces as $prov_id=>$province_name)
                                    <option value="{{ $prov_id }}" <?php echo $province_id==$prov_id ? 'selected' : '';?>>{{ $province_name }}</option>
                                    @endforeach
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
                        <a href="http://wpa.qq.com/msgrd?v=3&uin=99618132&site=qq&menu=yes" class="consult">免费咨询</a>
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
									<p><span>学&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;制：{{ $v->length }}年</span><span class="list-2">报名费用：<em class="price">{{ $v->entry_fee }}</em>元</span></p>						
									<p><span>官方学费：<em class="price lThrow">{{ $v->market_fee }}</em>元</span><span class="list-2">快学历学费：<em class="ourPrice">{{ $v->kxl_fee }}</em>元</span></p>		
									<p><span>是否全日制：{{ App\Model\Education::$fullTime[$v->fulltime_id] }}</span></span><span class="list-2">进修类别：{{ App\Model\Education::$studyMode[$v->studymode_id] }}</span></p>												
									<p><span>可选专业：{{ $v->major }}</span></p>
									<div class="showMoreBox">
										<p><span>限制报名户籍：{{ $v->province_desc }}</span></p>
										<p><span>课程顾问：
                                                <?php $have_qq = false; ?>
                                                @if(count($v->contacts))
                                                    @foreach($v->contacts as $c)
                                                        @if($c->atype==1)
                                                            <?php $have_qq = true; ?>
                                                            <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin={{ $c->account }}&site=qq&menu=yes"><img border="0" src="../Image/chatMe.png" alt="点击这里给我发消息" title="点击这里给我发消息"/></a>
                                                        @endif
                                                    @endforeach
                                                @endif
                                                @if($have_qq===false)
                                                    暂无
                                                @endif
                                            </span>
                                        </p>
										<p class="require"><span>报名须知：</span>{{ $v->admission }}</p>
										<p><a href="{{ url('/education/info?education_id='.$v->id) }}"><span class="addToShoppingCar">我要报名</span></a></p>	
									</div>
								</li>
                                @endforeach
                                @if(count($edu)==0)
                                <li style="letter-spacing: 2px;">
                                   		暂无相关院校，如果您有需要可以<a href="http://wpa.qq.com/msgrd?v=3&uin=99618132&site=qq&menu=yes" rel="nofollow" style="color:#127bc7;">联系客服</a>!
                                </li>
                                @endif
                            </ul>

                        </div>
                        <!-- 服务介绍 -->

                        <!-- 用户评价 -->
                        <div id="productEvaluatonInfo">

                            <div class="product-details-content" style="margin-bottom: 0;border-bottom: none">
                                <span class="targetPos" id="proEvaluate" style="top: -1px;"></span>
                                <h2 class="detailTit">用户评价</h2>
                                <div class="product-evaluate"> </div>
                            </div>
                            <div id="evaluatonList" style="border: 1px solid #eaedec;border-top: none;margin-bottom: 20px;">
                                <ul class="ev-list clearfix aa-display">
                                    <li class="ev-le" style="font-size: 14px;color:#757575;">
                                    	暂无评价信息！
                                	</li>
                                </ul>    
                                <!-- 用户评价内容 -->

                                <!--<ul class="ev-list clearfix aa-display">
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
                                </script>-->
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
									<p class="cu-text">3、通过QQ、微信公众号、官方电话等方式，联系我们！</p>
								</li>
                                <li class="lastLi">
                                    <h2><span class="cu-icon icon5"></span>开具发票</h2>
                                    <p class="cu-text">为保障考生权益，快学历所有学历进修的学费由北京维思天下教育科技有限公司统一提供发票，请在汇款或在线付款后联系在线客服确定邮寄地址。</p>
                                </li>
                            </ul>
                        </div>
                        <!-- 售后保障 -->

                        <!-- 关于快学历 -->
                        <div class="product-details-content">
                            <span class="targetPos" id="proAboutus" style="top: -1px;"></span>
                            <h2 class="detailTit">关于快学历</h2>
                            <div class="about-kxl">
                                <p>快学历只做最靠谱的学历教育，最快取得本科学历，只需8个月，教育部终身可查，快学历本科学历快速取证，快速拿到本科学历。</p>
                                <p>学历进修的路上，希望与您携手同行！</p>                     
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
                    <h2>课程顾问</h2>
					<ul id="servantList">
						@if($contacts && $contacts['account'])
							<li>
	                            <div class="clear">
	                                <div class="peopleLogo floatLeft"><a href="#"> <img src="../Image/1019.png" /> </a></div>
	                                <div class="peopleMess floatLeft">
	                                    <p>姓名：<span>{{ $contacts['realname'] }}</span></p>
	                                    <p>QQ：<span><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin={{ $contacts['account'] }}&site=qq&menu=yes"><img border="0" src="../Image/chatMe.png" alt="点击这里给我发消息" title="点击这里给我发消息"></a></span></p>
	                                </div>
	                            </div>
	                            <div  class="serviceArea">
	                                <dl><dt>业务范围：</dt><dd title="{!! $contacts['scope_desc'] !!}">{!! $contacts['scope_desc'] !!}</span></dl>
	                            </div>                         
							</li>
					  	@else
                            <li>
                               	 暂无相关信息！
                            </li>
                        @endif
					</ul>
                </div>
            </div>
            <!-- 详情内容 -->
        </div>
        <!-- main end -->
    </div>





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
        $('.detail-tab-trigger li').on('click',function(){
	        var toId = $(this).find('a').attr("data-href");
	        $(this).addClass('active').siblings().removeClass('active');
	        $.scrollTo(toId,100);
	    });
        $(document).ready(function(){
            $('#selProvince').change(function(){
                var param = location.search.indexOf('?')!=-1 ? location.search.substr(location.search.indexOf('?')+1) : "";
                param = param ? $.deserialize(param) : {};
                param.province_id = $(this).val();
                location.href = location.pathname+'?'+$.param(param);
            });
        });
    </script>
</div>
@endsection
