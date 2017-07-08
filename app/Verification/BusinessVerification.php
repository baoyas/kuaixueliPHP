<?php

namespace App\Verification;
use App\Helpers\Helpers;
use App\Http\Controllers\Api\ResultController as Result;
use App\Model\Sell;
use App\Model\Thumbs;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Transformer\SellerTransformer;
use App\Http\Controllers\Api\JaseController;
use App\Transformer\SellTransformer;
use App\Lib\JassEasemob;

/**
 * Class BusinessVerification
 *
 * @package \App\Verification
 */
class BusinessVerification
{
    public function __construct ()
    {
        $this->result = new Result();
        $this->sellertransformer = new SellerTransformer();
        $this->jasecontroller = new JaseController();
        $this->selltransformer = new SellTransformer();
        $this->jassEasemob = new JassEasemob();
    }

    /**
     * 我要买
     * @param Request $request
     */
    public function Buystore (Request $request)
    {
        $uid = $request->item['uid'];
        $sell_title = $request->get('sell_title');
        $sell_pic = $request->get('sell_pic');
        $cate_id = $request->get('cate_id');
        $sell_describe = $request->get('sell_describe');
        $sell_price = $request->get('sell_price');
        $sell_price_max = $request->get('sell_price_max');
        $sell_area = $request->get('sell_area');

        if ($sell_price > $sell_price_max)
        {
            return $this->result->setStatusMsg('error')->setStatusCode(408)->setMessage('最大价格不能小于最小价格！')->responseError();
        }

        /**
         * 判断标题是否为空
         */
        if ($sell_title == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('标题不能为空！')->responseError();
        }
        /**
         * 判断图片是否为空
         */
        if ($sell_pic == '')
        {
//            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('图片不能为空！')->responseError();
            $sell_pic = null;
        }
        else
        {
            $_sell_pic = explode(',', $sell_pic);
            $sell_pic = serialize($_sell_pic);
        }
        /**
         * 判断分类
         */
        if ($cate_id == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('分类必选！')->responseError();
        }
        /**
         * 判断 描述
         */
        if ($sell_describe == '')
        {
//            return $this->result->setStatusMsg('error')->setStatusCode(406)->setMessage('描述不能为空！')->responseError();
            $sell_describe = $sell_title;
        }
        /**
         * 判断价格
         */
        if ($sell_price == '' || $sell_price_max == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(407)->setMessage('价格不能为空！')->responseError();
        }
        /**
         * 判断 位置
         */
        if ($sell_area == '')
        {
//            return $this->result->setStatusMsg('error')->setStatusCode(408)->setMessage('位置不能为空！')->responseError();
            $sell_area = null;
        }
        $save = [
            'sell_title' => $sell_title,
            'sell_pic' => $sell_pic,
            'cate_id' => $cate_id,
            'sell_describe' => Helpers::str_replace_add($sell_describe),
            'sell_price' => $sell_price,
            'sell_price_max' => $sell_price_max,
            'sell_area' => $sell_area,
            'sell_time' => time(),
            'sell_up_time' => time(),
            'sell_uid' => $uid,
            'sell_order' => 100,
            'is_sell' => 1
        ];
        $statues = Sell::create($save);
        if ($statues)
        {
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'message' => '发布成功！',
                'sell_id' => "".$statues->id."",
                'url' => env('APP_URL').'/api/businessRecommend?token='.$request->get('token')
            ]);
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(409)->setMessage('发布失败！')->responseError();
        }
    }

    /**
     * 我要卖
     * @param Request $request
     */
    public function Sellstore (Request $request)
    {
        $uid = $request->item['uid'];
        $sell_title = "".$request->get('sell_title')."";
        $sell_pic = $request->get('sell_pic');
        $cate_id = $request->get('cate_id');
        $sell_describe = $request->get('sell_describe');
        $sell_price = $request->get('sell_price');
        $sell_area = $request->get('sell_area');
        $sell_auth = $request->get('sell_auth');
        if ($sell_auth == '')
        {
            $sell_auth = 1; //默认所有人可见
        }
        /**
         * 判断标题是否为空
         */
        if ($sell_title == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('标题不能为空！')->responseError();
        }
        /**
         * 判断图片是否为空
         */
        if ($sell_pic == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('图片不能为空！')->responseError();
        }
        else
        {
            $sell_pic = explode(',', $sell_pic);
        }
        /**
         * 判断分类
         */
        if ($cate_id == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('分类必选！')->responseError();
        }
        /**
         * 判断 描述
         */
        if ($sell_describe == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(406)->setMessage('描述不能为空！')->responseError();
        }
        /**
         * 判断价格
         */
        if ($sell_price == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(407)->setMessage('价格不能为空！')->responseError();
        }
        /**
         * 判断 位置
         */
        if ($sell_area == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(408)->setMessage('位置不能为空！')->responseError();
        }

        $save = [
            'sell_title' => $sell_title,
            'sell_pic' => serialize($sell_pic),
            'cate_id' => $cate_id,
            'sell_describe' => Helpers::str_replace_add($sell_describe),
            'sell_price' => $sell_price,
            'sell_area' => $sell_area,
            'sell_time' => time(),
            'sell_up_time' => time(),
            'sell_uid' => $uid,
            'sell_order' => 100,
            'is_sell' => 2,
            'sell_auth' => $sell_auth
        ];
        $statues = Sell::create($save);
        if ($statues)
        {
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'message' => '发布成功！',
                'sell_id' => "".$statues->id."",
                'url' => env('APP_URL').'/api/sellRecommend?token='.$request->get('token')
            ]);
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(409)->setMessage('发布失败！')->responseError();
        }
    }

    /**
     * 我要买推荐
     * @param Request $request
     */
    public function businessRecommend (Request $request)
    {
        $uid = $request->item['uid'];
        $cate_id = $request->get('cate_id');
        $sell_id = $request->get('sell_id');
        $sellInfo = Sell::where('sell.id', $sell_id)->join('cate', 'sell.cate_id', '=', 'cate.id')->select('sell.*', 'cate.cate_name')->first();
        if (!$sellInfo)
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('没有查找到该信息！')->responseError();
        }
        $money = [
            ceil($sellInfo->sell_price*0.8),
            ceil($sellInfo->sell_price_max*1.2),
        ];
