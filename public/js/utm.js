
/**
 * 根据Cookie变量名称，从获取系统中提取对应的变量值
 *
 * @param String cname, 变量名称
 * @return String
 */
function getCookie(cname)
{
	var ca = document.cookie.split(';');
	for(var i=0; i<ca.length; i++)
	{
		var c = $.trim(ca[i]).split('=');

		if (c[0].indexOf(cname)==0 && c[0].length == cname.length)
		{
			return c[1];
		}
	}

	return '';
}

function getQueryString(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	var r = window.location.search.substr(1).match(reg);
	if (r != null) return unescape(r[2]); return null;
}

//增加小能咨询动作记录
function addXnOperation() {
	var operationXn = getCookie('operationXn');
	var utmkey = getCookie('utmkey');
	if (!operationXn)
	{
		$.ajax({
			url: "/utm/addXnOperation",
			data: {"utmkey":utmkey},
			cache: false,
			dataType:'json',
			success: function(ret){

			}
		});
	}
}

function addOperation(operationtype) {
	var operation = getCookie('operation_'+operationtype);
	var utmkey = getCookie('utmkey');

	if (!operation)
	{
		$.ajax({
			url: "/utm/addOperation",
			data: {"utmkey":utmkey,"operationtype":operationtype},
			cache: false,
			dataType:'json',
			success: function(ret){

			}
		});
	}
}

var utmkey = getCookie('utmkey');
var utmflag = getCookie('utmflag');
var link = window.location.href;
var referer = document.referrer;
var useragent = navigator.userAgent;
var utm_term = getQueryString('utm_term');

if (!utmflag)
{
	$.ajax({
		url: "/utm/addInterview",
		data: {"utmkey":utmkey,"link":link,"referer":referer,"useragent":useragent,"utm_term":utm_term},
		cache: false,
		dataType:'json',
		success: function(ret){

		}
	});
}