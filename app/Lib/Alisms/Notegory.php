<?php

class Notegory extends \App\Http\Controllers\Api\JaseController{

	/**
	 * [$params 数组]
	 * @var [type]
	 */
	public $params;

    public function __construct($params)
    {
        $this->note_user = $params['NOTE_USER'];
        $this->note_pass = $params['NOTE_PASS']; 
    }

	public function noteGory ($tel)
    {
		include dirname(__FILE__) . "/Sdk/TopSdk.php";
	    date_default_timezone_set('Asia/Shanghai');
	    $c = new TopClient;
		$c->appkey = $this->note_user;
		$c->secretKey = $this->note_pass;
		$kkk= mt_rand(1,9999);
		$cc = str_pad(mt_rand(1, $kkk), 4, '0', STR_PAD_LEFT);
        //cache(['Verify' => '1234'], 600);
        //return 0;
        cache(['Verify' => $cc], 600);
		$req = new AlibabaAliqinFcSmsNumSendRequest;
		$req->setExtend("123456");
		$req->setSmsType("normal");
		$req->setSmsFreeSignName("快学历");
		$req->setSmsParam('{"code":"'.$cc.'","product":"快学历"}');
		$req->setRecNum("".$tel."");
		$req->setSmsTemplateCode("SMS_35070517");
		$resp = $c->execute($req);
		$arr = $this->object_to_array($resp);
		// echo '<pre>';
		// print_r($arr);die;
		if (array_key_exists('result', $arr) == '')
		{
			return true;
		}
		if ($arr['result']['err_code'] == 0)
		{
			return 0;
		}
		else
		{
			return true;
		}
	}

	public function object_to_array($obj){
	    $_arr = is_object($obj)? get_object_vars($obj) : $obj;
	    foreach ($_arr as $key => $val) {
	        $val = (is_array($val)) || is_object($val) ? self::object_to_array($val) : $val;
	        $arr[$key] = $val;
	    }
	    return $arr;
	}

}

?>