//        DB::enableQueryLog();
        $data = Sell::where('sell.cate_id', $cate_id)
                        ->where(['sell.is_sell'=>2, 'sell.is_del' => 0])
//                        ->where('sell.is_del', 0)
                        ->whereBetween('sell.sell_price', $money)
                        ->where('sell.sell_uid', '!=', $uid)
                        ->orderBy('sell.sell_time', 'desc')
                        ->join('user as u', 'sell.sell_uid', '=', 'u.id')
                        ->join('cate as c', 'sell.cate_id', '=', 'c.id')
                        ->limit(Config::get('web.businessRecommend'))
                        ->select('sell.*', 'u.user_face', 'u.nickname', 'u.autograph', 'u.phone', 'c.cate_name')
                        ->get()
                        ->toArray();
//        dd($data);
//        return response()->json(DB::getQueryLog());
//        if (empty($data1))
//        {
//            $data2 = Sell::where('sell.cate_id', $cate_id)->where('sell.is_sell', 2)->where('sell.is_del', 0)->where('sell.sell_uid', '!=', $uid)->orderBy('sell.sell_time', 'desc')->join('user as u', 'sell.sell_uid', '=', 'u.id')->join('cate as c', 'sell.cate_id', '=', 'c.id')->limit(Config::get('web.businessRecommend'))->select('sell.*', 'u.user_face', 'u.nickname', 'u.autograph', 'u.phone', 'c.cate_name')->get()->toArray();
//
//        }
        if (empty($data))
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('没有查找到更多的信息！')->responseError();
        }
        else
        {
            $_tmp_sell = $this->jasecontroller->sell_handle($data, $uid);
            foreach ($_tmp_sell as $k=>$v)
            {
                if ($v['sell_pic'] != null)
                {
                    $_tmp_pic = [];
                    $tmp_pic = unserialize($v['sell_pic']);
                    foreach ($tmp_pic as $s)
                    {
                        $_tmp_pic[] = config('web.QINIU_URL').'/'.$s;
                    }
                }
                else
                {
                    $_tmp_pic = [];
                }

                if ($v['sell_price'] == '0' && $v['sell_price_max'] == '0')
                {
                    $_tmp_sell[$k]['money'] = '0.00';
                }
                else
                {
                    if ($v['sell_price'] == '0')
                    {
                        $_tmp_sell[$k]['money'] = $v['sell_price_max'];
                    }
                    elseif ($v['sell_price_max'] == '0')
                    {
                        $_tmp_sell[$k]['money'] = $v['sell_price'];
                    }
                    else
                    {
                        $_tmp_sell[$k]['money'] = "".$v['sell_price']."" . ' - ' . "".$v['sell_price_max']."";
                    }
                }

                $_tmp_sell[$k]['sell_pic'] = $_tmp_pic;
                $_tmp_sell[$k]['sell_time'] = Helpers::timeFormat($v['sell_time']);
                $_tmp_sell[$k]['sell_thumbsUp'] = $this->jasecontroller->sell_thumbsUp($v['id']);
                $_tmp_sell[$k]['sell_comment'] = $this->jasecontroller->sell_comment($v['id']);
                $_tmp_sell[$k]['is_thumbsUp'] = $this->jasecontroller->is_thumbsUp($uid, $v['id']);
            }
            $user_phone = collect($_tmp_sell)->map(function ($_tmp_sell) {
                return $_tmp_sell['phone'];
            })->toArray();
//            dd($_tmp_sell);
            $system_user = User::where('phone', config('web.DEFAULT_UID'))->first();
            $ext = [
                'nickname' => $system_user->nickname,
                'user_face' => config('web.QINIU_URL').'/'.$system_user->user_face,
                'sell_id' => $sell_id
            ];
//            dd($system_user);
            $this->jassEasemob->yy_hxSend($system_user->phone, $user_phone, '小主！买家来啦！她看上了你的'.$sellInfo->cate_name.'，快去联系ta!', 'users', $ext);
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'object' => $this->selltransformer->transformController($_tmp_sell),
            ]);
