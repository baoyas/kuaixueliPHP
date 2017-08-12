/**
 * 服务商列表页面，切换服务地区的省份
 * @author nyl
 * @param integer province  省份ID
 */
function showCityList(province)
{
	$(".t-cityAreaList").css('display', 'none');
	$(".t-provinceCityList").css('display', 'none');
	if (province == 1)
	{
		$("#cityArea_3302").css('display', 'block');
	}
	else if (province == 2)
	{
		$("#cityArea_3303").css('display', 'block');
	}
	else if (province == 9)
	{
		$("#cityArea_3304").css('display', 'block');
	}
	else if (province == 22)
	{
		$("#cityArea_3305").css('display', 'block');
	}
	else 
	{
		$("#provinceCity_" + province).css('display', 'block');
	}
}

/**
 * 服务商列表页面，切换服务地区的市
 * @author nyl
 * @param integer city  市ID
 */
function showAreaList(city)
{
	$(".t-cityAreaList").css('display', 'none');
	$("#cityArea_" + city).css('display', 'block');
}

/**
 * 搜索服务商
 * @author nyl
 * @param string url
 */
function searchProvider( url, canBe)
{
	var providerName = $.trim($('#providerName').val());
	if (!providerName)
	{
		if(canBe > 0)
		{
			location.href=url;
		}
		return false;
	}
	url += '&rn=' + providerName;
	location.href=url;
}

var newPpId = 0;

/**
 * 点击QQ咨询
 * @author nyl
 * @param integer ppId
 */
function qqconsultation(ppId)
{
	newPpId = ppId
	if (isSubmit)
	{
		 return false;
	}
	isSubmit = true;
	$.getJSON('/provider/qqconsultation/ppid/' + ppId, function(result){
		isSubmit = false;
		if (true == result.ret)
		{
			//$("#qqconsultation_" + ppId).click();
			//location.href=$("#qqconsultation_" + ppId).attr('href');
			window.open($("#qqconsultation_" + ppId).attr("href"), '_blank');
			window.location.reload();
		}
		else
		{
			// 未登录则弹出登录框
			if ('undefined' != typeof(result.code) && 10001 == result.code && $("#quReg").length > 0)
			{
				Base.alert($("#quReg").val(), '快速注册 ', 860, 475);
				return false;
			}
			Base.alertTime(result.msg);
			return false;
		}
	});
}

$(function(){
	$("#providerName").keydown(function(event){
		if (event.keyCode==13)
		{
			var url = $(this).attr('url');
			var canBe = $(this).attr('canBe');
			searchProvider(url, canBe);
		}
	});
});

/**
 * 注册或者登录后操作
 * @return
 */
function afterAction()
{
	qqconsultation(newPpId);
}

/**
 * 从服务商详情页选购产品
 * */
function getProviderProduct( url, providerId, productId, province, city, area )
{
	$.getJSON('/provider/ajaxGetProviderProduct/'
		+'providerId/' + providerId
		+ '/productId/' + productId
		+ '/pr/' + province
		+ '/ct/' + city
		+ '/ar/' + area,
		function(result){
			if( result.ret && result.data.ppid)
				Base.webview('/product/package/ppid/' + result.data.ppid, 700, 500, '选择套餐');
			else
				location.href = url;
		});
}

/**
 * 购买产品
 * @author nyl
 */
function bugProduct(url, ppid, canordersstate, identitytitle)
{
	if (canordersstate == 1)
	{
		if (!confirm('该' + identitytitle + '当前状态为休息中，可能无法及时与您取得联系，坚持购买请确认。'))
		{
			return false;
		}
	}
	$.getJSON('/product/checkPackage/ppid/' + ppid, function(result){
		if (result.ret)
		{
			Base.webview('/product/package/ppid/' + ppid, 700, 500, '选择套餐');
		}
		else 
		{
			location.href=url;
		}
	});
}