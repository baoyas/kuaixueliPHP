<?php 
include_once './top/TopClient.php';
$c = new TopClient;
$c->appkey = '23330896';
$c->secretKey = 'f2b41ee4280327be4d5cd089fa16b60c';
$req = new AlibabaAliqinTaSmsNumSendRequest;
$req->setExtend("123456");
$req->setSmsType("normal");
$req->setSmsFreeSignName("短信签名");
$req->setSmsParam("");
$req->setRecNum("15561379779");
$req->setSmsTemplateCode("SMS_001");
$req->setExtendCode("1234");
$req->setExtendName("1234");
$resp = $c->execute($req, $sessionKey);
 ?>