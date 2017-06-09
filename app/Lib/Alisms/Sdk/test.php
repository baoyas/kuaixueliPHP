<?php
    include "TopSdk.php";
    date_default_timezone_set('Asia/Shanghai'); 

    $c = new TopClient;
	$c->appkey = '23330896';
	$c->secretKey = 'f2b41ee4280327be4d5cd089fa16b60c';
	$req = new AlibabaAliqinFcSmsNumSendRequest;
	$req->setExtend("123456");
	$req->setSmsType("normal");
	$req->setSmsFreeSignName("大鱼测试");
	$code = '123456';
	$tel = '15504765081';
	$req->setSmsParam('{"code":"'.$code.'","product":"我能"}');
	$req->setRecNum("".$tel."");
	$req->setSmsTemplateCode("SMS_6415181");
	$resp = $c->execute($req);
	$arr = object_to_array($resp);
	echo '<pre>';
	print_r($arr['result']['err_code']);die;

	function object_to_array($obj){
    $_arr = is_object($obj)? get_object_vars($obj) : $obj;
    foreach ($_arr as $key => $val) {
        $val = (is_array($val)) || is_object($val) ? object_to_array($val) : $val;
        $arr[$key] = $val;
    }
 
    return $arr;
}
?>