//            return $this->result->responses([
//                'status' => 'success',
//                'status_code' => '',
//                'object' => $this->sellertransformer->transformController($data),
//            ]);
        }
    }

    /**
     * 我要卖推荐
     * @param Request $request
     */
    public function sellRecommend (Request $request)
    {
        $uid = $request->item['uid'];
        $cate_id = $request->get('cate_id');
        $sell_id = $request->get('sell_id');
        $sellInfo = Sell::where('sell.id', $sell_id)->join('cate', 'sell.cate_id', '=', 'cate.id')->select('sell.*', 'cate.cate_name')->first();
//        dd($sellInfo);
        if (!$sellInfo)
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('没有查找到该信息！')->responseError();
        }

        $money = [
            ceil($sellInfo->sell_price*0.8),
            ceil($sellInfo->sell_price*1.2),
        ];
//        dd($money);
        $data = Sell::where('sell.cate_id', $cate_id)
                        ->where('sell.is_sell', 1)
                        ->where('sell.is_del', 0)
//                        ->whereBetween('sell.sell_price', $money)
//                        ->orWhereBetween('sell.sell_price_max', $money)
//                        ->where(function ($query) use ($money) {
//                            $query->whereBetween('sell.sell_price', $money)
//                                ->orWhereBetween('sell.sell_price_max', $money);
//                        })
                        ->where('sell.sell_uid', '!=', $uid)
                        ->orderBy('sell.sell_time', 'desc')
                        ->join('user as u', 'sell.sell_uid', '=', 'u.id')
                        ->join('cate as c', 'sell.cate_id', '=', 'c.id')
                        ->limit(Config::get('web.sellRecommend'))
                        ->select('sell.*', 'u.user_face', 'u.nickname', 'u.autograph', 'u.phone', 'c.cate_name')
                        ->get()
                        ->toArray();
//        dd($data);
//        $_datas = [];
//        foreach ($data as $k=>$item)
//        {
//            if ($item['sell_price'] <= $sellInfo->sell_price*0.8 && $item['sell_price_max'] >= $sellInfo->sell_price*0.8)
//            {
//                $_datas[] = $item;
//            }
//            else if ($item['sell_price'] <= $sellInfo->sell_price*1.2 && $item['sell_price_max'] >= $sellInfo->sell_price*1.2)
//            {
//                $_datas[] = $item;
//            }
//        }
//        dd($_datas);
        $data = collect($data)->filter(function ($item) use($sellInfo) {
//            return $item['sell_price']*0.8 <= $sellInfo->sell_price && $item['sell_price_max']*1.2 >= $sellInfo->sell_price;
            return $item['sell_price'] <= $sellInfo->sell_price*0.8 && $item['sell_price_max'] >= $sellInfo->sell_price*0.8 || $item['sell_price'] <= $sellInfo->sell_price*1.2 && $item['sell_price_max'] >= $sellInfo->sell_price*1.2 || $item['sell_price'] >= $sellInfo->sell_price*0.8;
        })->toArray();
