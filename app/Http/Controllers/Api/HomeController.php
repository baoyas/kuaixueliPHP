<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Lib\JassEasemob;
use App\Model\City;
use App\Model\Sell;
use App\Model\User;
use App\Model\Version;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResultController as Result;
use App\Verification\HomeVerification;
use App\Http\Controllers\Api\JaseController;
use App\Transformer\SellTransformer;
class HomeController extends Controller
{
    private $result;
    private $homeverification;
    private $JassEasemob;
    private $jasecontroller;
    private $selltransformer;
    public function __construct ()
    {
        $this->result = new Result();
        $this->homeverification = new HomeVerification();
        $this->JassEasemob = new JassEasemob();
        $this->jasecontroller = new JaseController();
        $this->selltransformer = new SellTransformer();
    }
    /**
     * 获取七牛token
     * @param Request $request
     */
    public function getToken (Request $request)
    {
        return $this->homeverification->getToken($request);
    }

    /**
     * 城市列表
     * @return array
     */
    public function cityList ()
    {
        $city_hot = City::where('is_hot', 1)->where('power', 1)->get()->toArray();
        $city = City::where('power', 1)->get()->toArray();

        $arr = [
            'city_hot' => $city_hot,
            'city' => Helpers::get_all_city($city)
        ];
        return $arr;
    }

    /**
     * 查看我的通讯记录 手机号 是否注册 是否是我好友 1未注册 2 已注册 3我的好友
     * @param Request $request
     */
    public function checkPhone (Request $request)
    {
        $userPhone = $request->item['username'];
        $uid = $request->item['uid'];
        $other = $request->get('other');

        $_other = explode(',', $other);

        $new_outher = [];
        foreach ($_other as $k=>$v)
        {
            $reg_code = $this->UserReg($v);
            $new_outher[$k]['phone'] = $v;
            $new_outher[$k]['code'] = $reg_code['code'];
            $new_outher[$k]['user_face'] = $reg_code['user_face'];
            $new_outher[$k]['nickname'] = $reg_code['nickname'];
            $new_outher[$k]['accounts'] = $reg_code['accounts'];
        }

        $new_arr = [];
        foreach ($new_outher as $k=>$v)
        {
            $new_arr[$k]['phone'] = $v['phone'];
            $new_arr[$k]['user_face'] = $v['user_face'];
            $new_arr[$k]['nickname'] = $v['nickname'];
            $new_arr[$k]['accounts'] = $v['accounts'];
            if ($v['code'] == 2)
            {
                $new_arr[$k]['code'] = $this->UserFriend($userPhone, $v['phone']);
            }
            else
            {
                $new_arr[$k]['code'] = $v['code'];
            }
        }
        return $this->result->responses([
            'status' => 'success',
            'status_code' => '',
            'object' => $new_arr
        ]);
    }

    /**
     * 用户手机号是否注册
     * @param $userPhone
     */
    public function UserReg ($userPhone)
    {
        $reg = User::where('phone', $userPhone)->first();
        if ($reg)
        {
            return [
                'user_face' => config('web.QINIU_URL').'/'.$reg->user_face,
                'nickname' => $reg->nickname,
                'accounts' => $reg->accounts,
                'code' => '2'
            ];
        }
        else
        {
            return [
                'user_face' => '',
                'nickname' => '',
                'accounts' => '',
                'code' => '1'
            ];
        }
    }

    /**
     * 查看我俩是否是好友
     * @param $iphone
     * @param $outherPhone
     */
    public function UserFriend ($iphone, $outherPhone)
    {
        $friends = $this->JassEasemob->showFriend($iphone);
        if (in_array($outherPhone, $friends['phone']))
        {
            return '3';
        }
        else
        {
            return '2';
        }
    }

    /**
     * 统计我发布的买卖和朋友圈
     * @return mixed
     */
    public function totalSell(Request $request)
    {
        $uid = $request->item['uid'];
        $businessTotal = Sell::where('is_sell', 1)->where('is_del', 0)->where('sell_uid', $uid)->count();
        $businessSellTotal = Sell::where('is_sell', 2)->where('is_del', 0)->where('sell_uid', $uid)->count();
        $firendTotal = Sell::where('is_circle', 1)->where('is_del', 0)->where('sell_uid', $uid)->count();
        $arr = [
            'businessTotal' => "".$businessTotal."",
            'businessSellTotal' => "".$businessSellTotal."",
            'firendTotal' => "".$firendTotal."",
        ];
        return $this->result->responses([
            'status' => 'success',
            'status_code' => '',
            'object' => [$arr]
        ]);
    }

    /**
     * 分享
     * @param $sell_id
     * @return mixed
     */
    public function share ($sell_id)
    {
        $sell = Sell::where('sell.id', $sell_id)->join('user as u', 'sell.sell_uid', '=', 'u.id')->join('cate as c', 'sell.cate_id', '=', 'c.id')->select('sell.*', 'u.user_face', 'u.nickname', 'u.phone', 'c.cate_name')->first()->toArray();
        if ($sell) {
            if ($sell['sell_pic'] != null) {
                $_tmp_pic = [];
                $tmp_pic = unserialize($sell['sell_pic']);
                foreach ($tmp_pic as $v) {
                    $_tmp_pic[] = config('web.QINIU_URL') . '/' . $v;
                }
            } else {
                $_tmp_pic = [];
            }

            $sell['sell_time'] = Helpers::timeFormat($sell['sell_time']);
            $sell['user_face'] = $sell['user_face'];
            $sell['sell_pic'] = $_tmp_pic;
            $sell['sell_thumbsUp'] = $this->jasecontroller->sell_thumbsUp($sell['id']);
            $sell['sell_comment'] = $this->jasecontroller->sell_comment($sell['id']);
            $sell['is_thumbsUp'] = 0;
            if ($sell['sell_price'] == '0' && $sell['sell_price_max'] == '0') {
                $sell['money'] = '0.00';
            } else {
                if ($sell['sell_price'] == '0') {
                    $sell['money'] = $sell['sell_price_max'];
                } elseif ($sell['sell_price_max'] == '0') {
                    $sell['money'] = $sell['sell_price'];
                } else {
                    $sell['money'] = "" . $sell['sell_price'] . "" . ' - ' . "" . $sell['sell_price_max'] . "";
                }
            }

        }
        $obj = [
            'sell_info' => $this->selltransformer->transformController([$sell]),
        ];
        return $this->result->responses([
            'status' => 'success',
            'status_code' => '',
            'object' => [$obj]
        ]);
    }


    /**
     * 获取对应的版本
     * @param $type
     */
    public function version ($type)
    {
        $data = Version::where('ver_terminal', $type)->limit(1)->orderBy('id', 'desc')->get()->toArray();
        $_data = [];
        foreach ($data as $k=>$v)
        {
            $_data[$k]['ver_number'] = $v['ver_number'];
            $_data[$k]['ver_content'] = $v['ver_content'];
        }
        $obj = [
            'version' => $_data
        ];
        return $this->result->responses([
            'status' => 'success',
            'status_code' => '',
            'object' => [$obj]
        ]);
    }
}
