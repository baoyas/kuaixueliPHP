@extends('layouts.app')

@section('content')
<!-- logo -->
<div class="minwidth kfwNav headerOverflowInherit" style="z-index:100;">
    <div class="floatLeft kfwNaSub headerOverflowInherit">
        <a href="javascript:;" style="cursor: default" rel="nofollow">全部服务分类</a>
        <div class="kfwNaSubSub kfwNaSubSubIsHome">
            <div class="floatLeft headerOverflowInherit kfwISubNav">
                <ul class="iNav overflowhidden headerOverflowInherit">
                    <li class="iNavFirst">
                        <i></i>
                        <h4>研究生进修</h4>
                    </li>
                    <li >
                        <i></i>
                        <h4>本科进修</h4>
                    </li>
                    <li >
                        <i></i>
                        <h4>专科进修</h4>
                    </li>
                    <li >
                        <i></i>
                        <h4>中职进修</h4>
                    </li>
                    <li>
                        <i></i>
                        <h4>论文指导与发表</h4>
                    </li>
                    <li >
                        <i></i>
                        <h4>职业技能证书</h4>
                    </li>
                    <li >
                        <i></i>
                        <h4>就业课堂</h4>
                    </li>
                </ul>
            </div>
            <div class="floatLeft iSubNav">
                <!-- 研究生进修 -->
                <div class="kfwanimate">
                    <ul class="floatLeft iSubNavLe overflowhidden">
                        <li>
                            <a href="#" ><h5>硕士研究生</h5></a>
                            <div class="kfwNavOne">
                                <a class="active" href="{{ url('/education/level') }}">在职研究生</a>
                                <a  href="{{ url('/education/level') }}">英国一年研究生</a>
                                <a class="active" href="{{ url('/education/level') }}">MBA</a>
                            </div>
                        </li>
                        <li>
                            <a href="{{ url('/education/level') }}" ><h5>博士研究生</h5></a>
                            <div >
                                <a class="active" href="{{ url('/education/level') }}">EMBA</a>
                            </div>
                        </li>
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
                <!-- 研究生进修 -->
                <!-- 本科进修-->
                <div class="kfwanimate">
                    <ul class="floatLeft iSubNavLe overflowhidden">
                        <li>
                            <a href="/product/kssb.html" ><h5>自学考试</h5></a>
                            <div class="kfwNavOne">

                            </div>
                        </li>
                        <li>
                            <a href="/product/Americasb.html" ><h5>电大</h5></a>
                            <div>

                            </div>
                        </li>
                        <li>
                            <a href="/product/trademarktransfer.html" ><h5>成人高考</h5></a>
                            <div>

                            </div>
                        </li>
                        <li>
                            <a href="/product/trademarktransfer.html" ><h5>远程教育</h5></a>
                            <div >

                            </div>
                        </li>
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
                <!-- 本科进修 -->
                <!-- 专科进修 -->
                <div class="kfwanimate">
                    <ul class="floatLeft iSubNavLe overflowhidden">
                        <li>
                            <a href="/product/securityservice-20times.html"><h5>自学考试</h5></a>
                            <div class="kfwNavOne">

                            </div>
                        </li>
                        <li>
                            <a href="/product/gerenshebaogongjijin.html"><h5>成人高考</h5></a>
                            <div>

                            </div>
                        </li>
                        <li>
                            <a href="/product/HRConsultant-PhoneMonth.html"><h5>电大</h5></a>
                            <div></div>
                        </li>
                        <li>
                            <a href="/product/HRConsultant-PhoneMonth.html"><h5>远程教育</h5></a>
                            <div></div>
                        </li>
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
                <!-- 专科进修 -->
                <!-- 中职进修 -->
                <div class="kfwanimate">
                    <ul class="floatLeft iSubNavLe overflowhidden">

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
                <!-- 中职进修 -->

                <!-- 论文指导与发表-->
                <div class="kfwanimate">
                    <ul class="floatLeft iSubNavLe overflowhidden">

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
                <!-- 论文指导与发表 -->
                <!-- 职业技能证书 -->
                <div class="kfwanimate">
                    <ul class="floatLeft iSubNavLe overflowhidden">
                        <li>
                            <a href="/product/account.html" ><h5>软件水平资格</h5></a>

                        </li>

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
                <!-- 职业技能证书 -->
                <!-- 就业课堂 -->
                <div class="kfwanimate">
                    <ul class="floatLeft iSubNavLe overflowhidden">

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
                <!-- 就业课堂 -->
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
                <p style=" background: url(Image/shoutu.jpg) no-repeat scroll center center; opacity: 1;filter:alpha(opacity=100);">
                    <a href="javascript:void(0);" target="_blank" rel="nofollow"></a>
                </p>
                <p style=" background: url(Image/kuaixueli2.jpg) no-repeat scroll center center; ">
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