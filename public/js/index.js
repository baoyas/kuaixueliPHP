$(function(){

	//首屏banner效果图
	var ali=$('#lunbonum li');
	var aPage=$('#lunhuanback p');
	var aslide_img=$('.lunhuancenter > div');
	var iNow=0;

	ali.each(function(index){
		$(this).mouseenter(function(){
			slide(index);
		})
	});

	function slide(index){
		iNow=index;
		ali.eq(index).addClass('lunboone').siblings().removeClass();
		aPage.eq(index).siblings().stop().animate({opacity:0},1000);
		aPage.eq(index).stop().animate({opacity:1},1000);
		aslide_img.eq(index).stop().animate({opacity:1,top:0},1000).siblings('div').stop().animate({opacity:0,top:0},1000);
	}

	function autoRun(){
		iNow++;
		if(iNow==ali.length){
			iNow=0;
		}
		slide(iNow);
	}

	var timer=setInterval(autoRun,5000);

	ali.hover(function(){
		clearInterval(timer);
	},function(){
		timer=setInterval(autoRun,5000);
	});

	//客户故事
	var userstory = {
		aPage: $(".ueImg li"),
		aPrev: $("#positLeft"),
		aNext: $("#positRight"),
		aliLth: $(".ueImg li").length-1,
		iNow:0,
		slide:function(index){
			this.iNow=index;
			this.aPage.eq(index).siblings().stop().fadeOut();
			this.aPage.eq(index).stop().fadeIn();
		},
		init:function(){
			var that = this;
			this.aNext.click(function(){
				if(that.iNow != that.aliLth){
					that.slide(that.iNow+1);
				}else{
					that.slide(0);
				}
			})
			this.aPrev.click(function(){
				if(that.iNow != 0){
					that.slide(that.iNow-1);
				}else{
					that.slide(that.aliLth);
				}
			})
		}
	}
	userstory.init();




	//图片预加载
	//$(".lazyload").lazyload();

	//视频弹框
	$(".innerVideo").click(function(){
		var videoHtml = "<div><video controls autoplay width='812' height='457'><source src='" + $(this).attr("data-url") + "'>您的浏览器不支持video标签,请使用高版本浏览器观看</video></div>";
		Base.alert(videoHtml, $(this).attr("data-title"), 812, 457, 0);

	});
	$(".classVideo").click(function(){
		Base.webview($(this).attr("data-url"), 812, 457, $(this).attr("data-title"));
	});

	//友情链接滚动
	new srcMarquee("ScrollMe",0,1,695,24,8,5000,5000,24);
});

//时间轴动画
function showDetails(obj,detailid){
	$('#timeaxisDetails_1').find('li').removeClass('cur');
	$(obj).addClass('cur');
	var html = $('#timeaxisCon'+detailid).val();
	$('#timeaxisCon_1').html(html);
}
function showDetails2(obj,detailid){
	$('#timeaxisDetails_2').find('li').removeClass('cur');
	$(obj).addClass('cur');
	var html = $('#timeaxisConb'+detailid).val();
	$('#timeaxisCon_2').html(html);
}

