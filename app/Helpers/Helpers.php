<?php 

namespace App\Helpers;
use App\Lib\Geohash;
use App\Model\User;
use Illuminate\Support\Facades\Config;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class Helpers
{
    public static function UploadFile ($file = null)
    {
        if ($file != null)
        {
            // 用于签名的公钥和私钥
            $accessKey = Config::get('web.QINIU_AK');
            $secretKey = Config::get('web.QINIU_SK');

            // 初始化签权对象
            $auth = new Auth($accessKey, $secretKey);
            $bucket = Config::get('web.QINIU_BUCKET');

            // 生成上传Token
            $token = $auth->uploadToken($bucket);

            // 要上传文件的本地路径
            $filePath = $file;

            // 上传到七牛后保存的文件名
            $key = self::get_img_name($file);

            // 构建 UploadManager 对象
            $uploadMgr = new UploadManager();
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
            if ($err !== null) {
                return $err;
            } else {
                $ret['status'] = 0;
                return $ret;
            }
        }
        else
        {
            return [
                'status' => 1,
                'msg' => '参数错误！'
            ];
        }
    }

    /**
     * 多文件上传
     * @param null $file
     * @return array
     */
    public static function UploadFiles ($file = null)
    {
        if ($file != null)
        {
            // 用于签名的公钥和私钥
            $accessKey = Config::get('web.QINIU_AK');
            $secretKey = Config::get('web.QINIU_SK');

            // 初始化签权对象
            $auth = new Auth($accessKey, $secretKey);
            $bucket = Config::get('web.QINIU_BUCKET');

            // 生成上传Token
            $token = $auth->uploadToken($bucket);

            foreach ($file as $k=>$v)
            {
                // 要上传文件的本地路径
                $filePath = $v;

                // 上传到七牛后保存的文件名
                $key = self::get_img_name($v);

                // 构建 UploadManager 对象
                $uploadMgr = new UploadManager();
                $ret[] = $uploadMgr->putFile($token, $key, $filePath);
            }
            $arr = [];
            foreach ($ret as $k=>$v)
            {
                $arr[] = $v[0]['key'];
            }
            return $arr;
        }
        else
        {
            return [
                'status' => 1,
                'msg' => '参数错误！'
            ];
        }
    }

    /**
     * 获取七牛T
     */
    public static function uploadToken ()
    {
        // 用于签名的公钥和私钥
        $accessKey = Config::get('web.QINIU_AK');
        $secretKey = Config::get('web.QINIU_SK');

        // 初始化签权对象
        $auth = new Auth($accessKey, $secretKey);
        $bucket = Config::get('web.QINIU_BUCKET');

        // 生成上传Token
        $token = $auth->uploadToken($bucket);
        return $token;
    }

    /**
     * 上传图片重命名
     * @param $ext 图片扩展名
     * @return string
     */
    public static function get_img_name ($file)
    {
        list($width, $height) = getimagesize($file);
        $ext = strrchr($file->getClientOriginalName(),'.'); //获取图片格式
        $kkk = mt_rand(1,9999);
        return $width.'W_'.$height.'H_'.time() .$kkk. $ext;
    }


    /**
     * 时间戳转换成 多少秒前格式
     * @param $timeInt
     * @param string $format
     * @return bool|string
     */
   public static function timeFormat($timeInt,$format='Y-m-d'){
        if(empty($timeInt)||!is_numeric($timeInt)||!$timeInt){
            return '';
        }
        $d = (time()-$timeInt);
        if($d<0){
            return '';
        }else{
            if($d<60){
                return $d.'秒前';
            }else{
                if($d<3600){
                    return floor($d/60).'分钟前';
                }else{
                    if($d<86400){
                        return floor($d/3600).'小时前';
                    }else{
                        if($d<604800){//7天内
                            return floor($d/86400).'天前';
                        }else{
                            return date($format,$timeInt);
                        }
                    }
                }
            }
        }
    }

    /**
     * 替换字符串中的/n为<br />
     * @param $str
     * @return mixed
     */
   public static function textarea_replace_str ($str)
   {
        return str_replace("\\n", "\r\n", $str);
   }

    /**
     * 添加数据时 把/n/r 替换成\n
     * @param $str
     * @return mixed
     */
    public static function str_replace_add ($str)
    {
        $aa = str_replace("\n", "", $str);
        $aa = str_replace("'", "‘", $str);
        $aa = str_replace("/", "／", $str);
        $aa = str_replace("@", "＠", $str);
        $strs = htmlspecialchars($aa);
        $last = str_replace("%", "％", $strs);
        $strss = str_replace("\\r\\n", 'n', $last);
        return str_replace(array("\r\n", "\r", "\n"), '\n', $strss);
    }

    /**
     * 后台添加数据时 把/n/r 替换成\n
     * @param $str
     * @return mixed
     */
    public static function str_replace_add_admin ($str)
    {
        $aa = str_replace("'", "‘", $str);
        $aa = str_replace("%", "", $str);
        $aa = str_replace("/", "", $str);
        $strs = htmlspecialchars($aa);
        $strss =addslashes($strs);
        return str_replace("\r\n", '\n', $strss);
    }

    /**
     * 替换字符串中的/n为<br />
     * @param $str
     * @return mixed
     */
    public static function replace_str ($str)
    {
        return str_replace("\\n", "<br />", $str);
    }

    /**
     * 获取本周时间范围
     * @return mixed
     */
    public static function getWeek ()
    {
        header('Content-type: text/html; charset=utf-8');
        $date               = date('Y-m-d');  //当前日期
        $first              = '1'; //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
        $w                  = date('w',strtotime($date));  //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        $now_start          = date('Y-m-d',strtotime("$date -".($w ? $w - $first : 6).' days')); //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $week['now_start']  = date('Y-m-d',strtotime("$date -".($w ? $w - $first : 6).' days')); //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $week['now_end']    = date('Y-m-d',strtotime("$now_start + 6 days"));  //本周结束日期13
        $week['last_start'] = date('Y-m-d',strtotime("$now_start - 7 days"));  //上周开始日期
        $week['last_end']   = date('Y-m-d',strtotime("$now_start - 1 days"));  //上周结束日期
        return $week;
    }

    /**
     * 获取本月开始和结束时间
     * @return mixed
     */
    public static function getMonth ()
    {
        $month['start'] = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),1,date("Y")));
        $month['end']   = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("t"),date("Y")));
        return $month;
    }

    /**
     * 获取当天开始时间和结束时间
     * @param $day
     * @return mixed
     */
    public static function getToday ($day)
    {
        $today['start'] = strtotime($day .' 0:0:0');
        $today['end']   = strtotime($day . ' 23:59:59');
        return $today;
    }

    /**
     * 字符串多余的文字替换成...
     * @param $str_cut 字符串
     * @param $length 长度
     * @return string
     */
    public static function substr_cut($str_cut,$length)
    {
        if (strlen($str_cut) > $length)
        {
            for($i=0; $i < $length; $i++)
                if (ord($str_cut[$i]) > 130)    $i++;
            $str_cut = substr($str_cut,0,$i)."...";
        }
        return $str_cut;
    }

    /**
     * 生成唯一订单号
     * @return string
     */
    public static function get_order()
    {
        return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    /**
     * 生成程序内的账号
     * @return string
     */
    public static function get_uuid ($uid)
    {
        $user = User::where('id', $uid)->first();
        $str_last =  $uid . str_shuffle(strtotime(date('md', time())).substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 2));
        $str_num = mb_strlen($str_last, 'UTF-8');
        if ($str_num > 7)
        {
            $_str = mb_substr($str_last, 0, 7, 'UTF-8');
        }
        $user->accounts = $_str;
        $user->update();
        return $_str;
    }

    /**
     * token生成
     * @param $uid 用户idd
     * @param $username 用户帐号
     * @param $expire  过期时间
     * @return mixed
     */
    public static function make_token ($uid, $username, $expire)
    {
        $val = ['uid' => $uid, 'username' => $username, 'expire' => $expire];
        $user_json = self::json($val);
        return self::tt_encrypt($user_json);
    }

    /**
     * 判断用户是否登陆token是否过期
     * @param null $token token
     * @return array|bool
     */
    public static function is_login ($token = NULL)
    {
        if ($token == NULL)
        {
            return FALSE;
        }
        if ($token == '(null)')
        {
            return false;
        }
        $token_info = self::explain_token($token);
        $userInfo = User::where('id', $token_info['uid'])->first();
        if (!is_object($userInfo))
        {
            return FALSE;
        }
        if ($token != $userInfo->token)
        {
            return FALSE;
        }
        else
        {
            if (time() > $userInfo->expire)
            {
                return FALSE;
            }
            else
            {
                $userInfo->expire = time()+3600*24*7;
                $userInfo->update();
                $arr = [
                    'uid' => $token_info['uid'],
                    'username' => $token_info['username']
                ];
                return $arr;
            }
        }
    }

    /**
     * token解密
     * @param string $token token
     * @return array|bool
     */
    public static function explain_token ($token = '')
    {
        if ($token == '')
        {
            return FALSE;
        }
        $token_info = json_decode(self::tt_decrypt($token));
        if ($token_info == NULL)
        {
            return false;
        }
        $arr = [
            'uid'      => $token_info->uid,
            'username' => $token_info->username,
            'expire'   => $token_info->expire
        ];
        return $arr;
    }

    /**
     * 系统加密方法
     * @param $data 要加密的字符串
     * @param string $key 加密密钥
     * @param int $expire 过期时间 单位 秒
     * @return mixed
     */
    public static function tt_encrypt($data, $key = '', $expire = 0)
    {
        $key  = md5(empty($key) ? env('APP_KEY') : $key);
        $data = base64_encode($data);
        $x    = 0;
        $len  = strlen($data);
        $l    = strlen($key);
        $char = '';
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) $x = 0;
            $char .= substr($key, $x, 1);
            $x++;
        }
        $str = sprintf('%010d', $expire ? $expire + time():0);
        for ($i = 0; $i < $len; $i++) {
            $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1)))%256);
        }
        return str_replace(array('+','/','='),array('-','_',''),base64_encode($str));
    }

    /**
     * 系统解密方法
     * @param $data 要解密的字符串
     * @param string $key 加密密钥
     * @return string
     */
    public static function tt_decrypt($data, $key = '')
    {
        if ($data == '')
        {
            return 'null';
        }
        $key    = md5(empty($key) ? env('APP_KEY') : $key);
        $data   = str_replace(array('-','_'),array('+','/'),$data);
        $mod4   = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        $data   = base64_decode($data);
        $expire = substr($data,0,10);
        $data   = substr($data,10);
        if($expire > 0 && $expire < time()) {
            return '';
        }
        $x      = 0;
        $len    = strlen($data);
        $l      = strlen($key);
        $char   = $str = '';
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) $x = 0;
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            if (ord(substr($data, $i, 1))<ord(substr($char, $i, 1))) {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            }else{
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return base64_decode($str);
    }

    /**
     * 数组转json
     * @param $arr 数组
     * @return string
     */
    public static function json ($arr)
    {
        header("Content-Type: text/html; charset=UTF-8");
        $str = stripcslashes(json_encode($arr,JSON_UNESCAPED_UNICODE));
        return $str;
    }

    /**
     * 获取geohash
     * @param $lng 经度
     * @param $lat 纬度
     * @return string
     */
    public static function get_geohash ($lng, $lat)
    {
        $geohash = new Geohash();
        $computed_hash = $geohash->encode($lat, $lng);
        return substr($computed_hash, 0, 20);
    }

    /**
     * 附近定位
     * @param $prefix
     * @return mixed
     */
    public static function neighbors ($prefix)
    {
        $geohash = new Geohash();
        $neighbors = $geohash->neighbors($prefix);
        array_push($neighbors, $prefix);
        return $neighbors;
    }

    /**
     * 计算距离
     * @param $lat1 纬度值1
     * @param $lng1 经度值1
     * @param $lat2 纬度值2
     * @param $lng2 经度值2
     * @return float
     */
    public static function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6367000; //approximate radius of earth in meters

        /*
        Convert these degrees to radians
        to work with the formula
        */

        $lat1 = ($lat1 * pi() ) / 180;
        $lng1 = ($lng1 * pi() ) / 180;

        $lat2 = ($lat2 * pi() ) / 180;
        $lng2 = ($lng2 * pi() ) / 180;

        /*
        Using the
        Haversine formula

        http://en.wikipedia.org/wiki/Haversine_formula

        calculate the distance
        */

        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);  $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;

        return "".round($calculatedDistance)."";
    }

    /**\
     * 无线分类
     * @param $cate
     * @param int $pid
     * @return array
     */
    public static function unlimitedForLayer ($cate, $pid = 0)
    {
        $arr = array();
        foreach ($cate as $v) {
            if ($v['pid'] == $pid) {
                $v['child'] = self::unlimitedForLayer($cate,$v['id']);
                $arr[] = $v;
            }
        }
        return $arr;
    }

    /**
     * [get_all_city 城市字母排序]
     * @param  [type] $city [description]
     * @return [type]       [description]
     */
    public static function get_all_city($city)
    {
        if (is_array($city))
        {
            for ($i = 'A'; $i <= 'Z'; $i++)
            {
                foreach ($city as $k=>$vo)
                {
                    if ($i == $vo['letter'])
                    {
                        $array[$i][] = $vo;
                    }
                }

                if($i == 'Z')
                {
                    break;
                }
            }
        }
        return $array;
    }

    /**
     * 重新排序
     * @param $data
     * @return array
     */
    public static function array_chaifen ($data)
    {
        $arr = [];

        foreach ($data as $k => $v) {
            $arr[$v['sell_order']][] = $v;
        }

        $ret = [];
        $_data = [];
        foreach ($arr as $key => $value) {
            foreach ($value as $n => $m) {
                $ret[$key][] = $m['sell_up_time'];
            }
            $_data[] = self::array_pauxu($ret[$key], $arr[$key]);
        }
        return $_data;

    }

    public static function array_pauxu ($ret, $arr)
    {
        array_multisort($ret, SORT_DESC, $arr);
        return $arr;
    }

}

?>
