<?php

namespace App\Http\Controllers\Api;

use App\Model\Ad;
use App\Model\Common;
use App\Model\Sell;
use App\Model\Thumbs;
use App\Model\User;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformer\AdobjectTransformer;
use App\Lib\JassEasemob;

class JaseController extends Controller
{
    use Helpers;

    private $AdobjectTransformer;
    private $jasseasemob;
    public function __construct ()
    {
        $this->AdobjectTransformer = new AdobjectTransformer();
        $this->jasseasemob = new JassEasemob();
    }

    /**
     * 广告列表
     * @param int $adplace  默认朋友圈 跳转方式id
     * @return array
     */
    public function adList ($adplace = 5)
    {
        $time = time();
        $data = Ad::where('ad_place_id', $adplace)->where('ad_start_at', '<=', $time)->where('ad_end_at', '>=', $time)->orderBy('ad_object.ad_object_sort', 'asc')->join('ad_place as ap', 'ad_object.ad_place_id', '=', 'ap.id')->join('ad_skip as ak', 'ad_object.ad_skip_id', '=', 'ak.id')->select('ad_object.*', 'ap.ad_place_name', 'ak.ad_skip_name', 'ak.ad_skip_describe')->get()->toArray();
        if (!empty($data))
        {
            return $this->AdobjectTransformer->transformController($data);
        }
        else
        {
            return [];
        }
    }

    /**
     * 发现我要买处理显示不显示
     * @param $sell
     * @param $uid
     */
    public function sell_handle ($sell , $uid)
    {
        $user = User::where('id', $uid)->first();
        $show_sell = []; //全部
        $ifriend_show_sell = []; //自己的好友查看
        $i_show_sell = [];  //仅限自己查看
//        dd($sell);
        foreach ($sell as $k=>$v)
        {
            /**
             * auth 1 所有人可见
             */
            if ($v['sell_auth'] == 1 || $v['sell_auth'] == null)
            {
                $show_sell[] = $v;
            }

            /**
             *  仅限自己的好友可见
             */
            if ($v['sell_auth'] == 2)
            {
                $tmp_sell = $this->jasseasemob->showFriend($user->phone);
                $is_friends = $tmp_sell['phone'];
                /**
                 * 判断我是否在发布者的好友列表中
                 */
                if (in_array($v['phone'], $is_friends))
                {
                    $ifriend_show_sell[] = $v;
                }
                /**
                 * 判断是否是自己
                 */
                if ($v['phone'] == $user->phone)
                {
                    $ifriend_show_sell[] = $v;
                }
            }

            /**
             * 仅限自己可见
             */
            if ($v['sell_auth'] == 3)
            {
                if ($uid == $v['sell_uid'])
                {
                    $i_show_sell[] = $v;
                }
            }
        }
        $_show_sell = array_merge($show_sell, $ifriend_show_sell, $i_show_sell);
        return $_show_sell;
    }

    /**
     * 点击量
     * @param $sell_id
     */
    public function sell_thumbsUp ($sell_id, $is_friends = false)
    {
        if ($is_friends === false)
        {
            return Thumbs::where('thumbs_sell_id', $sell_id)->count();  //买卖
        }
        else
        {
            return Thumbs::where('thumbs_sell_id', $sell_id)->where('is_friends', 1)->count(); //朋友圈
        }
    }

    /**
     * 评论量
     * @param $sell_id
     */
    public function sell_comment ($sell_id)
    {
        return Common::where('common_id', $sell_id)->where('common_type', 1)->count();
    }

    /**
     * 我是否点赞
     * @param $uid
     * @param $sell_id
     */
    public function is_thumbsUp ($uid , $sell_id, $is_friends = false)
    {
        if ($is_friends === false)
        {
            $sell = Thumbs::where('thumbs_uid', $uid)->where('thumbs_sell_id', $sell_id)->first();
        }
        else
        {
            $sell = Thumbs::where('thumbs_uid', $uid)->where('thumbs_sell_id', $sell_id)->where('is_friends', 1)->first();
        }
//        dd($sell);
        if ($sell)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    /**
     * 我的朋友
     * @param $phone  我的手机号
     * 返回朋友的uid
     */
    public function My_friends ($phone, $uid)
    {
        $tmp_sell = $this->jasseasemob->showFriend($phone);
        $my_friends_count = $tmp_sell['count']; //我的朋友个数
        $users = User::whereIn('phone', $tmp_sell['phone'])->select('id')->get()->toArray();
        $user_id = [];
        foreach ($users as $v)
        {
            $user_id[] = $v['id'];
        }
        $user_id[] = $uid;
        $obj = [
            'counts' => $my_friends_count,
            'user_id' => $user_id
        ];
        return $obj;
    }
}