//友情链接滚动
function srcMarquee(){
	this.ID = document.getElementById(arguments[0]);
	if(!this.ID){this.ID = -1;return;}
	this.Direction = this.Width = this.Height = this.DelayTime = this.WaitTime = this.Correct = this.CTL = this.StartID = this.Stop = this.MouseOver = 0;
	this.Step = 1;
	this.Timer = 30;
	this.DirectionArray = {"top":0 , "bottom":1 , "left":2 , "right":3};
	if(typeof arguments[1] == "number")this.Direction = arguments[1];
	if(typeof arguments[2] == "number")this.Step = arguments[2];
	if(typeof arguments[3] == "number")this.Width = arguments[3];
	if(typeof arguments[4] == "number")this.Height = arguments[4];
	if(typeof arguments[5] == "number")this.Timer = arguments[5];
	if(typeof arguments[6] == "number")this.DelayTime = arguments[6];
	if(typeof arguments[7] == "number")this.WaitTime = arguments[7];
	if(typeof arguments[8] == "number")this.ScrollStep = arguments[8]
	this.ID.style.overflow = this.ID.style.overflowX = this.ID.style.overflowY = "hidden";
	this.ID.noWrap = true;
	this.IsNotOpera = (navigator.userAgent.toLowerCase().indexOf("opera") == -1);
	if(arguments.length >= 7)this.Start();
}
srcMarquee.prototype.Start = function(){
	if(this.ID == -1)return;
	if(this.WaitTime < 800)this.WaitTime = 800;
	if(this.Timer < 20)this.Timer = 20;
	if(this.Width == 0)this.Width = parseInt(this.ID.style.width);
	if(this.Height == 0)this.Height = parseInt(this.ID.style.height);
	if(typeof this.Direction == "string")this.Direction = this.DirectionArray[this.Direction.toString().toLowerCase()];
	this.HalfWidth = Math.round(this.Width / 2);
	this.BakStep = this.Step;
	this.ID.style.width = this.Width;
	this.ID.style.height = this.Height;
	if(typeof this.ScrollStep != "number")this.ScrollStep = this.Direction > 1 ? this.Width : this.Height;
	var msobj = this;
	var timer = this.Timer;
	var delaytime = this.DelayTime;
	var waittime = this.WaitTime;
	msobj.StartID = function(){msobj.Scroll()}
	msobj.Continue = function(){
		if(msobj.MouseOver == 1){
			setTimeout(msobj.Continue,delaytime);
		}
		else{ clearInterval(msobj.TimerID);
			msobj.CTL = msobj.Stop = 0;
			msobj.TimerID = setInterval(msobj.StartID,timer);
		}
	}
	msobj.Pause = function(){
		msobj.Stop = 1;
		clearInterval(msobj.TimerID);
		setTimeout(msobj.Continue,delaytime);
	}
	msobj.Begin = function(){
		msobj.ClientScroll = msobj.Direction > 1 ? msobj.ID.scrollWidth : msobj.ID.scrollHeight;
		if((msobj.Direction <= 1 && msobj.ClientScroll <msobj.Height) || (msobj.Direction > 1 && msobj.ClientScroll <msobj.Width))return;
		msobj.ID.innerHTML += msobj.ID.innerHTML;
		msobj.TimerID = setInterval(msobj.StartID,timer);
		if(msobj.ScrollStep < 0)return;
		msobj.ID.onmousemove = function(event){
			if(msobj.ScrollStep == 0 && msobj.Direction > 1){
				var event = event || window.event;
				if(window.event){
					if(msobj.IsNotOpera){msobj.EventLeft = event.srcElement.id == msobj.ID.id ? event.offsetX - msobj.ID.scrollLeft : event.srcElement.offsetLeft - msobj.ID.scrollLeft + event.offsetX;}
					else{msobj.ScrollStep = null;return;}
				}
				else{msobj.EventLeft = event.layerX - msobj.ID.scrollLeft;}
				msobj.Direction = msobj.EventLeft > msobj.HalfWidth ? 3 : 2;
				msobj.AbsCenter = Math.abs(msobj.HalfWidth - msobj.EventLeft);
				msobj.Step = Math.round(msobj.AbsCenter * (msobj.BakStep*2) / msobj.HalfWidth);
			}
		}
		msobj.ID.onmouseover = function(){
			if(msobj.ScrollStep == 0)return;
			msobj.MouseOver = 1;
			clearInterval(msobj.TimerID);
		}
		msobj.ID.onmouseout = function(){
			if(msobj.ScrollStep == 0){
				if(msobj.Step == 0)msobj.Step = 1;
				return;
			}
			msobj.MouseOver = 0;
			if(msobj.Stop == 0){
				clearInterval(msobj.TimerID);
				msobj.TimerID = setInterval(msobj.StartID,timer);
			}}}
	setTimeout(msobj.Begin,waittime);
}
srcMarquee.prototype.Scroll = function(){
	switch(this.Direction){
		case 0:
			this.CTL += this.Step;
			if(this.CTL >= this.ScrollStep && this.DelayTime > 0){
				this.ID.scrollTop += this.ScrollStep + this.Step - this.CTL;
				this.Pause();
				return;
			}
			else{
				if(this.ID.scrollTop >= this.ClientScroll){this.ID.scrollTop -= this.ClientScroll;}
				this.ID.scrollTop += this.Step;
			}
			break;
		case 1:
			this.CTL += this.Step;
			if(this.CTL >= this.ScrollStep && this.DelayTime > 0){
				this.ID.scrollTop -= this.ScrollStep + this.Step - this.CTL;
				this.Pause();
				return;
			}
			else{
				if(this.ID.scrollTop <= 0){this.ID.scrollTop += this.ClientScroll;}
				this.ID.scrollTop -= this.Step;
			}
			break;
		case 2:
			this.CTL += this.Step;
			if(this.CTL >= this.ScrollStep && this.DelayTime > 0){
				this.ID.scrollLeft += this.ScrollStep + this.Step - this.CTL;
				this.Pause();
				return;
			}
			else{
				if(this.ID.scrollLeft >= this.ClientScroll){this.ID.scrollLeft -= this.ClientScroll;}
				this.ID.scrollLeft += this.Step;
			}
			break;
		case 3:
			this.CTL += this.Step;
			if(this.CTL >= this.ScrollStep && this.DelayTime > 0){
				this.ID.scrollLeft -= this.ScrollStep + this.Step - this.CTL;
				this.Pause();
				return;
			}
			else{
				if(this.ID.scrollLeft <= 0){this.ID.scrollLeft += this.ClientScroll;}
				this.ID.scrollLeft -= this.Step;
			}
			break;
	}
}


;(function($){
	$.fn.timeaxisTab=function(options){

		var defaults={
			colorChangeClass:'colorChange',
			eventType:'click',
			fiUl:'#fiUl li',
			thDiv:'#thDiv div'
		}
		var options=$.extend(defaults,options);

		this.each(function(){
			var _this=$(this);
			_this.find(options.fiUl).bind(options.eventType,function(){
				$(this).addClass(options.colorChangeClass).siblings().removeClass(options.colorChangeClass);
				var index = $(this).index();
				_this.find(options.thDiv).eq(index).show().siblings().hide();
				_this.find(options.thDiv).eq(index).find('li:first-child').click();
			});
		});
	}
})(jQuery);