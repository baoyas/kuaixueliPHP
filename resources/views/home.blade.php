@extends('layouts.app')

@section('content')
<!-- logo -->
<div class="minwidth kfwNav headerOverflowInherit" style="z-index:100;">
    <div class="floatLeft kfwNaSub headerOverflowInherit">
        <a href="javascript:;" style="cursor: default" rel="nofollow">全部服务分类</a>
        <div class="kfwNaSubSub kfwNaSubSubIsHome">
            <div class="floatLeft headerOverflowInherit kfwISubNav">
                <ul class="iNav overflowhidden headerOverflowInherit">
                @foreach($eLevel as $key=>$val)
                    <li @if(empty($key))class="iNavFirst"@else''@endif>
                        <i></i>
                        <h4><a href="{{ url('/education/level?level_id='.$val['id']) }}">{{ $val['name'] }}</a></h4>
                    </li>
                @endforeach
                </ul>
            </div>
            <div class="floatLeft iSubNav">
                @foreach($eLevel as $k=>$v)
                <div class="kfwanimate">
                    <ul class="floatLeft iSubNavLe overflowhidden">
                        @foreach($v['children'] as $kk=>$vv)
                        <li>
                            <a href="{{ url('/education/level?level_id='.$vv['id']) }}"><h5>{{ $vv['name'] }}</h5></a>
                            <div class="kfwNavOne">
                                @foreach($vv['children'] as $kkk=>$vvv)
                                <a class="active" href="{{ url('/education/level?level_id='.$vvv['id']) }}">{{ $vvv['name'] }}</a>
                                @endforeach
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <ul class="floatLeft iSubNavRi overflowhidden">
                        <li class="iSubNavRi1">
                            <a href="/product/company.html">
                                <img src="Image/Home_index/inav-1.jpg" alt="">
                            </a>
                        </li>
                        <li>
                            <a href="/trsearch/index.html">
                                <img src="Image/Home_index/inav-2.jpg" alt="3">
                            </a>
                        </li>
                        <li>
                            <a href="/legalvip">
                                <img  class="lastMar" src="Image/Home_index/inav-3.jpg" alt="">
                            </a>
                        </li>
                    </ul>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="floatLeft kfwNavs">
        <!--<a href="/">首页</a>-->
        <a href="/product/company.html">一年专科
            <img class="hotNew" alt="" src="Image/hot.gif">
        </a>
        <a href="/product/kssb.html">国外进修
            <img class="hotNew" alt="" src="Image/hot.gif">
        </a>
        <a href="/product/htc.html" target="_blank">论文检索
            <img class="hotNew" alt="" src="Image/hot.gif">
        </a>
        <a href="/trsearch/index.html">学位英语</a>
        <a href="/moreservice">更多服务</a>
    <span class="floatRight telephone">
        400-618-1106    </span>
    </div>
</div>
<!-- logo -->


<!-- 首屏 -->
<div class="main indexFirst">
    <div class="indexFirst-in">
        <div class="lunhuan">
            <div id="lunhuanback">
                <p style=" background: url(Image/lunbotu1.png) no-repeat scroll center center; opacity: 1;filter:alpha(opacity=100);">
                    <a href="javascript:void(0);" target="_blank" rel="nofollow"></a>
                </p>
                <p style=" background: url(Image/lunbotu2.png) no-repeat scroll center center; ">
                    <a href="javascript:void(0);" target="_blank" rel="nofollow"></a>
                </p>
            </div>
            <div class="lunhuan_main">
                <!-- 轮换中间区域 -->
                <div class="lunhuancenter">
                    <ul id='lunbonum' style="z-index:4">
                        <li class='lunboone'></li>
                        <li ></li>
                        <!--<li ></li>-->
                    </ul>
                    <!-- 轮播的页码  结束 -->
                </div>
                <!-- 轮换中间区域结束 -->
            </div>
        </div>
    </div>
</div>
<!-- 首屏 -->
<!-- 企业客户 -->
<div class="minwidth">
    <h3 class="index_title">合作伙伴</h3>
    <ul class="enetrCustomer overflowhidden">
        <li class="eCFirstLi">
            <a class="eCFirst mi" href="javascript:;"></a>
            <a class="k36" href="javascript:;"></a>
            <a class="dd" href="javascript:;"></a>
            <a class="aly" href="javascript:;"></a>
            <a class="cyj" href="javascript:;"></a>
            <a class="gk" href="javascript:;"></a>
        </li>
        <li>
            <a class="eCFirst boss" href="javascript:;"></a>
            <a class="lg" href="javascript:;"></a>
            <a class="the" href="javascript:;"></a>
            <a class="xsy" href="javascript:;"></a>
            <a class="shan" href="javascript:;"></a>
            <a class="worktitle" href="javascript:;"></a>
        </li>
    </ul>
</div>
<!-- 企业客户 -->

<div class="main oneEntrepreneurship marT40"></div>

@endsection