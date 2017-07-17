<?php

namespace App\Verification;
use App\Helpers\Helpers;
use App\Http\Controllers\Api\ResultController as Result;
use App\Model\Sell;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\JaseController;
use Illuminate\Support\Facades\Config;
use App\Transformer\UsersTransformer;
use App\Transformer\FriendsTransformer;
use App\Model\Thumbs;


/**
 * Class FriendsVerification
 *
 * @package \App\Verification
 */
class FriendsVerification
{
    public function __construct ()
    {
        $this->result = new Result();
        $this->jasecontroller = new JaseController();
        $this->userstransformer = new UsersTransformer();
        $this->friendstransformer = new FriendsTransformer();
    }

    /**
     * 发布朋友圈
     * @param Request $request
     */
    public function store (Request $request)
    {
        $uid = $request->item['uid'];
        $sell_pic = $request->get('sell_pic');
        $sell_video = $request->get('sell_video');
        $sell_describe = $request->get('sell_describe');
        $sell_video_pic = $request->get('sell_video_pic');
        if ($sell_pic == '')
        {
            $sell_pic = null;
        }
        else
        {
            $sell_pic = serialize(explode(',', $sell_pic));
        }

        if ($sell_video == '')
        {
            $sell_video = null;
        }
        /**
         * 判断 描述
         */
//        if ($sell_describe == '')
//        {
//            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('描述不能为空！')->responseError();
//        }
        $save = [
            'sell_uid' => $uid,
            'sell_pic' => $sell_pic,
            'sell_video' => $sell_video,
            'sell_video_pic' => $sell_video_pic,
            'sell_describe' => Helpers::str_replace_add($sell_describe),
            'sell_time' => time(),
            'sell_up_time' => time(),
            'sell_order' => 100,
            'is_circle' => 1,
            'is_sell' => 3
        ];
        $statues = Sell::create($save);
        if ($statues)
        {
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'message' => '发布成功！'
            ]);
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('发布失败！')->responseError();
        }
    }

    /**
     * 我的朋友圈
     * @param Request $request
     */
    public function friendsList (Request $request)
    {
        $uid = $request->item['uid'];
        $phone = $request->item['username'];
        $my_friends = $this->jasecontroller->My_friends($phone, $uid);
        $friends_ids = $my_friends['user_id'];
        $userInfo = User::where('id', $uid)->first()->toArray();
        $sell = Sell::where('sell.is_del', 0)
                    //->where('sell.is_circle', 1)
                    ->whereIn('sell.sell_uid', $friends_ids)
                    ->orderBy('sell.sell_time', 'desc')
                    ->join('user as u', 'sell.sell_uid', '=', 'u.id')
                    ->select('sell.*', 'u.user_face', 'u.nickname', 'u.phone')
                    ->paginate(Config::get('web.api_page'))->toArray();
        if (empty($sell['data']) && $request->get('page') == 2)
        {
            return $this->result->setStatusMsg('error')->setStatusCode(0)->setMessage('没有更多的数据了！')->responsePage();
        }
        else if (!empty($sell['data']))
        {
            foreach ($sell['data'] as $k=>$v)
            {
                if ($v['sell_pic'] != null)
                {
                    $_tmp_pic = [];
                    $tmp_pic = unserialize($v['sell_pic']);
                    foreach ($tmp_pic as $s)
                    {
                        $_tmp_pic[] = config('web.QINIU_URL').'/'.$s;
                    }
                    $sell['data'][$k]['sell_pic'] = $_tmp_pic;
                }
                else
                {
                    $sell['data'][$k]['sell_pic'] = "";
                }

                if ($v['sell_price'] == '0' && $v['sell_price_max'] == '0')
                {
                    $sell['data'][$k]['money'] = '0.00';
                }
                else
                {
                    if ($v['sell_price'] == '0')
                    {
                        $sell['data'][$k]['money'] = $v['sell_price_max'];
                    }
                    elseif ($v['sell_price_max'] == '0')
                    {
                        $sell['data'][$k]['money'] = $v['sell_price'];
                    }
                    else
                    {
                        $sell['data'][$k]['money'] = "".$v['sell_price']."" . ' - ' . "".$v['sell_price_max']."";
                    }
                }

                $sell['data'][$k]['sell_time'] = Helpers::timeFormat($v['sell_time']);
                $sell['data'][$k]['sell_thumbsUp'] = $this->jasecontroller->sell_thumbsUp($v['id']);
                $sell['data'][$k]['sell_comment'] = $this->jasecontroller->sell_comment($v['id']);
                $sell['data'][$k]['is_thumbsUp'] = $this->jasecontroller->is_thumbsUp($uid, $v['id'], 1);
                $sell['data'][$k]['user_face'] = config('web.QINIU_URL').'/'.$v['user_face'];
                $sell['data'][$k]['sell_area'] = $v['sell_area'];
            }
        }
        else
        {
            $obj = [
                'userInfo' => $this->userstransformer->transformController($userInfo)
            ];
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'object' => [$obj]
            ]);
        }
        if ($request->get('page') == 1)
        {
            $obj = [
                'userInfo' => $this->userstransformer->transformController($userInfo),
                'friends' => $this->friendstransformer->transformController($sell['data'])
            ];
        }
        else
        {
            $obj = [
                'friends' => $this->friendstransformer->transformController($sell['data'])
            ];
        }
        return $this->result->responses([
            'status' => 'success',
            'status_code' => '',
            'object' => [$obj]
        ], $sell);
    }

    /**
     * 其他人的主页
     * @param Request $request
     */
    public function outherFriends (Request $request)
    {
        $uid = $request->item['uid'];
        $outher_id = $request->get('outher_id');
        $outher = User::where('phone', $outher_id)->first()->toArray();

        $my_friends = $this->jasecontroller->My_friends($outher['phone'], $outher['id']); //查看他的朋友
        $friends_ids = $my_friends['user_id'];
        //查看我是不是他的朋友
        if (in_array($uid, $friends_ids))
        {
            $sell = Sell::where('sell.sell_uid', $outher['id'])->where('sell.is_del', 0)->orderBy('sell.sell_up_time', 'desc')->join('user as u', 'sell.sell_uid', '=', 'u.id')->select('sell.*', 'u.user_face', 'u.nickname', 'u.phone')->paginate(Config::get('web.api_page'))->toArray();
        }
        else
        {
            $sell = Sell::where('sell.sell_uid', $outher['id'])->where('sell.is_circle', 0)->where('sell.is_del', 0)->orderBy('sell.sell_up_time', 'desc')->join('user as u', 'sell.sell_uid', '=', 'u.id')->select('sell.*', 'u.user_face', 'u.nickname', 'u.phone')->paginate(Config::get('web.api_page'))->toArray();
        }
        /**
         * 判断有没有数据
         */
        if (empty($sell['data']) && $request->get('page') >= 2)
        {
            return $this->result->setStatusMsg('error')->setStatusCode(0)->setMessage('没有更多的数据了！')->responsePage();
        }
        elseif(!empty($sell['data']))
        {
            $_show_sell = $this->jasecontroller->sell_handle($sell['data'], $uid);
            foreach ($_show_sell as $k=>$v)
            {
                if ($v['sell_pic'] != null)
                {
                    $_tmp_pic = [];
                    $tmp_pic = unserialize($v['sell_pic']);
                    foreach ($tmp_pic as $s)
                    {
                        $_tmp_pic[] = config('web.QINIU_URL').'/'.$s;
                    }
                    $_show_sell[$k]['sell_pic'] = $_tmp_pic;
                }
                else
                {
                    $_show_sell[$k]['sell_pic'] = "";
                }

                if ($v['sell_price'] == '0' && $v['sell_price_max'] == '0')
                {
                    $_show_sell[$k]['money'] = '0.00';
                }
                else
                {
                    if ($v['sell_price'] == '0')
                    {
                        $_show_sell[$k]['money'] = $v['sell_price_max'];
                    }
                    elseif ($v['sell_price_max'] == '0')
                    {
                        $_show_sell[$k]['money'] = $v['sell_price'];
                    }
                    else
                    {
                        $_show_sell[$k]['money'] = "".$v['sell_price']."" . ' - ' . "".$v['sell_price_max']."";
                    }
                }
//                dd($v);
                $_show_sell[$k]['user_face'] = config('web.QINIU_URL').'/'.$v['user_face'];
                $_show_sell[$k]['sell_time'] = Helpers::timeFormat($v['sell_up_time']);
                $_show_sell[$k]['sell_thumbsUp'] = $this->jasecontroller->sell_thumbsUp($v['id']);
                $_show_sell[$k]['sell_comment'] = $this->jasecontroller->sell_comment($v['id']);
                $_show_sell[$k]['is_thumbsUp'] = $this->jasecontroller->is_thumbsUp($uid, $v['id']);
                $_show_sell[$k]['thumbsUp'] = [];
                $thumbsUp = Thumbs::with('User')->where('thumbs_sell_id', $v['id'])->orderBy('id', 'desc')->limit(5)->get(['thumbs_uid'])->toArray();
                if($thumbsUp && is_array($thumbsUp)) {
                    foreach($thumbsUp as $val) {
                        if($val['user']) {
                            $_show_sell[$k]['thumbsUp'][] = $this->userstransformer->transform($val['user']);
                        }
                    }
                }
            }
            if ($request->get('page') == 1)
            {
                $obj = [
                    'userInfo' => $this->userstransformer->transformController($outher),
                    'sell' => $this->friendstransformer->transformController($_show_sell)
                ];
            }
            else
            {
                $obj = [
                    'sell' => $this->friendstransformer->transformController($_show_sell)
                ];
            }
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'object' => [$obj]
            ], $sell);
        }
        else
        {
            $obj = [
                'userInfo' => $this->userstransformer->transformController($outher)
            ];
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'object' => [$obj]
            ]);
        }
    }
}