//        dd($data);
        if (empty($data))
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('没有查找到更多的信息！')->responseError();
        }
        else
        {
            $_tmp_sell = $this->jasecontroller->sell_handle($data, $uid);
            foreach ($_tmp_sell as $k=>$v) {
                if ($v['sell_pic'] != null) {
                    $_tmp_pic = [];
                    $tmp_pic = unserialize($v['sell_pic']);
                    foreach ($tmp_pic as $s) {
                        $_tmp_pic[] = config('web.QINIU_URL') . '/' . $s;
                    }
                } else {
                    $_tmp_pic = [];
                }
                $_tmp_sell[$k]['sell_pic'] = $_tmp_pic;
                $_tmp_sell[$k]['sell_time'] = Helpers::timeFormat($v['sell_time']);
                $_tmp_sell[$k]['sell_thumbsUp'] = $this->jasecontroller->sell_thumbsUp($v['id']);
                $_tmp_sell[$k]['sell_comment'] = $this->jasecontroller->sell_comment($v['id']);
                $_tmp_sell[$k]['is_thumbsUp'] = $this->jasecontroller->is_thumbsUp($uid, $v['id']);

                if ($v['sell_price'] == '0' && $v['sell_price_max'] == '0')
                {
                    $_tmp_sell[$k]['money'] = '0.00';
                }
                else
                {
                    if ($v['sell_price'] == '0')
                    {
                        $_tmp_sell[$k]['money'] = $v['sell_price_max'];
                    }
                    elseif ($v['sell_price_max'] == '0')
                    {
                        $_tmp_sell[$k]['money'] = $v['sell_price'];
                    }
                    else
                    {
                        $_tmp_sell[$k]['money'] = "".$v['sell_price']."" . ' - ' . "".$v['sell_price_max']."";
                    }
                }
//            return $this->result->responses([
//                'status' => 'success',
//                'status_code' => '',
//                'object' => $this->sellertransformer->transformController($data),
//            ]);
            }
//            dd($_tmp_sell);
            $user_phone = collect($_tmp_sell)->map(function ($_tmp_sell) {
                return $_tmp_sell['phone'];
            })->toArray();
//            dd($user_phone);

            $system_user = User::where('phone', config('web.DEFAULT_UID'))->first();
            $ext = [
                'nickname' => $system_user->nickname,
                'user_face' => config('web.QINIU_URL').'/'.$system_user->user_face,
                'sell_id' => $sell_id
            ];
//            dd($system_user);
            $this->jassEasemob->yy_hxSend($system_user->phone, $user_phone, '小主！卖家来啦！她刚刚发布了你要求购的'.$sellInfo->cate_name.'，快去联系ta!', 'users', $ext);
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'object' => $this->selltransformer->transformController($_tmp_sell),
            ]);
        }
    }

    /**
     * 我要买卖点赞
     * @param Request $request
     */
    public function thumbsUp (Request $request)
    {
        $uid = $request->item['uid'];
        $sell_id = $request->get('sell_id');
        $is_friends = $request->get('is_friends'); //是否点赞朋友圈
//        return '这快有问题！';
        $count = Thumbs::where('thumbs_uid', $uid)->where('thumbs_sell_id', $sell_id)->where('is_friends', $is_friends)->count();
        if ($count == 0)
        {
            $sell = Sell::where('id', $sell_id)->first();
            if ($sell)
            {
                $save = [
                    'thumbs_uid' => $uid,
                    'thumbs_sell_id' => $sell_id,
                    'thumbs_time' => time(),
                    'is_friends' => $is_friends
                ];
                $statues = Thumbs::create($save);
                if ($statues)
                {
                    User::addPoints($uid, 2);
                    return $this->result->responses([
                        'status' => 'success',
                        'status_code' => '',
                        'message' => '点赞成功！'
                    ]);
                }
                else
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('点赞失败！')->responseError();
                }
            }
            else
            {
                return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('参数错误！')->responseError();
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('您已经点赞过了！')->responseError();
        }
    }

    /**
     * 我要买卖取消点赞
     * @param Request $request
     */
    public function thumbsUpOff (Request $request)
    {
        $uid = $request->item['uid'];
        $sell_id = $request->get('sell_id');
        $thumbs = Thumbs::where('thumbs_uid', $uid)->where('thumbs_sell_id', $sell_id)->first();
        if ($thumbs)
        {
            $statues = Thumbs::where('thumbs_uid', $uid)->where('thumbs_sell_id', $sell_id)->delete();
            if ($statues)
            {
                return $this->result->responses([
                    'status' => 'success',
                    'status_code' => '',
                    'message' => '取消点赞成功！'
                ]);
            }
            else
            {
                return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('取消点赞失败！')->responseError();
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('参数错误！')->responseError();
        }
    }
}
