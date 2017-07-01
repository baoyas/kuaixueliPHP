<?php
/**
 * Created by PhpStorm.
 * User: MaoMao
 * Date: 2016/12/16
 * Time: 20:04
 */

namespace App\Verification;


use App\Helpers\Helpers;
use App\Model\Common;
use App\Model\Sell;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ResultController as Result;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Api\JaseController;
use App\Transformer\SellTransformer;
use App\Transformer\CommonTransformer;
use App\Transformer\FindselleditTransformer;
use App\Transformer\FriendsTransformer;

class FindsellVerification
{
    public function __construct()
    {
        $this->result = new Result();
        $this->jasecontroller = new JaseController();
        $this->selltransformer = new SellTransformer();
        $this->commontransformer = new CommonTransformer();
        $this->findselledittransformer = new FindselleditTransformer();
        $this->firendsstransformer = new FriendsTransformer();
    }

    /**
     * 发现列表
     * @param Request $request
     */
    public function index (Request $request)
    {
        $uid = $request->item['uid'];
        //查找不是朋友圈的
        $sell = Sell::where('sell.is_del', 0)
                    //->where('sell.is_circle', 0)
                    ->where('recommend', 1)
                    ->orderBy('sell.sell_order', 'desc')
                    ->join('user as u', 'sell.sell_uid', '=', 'u.id' )
                    ->join('cate as c', 'sell.cate_id', '=', 'c.id')
                    ->select('sell.*', 'u.user_face', 'u.nickname', 'u.phone', 'c.cate_name')
                    ->paginate(Config::get('web.api_page'))
                    ->toArray();
        if (empty($sell['data']) && $request->get('page') != 1)
        {
            return $this->result->setStatusMsg('error')->setStatusCode(0)->setMessage('没有更多的数据了！')->responsePage();
        }
        else
        {
            $_tmp_sell = $this->jasecontroller->sell_handle($sell['data'], $uid);
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
                $_tmp_sell[$k]['sell_time'] = Helpers::timeFormat($v['sell_up_time']);
                $_tmp_sell[$k]['sell_thumbsUp'] = $this->jasecontroller->sell_thumbsUp($v['id']);
                $_tmp_sell[$k]['sell_comment'] = $this->jasecontroller->sell_comment($v['id']);
                $_tmp_sell[$k]['is_thumbsUp'] = $this->jasecontroller->is_thumbsUp($uid, $v['id']);
            }
        }

//        array_multisort($_tmp_sell, SORT_DESC, $_tmp_sell);

        $_tmp_sell = Helpers::array_chaifen($_tmp_sell);
        $_tmp_sells = [];
        foreach ($_tmp_sell as $key => $value) {
            foreach ($value as $n => $m) {
                $_tmp_sells[] = $m;
            }
        }
//        dd($_tmp_sells);
        if ($request->get('page') == 1)
        {
            $adList = $this->jasecontroller->adList();
            if (!empty($_tmp_sells))
            {
                $obj = [
                    'adList' => $adList,
                    'sell' => $this->selltransformer->transformController($_tmp_sells)
                ];
            }
            else
            {
                $obj = [
                    'adList' => $adList,
                    'sell' => []
                ];
            }
        }
        else
        {
            $obj = [
                'sell' => $this->selltransformer->transformController($_tmp_sells)
            ];
        }
//        dd($obj);
        return $this->result->responses([
            'status' => 'success',
            'status_code' => '',
            'object' => [$obj]
        ], $sell);
    }

    /**
     * 发现详情
     * @param Request $request
     */
    public function findSellInfo (Request $request)
    {
        $uid = $request->item['uid'];
        $sell_id = $request->get('sell_id');
        $sell = Sell::where('sell.id', $sell_id)->join('user as u', 'sell.sell_uid', '=', 'u.id')->join('cate as c', 'sell.cate_id', '=', 'c.id')->select('sell.*', 'u.user_face', 'u.nickname', 'u.phone', 'c.cate_name')->first()->toArray();
        if ($sell)
        {
            if ($sell['sell_pic'] != null)
            {
                $_tmp_pic = [];
                $tmp_pic = unserialize($sell['sell_pic']);
                foreach ($tmp_pic as $v)
                {
                    $_tmp_pic[] = config('web.QINIU_URL').'/'.$v;
                }
            }
            else
            {
                $_tmp_pic = [];
            }

            $sell['sell_time'] = Helpers::timeFormat($sell['sell_time']);
            $sell['user_face'] = $sell['user_face'];
            $sell['sell_pic'] = $_tmp_pic;
            $sell['sell_thumbsUp'] = $this->jasecontroller->sell_thumbsUp($sell['id']);
            $sell['sell_comment'] = $this->jasecontroller->sell_comment($sell['id']);
            $sell['is_thumbsUp'] = $this->jasecontroller->is_thumbsUp($uid, $sell['id']);

            if ($sell['sell_price'] == '0' && $sell['sell_price_max'] == '0')
            {
                $sell['money'] = '0.00';
            }
            else
            {
                if ($sell['sell_price'] == '0')
                {
                    $sell['money'] = $sell['sell_price_max'];
                }
                elseif ($sell['sell_price_max'] == '0')
                {
                    $sell['money'] = $sell['sell_price'];
                }
                else
                {
                    $sell['money'] = "".$sell['sell_price']."" . ' - ' . "".$sell['sell_price_max']."";
                }
            }

            $sell_recommend = $this->sell_uid_recommend($sell['sell_uid']);

            /*评论*/

            $common = Common::where('common_id', $sell['id'])->where('common_type', 1)->join('user as u', 'common.form_uid', '=', 'u.id')->select('common.*', 'u.nickname as form_nickname', 'u.user_face as form_user_face')->paginate(Config::get('web.api_page'))->toArray();
            if (empty($common['data']))
            {
                if($request->get('page') != 1)
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(0)->setMessage('没有更多的数据了！')->responsePage();
                }

            }
            else
            {
                foreach ($common['data'] as $k=>$v)
                {
                    if ($v['to_uid'] == false)
                    {
                        $common['data'][$k]['common_time'] = Helpers::timeFormat($v['common_time']);
                        $common['data'][$k]['form_user_face'] = config('web.QINIU_URL').'/'.$v['form_user_face'];
                        $common['data'][$k]['to_user_face'] = "";
                        $common['data'][$k]['to_nickname'] = "";
                    }
                    else
                    {
                        $_user = User::where('id', $v['to_uid'])->first();
                        $common['data'][$k]['common_time'] = Helpers::timeFormat($v['common_time']);
                        $common['data'][$k]['form_user_face'] = config('web.QINIU_URL').'/'.$v['form_user_face'];
                        $common['data'][$k]['to_user_face'] = config('web.QINIU_URL').'/'.$_user->user_face;
                        $common['data'][$k]['to_nickname'] = $_user->nickname;
                    }
                }
            }
            if ($request->get('page') == 1)
            {
                if (!empty($common['data']))
                {
                    $commons = $this->commontransformer->transformController($common['data']);
                }
                else
                {
                    $commons = [];
                }
                $obj = [
                    'sell_info' => $this->selltransformer->transformController([$sell]),
                    'sell_recommend' => $sell_recommend,
                    'common' => $commons
                ];
            }
            else
            {
                $obj = [
                    'common' => $this->commontransformer->transformController($common['data'])
                ];
            }
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'object' => [$obj]
            ], $common);
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('参数错误！')->responseError();
        }
    }

    public function findSellInfos (Request $request)
    {
        $uid = $request->item['uid'];
        $sell_id = $request->get('sell_id');
        $sell = Sell::where('sell.id', $sell_id)->join('user as u', 'sell.sell_uid', '=', 'u.id')->select('sell.*', 'u.user_face', 'u.nickname', 'u.phone')->first()->toArray();
        if ($sell)
        {
//            if ($sell['sell_pic'] != null)
//            {
//                $tmp_pic = unserialize($sell['sell_pic']);
//                $_tmp_pic = [];
//                foreach ($tmp_pic as $v)
//                {
//                    $_tmp_pic[] = config('web.QINIU_URL').'/'.$v;
//                }
//            }
//            else
//            {
//                $_tmp_pic = "null";
//            }
//            $sell['sell_time'] = Helpers::timeFormat($sell['sell_time']);
//            $sell['user_face'] = config('web.QINIU_URL').'/'.$sell['user_face'];
//            $sell['sell_pic'] = $_tmp_pic;
//            $sell['sell_thumbsUp'] = $this->jasecontroller->sell_thumbsUp($sell['id']);
//            $sell['sell_comment'] = $this->jasecontroller->sell_comment($sell['id']);
//            $sell['is_thumbsUp'] = $this->jasecontroller->is_thumbsUp($uid, $sell['id']);
//            $sell_recommend = $this->sell_uid_recommend($sell['sell_uid']);
            /*评论*/

            $common = Common::where('common_id', $sell['id'])->where('common_type', 1)->join('user as u', 'common.form_uid', '=', 'u.id')->select('common.*', 'u.nickname as form_nickname', 'u.user_face as form_user_face')->paginate(Config::get('web.api_page'))->toArray();
            if (empty($common['data']))
            {
                if($request->get('page') != 1)
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(0)->setMessage('没有更多的数据了！')->responsePage();
                }

            }
            else
            {
                foreach ($common['data'] as $k=>$v)
                {
                    if ($v['to_uid'] == false)
                    {
                        $common['data'][$k]['common_time'] = Helpers::timeFormat($v['common_time']);
                        $common['data'][$k]['form_user_face'] = config('web.QINIU_URL').'/'.$v['form_user_face'];
                        $common['data'][$k]['to_user_face'] = "";
                        $common['data'][$k]['to_nickname'] = "";
                    }
                    else
                    {
                        $_user = User::where('id', $v['to_uid'])->first();
                        $common['data'][$k]['common_time'] = Helpers::timeFormat($v['common_time']);
                        $common['data'][$k]['form_user_face'] = config('web.QINIU_URL').'/'.$v['form_user_face'];
                        $common['data'][$k]['to_user_face'] = config('web.QINIU_URL').'/'.$_user->user_face;
                        $common['data'][$k]['to_nickname'] = $_user->nickname;
                    }
                }
            }
            if ($request->get('page') == 1)
            {
                if (!empty($common['data']))
                {
                    $commons = $this->commontransformer->transformController($common['data']);
                }
                else
                {
                    $commons = [];
                }

                $obj = [
//                    'sell_info' => $this->firendsstransformer->transformController([$sell]),
//                    'sell_recommend' => $sell_recommend,
                    'common' => $commons
                ];
            }
            else
            {
                $obj = [
                    'common' => $this->commontransformer->transformController($common['data'])
                ];
            }
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'object' => [$obj]
            ], $common);
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('参数错误！')->responseError();
        }
    }

    /**
     * 发布者的所有作品中的最新的两个 和 总个数
     * @param $sell_uid
     */
    public function sell_uid_recommend($sell_uid)
    {
        $sell_count = Sell::where('sell_uid', $sell_uid)->where('is_circle', 0)->count();
        //->where('sell_auth', 1)->where('sell_auth', null)
        $sell_rec = Sell::where('sell_uid', $sell_uid)->where('is_circle', 0)->where('sell_pic', '!=', null)->where(function ($query){
            $query->where('sell_auth', 1)->orWhere('sell_auth', null);
        })->orderBy('sell_time', 'desc')->limit(2)->get()->toArray();
        $_sell_rec = [];
        foreach ($sell_rec as $k=>$v)
        {
            $_sell_rec[$k]['id'] = "".$v['id']."";
            $_sell_rec[$k]['pic'] = config('web.QINIU_URL').'/'.unserialize($v['sell_pic'])[0];
            $_sell_rec[$k]['sell_title'] = "".$v['sell_title']."";
        }
        $obj = [
            'sell_count' => "".$sell_count."",
            'sell_rec' => $_sell_rec
        ];
        return $obj;
    }

    /**
     * 我要买卖编辑权限
     * @param Request $request
     */
    public function findSellChangeAuth (Request $request)
    {
        $uid = $request->item['uid'];
        $sell_id = $request->get('sell_id');
        $sell_auth = $request->get('sell_auth'); //权限 1所有人可见2仅限好友可见3仅限自己可见
        $sell = Sell::where('id', $sell_id)->first();
        if ($sell)
        {
            /**
             * 判断是否是该用户发布的
             */
            if ($uid == $sell->sell_uid)
            {
                /**
                 * 判断权限
                 */
                $auth = [1,2,3];
                if(in_array($sell_auth, $auth) === true)
                {
                    $sell->sell_auth = $sell_auth;
                    $statues = $sell->update();
                    if ($statues)
                    {
                        return $this->result->responses([
                            'status' => 'success',
                            'status_code' => '',
                            'message' => '权限更新成功！'
                        ]);
                    }
                    else
                    {
                        return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('编辑失败！')->responseError();
                    }
                }
                else
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('权限错误！')->responseError();
                }
            }
            else
            {
                return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('参数错误！')->responseError();
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('参数错误！')->responseError();
        }
    }

    /**
     * 我要买卖编辑查看
     * @param Request $request
     */
    public function findSellEdit (Request $request)
    {
        $uid = $request->item['uid'];
        $sell_id = $request->get('sell_id');
        $sell = Sell::where('sell.id', $sell_id)->join('cate as c', 'sell.cate_id', '=', 'c.id')->select('sell.*', 'c.cate_name')->first()->toArray();
        /**
         * 是否查找到
         */
        if (empty($sell))
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('没有查找到！')->responseError();
        }
        /**
         * 判断信息是否我发布的
         */
        if ($uid != $sell['sell_uid'])
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('该信息不是您发布的，不可编辑！')->responseError();
        }
        if ($sell['sell_pic'] != null)
        {
            $_tmp_pic = unserialize($sell['sell_pic']);
            $tmp_pic = [];
            foreach ($_tmp_pic as $v)
            {
                $tmp_pic[] = config('web.QINIU_URL').'/'.$v;
            }
            $sell['sell_pic'] = $tmp_pic;
        }
        else
        {
            $sell['sell_pic'] = [];
        }
        return $this->result->responses([
            'status' => 'success',
            'status_code' => '',
            'object' => $this->findselledittransformer->transformController([$sell])
        ]);
    }

    /**
     * 我要买卖编辑
     * @param Request $request
     */
    public function findSellUpdate (Request $request)
    {
        $uid = $request->item['uid'];
        $sell_title = $request->get('sell_title');
        $sell_pic = $request->get('sell_pic');
        $cate_id = $request->get('cate_id');
        $sell_describe = $request->get('sell_describe');
        $sell_price = $request->get('sell_price');
        $sell_area = $request->get('sell_area');
        $sell_auth = $request->get('sell_auth');
        $sell_id = $request->get('sell_id');
        $sell_price_max = $request->get('sell_price_max');

        $sell = Sell::where('id', $sell_id)->first();
        if ($sell)
        {
            /**
             * 判断是否被删除
             */
            if ($sell->is_del == 1)
            {
                return $this->result->setStatusMsg('error')->setStatusCode(410)->setMessage('该信息已被删除！')->responseError();
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
            if ($sell->is_sell == 1)
            {
                if ($sell_pic == '')
                {
                    $sell_pic = null;
                }
                else
                {
                    $sell_pic = serialize(explode(',', $sell_pic));
                }
            }
            else
            {
                if ($sell_pic == '')
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('图片不能为空！')->responseError();
                }
                else
                {
                    $sell_pic = serialize(explode(',', $sell_pic));
                }
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
            if ($sell->is_sell == 1)
            {
                $sell_area = $sell_area;
            }
            else
            {
                if ($sell_area == '')
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(408)->setMessage('位置不能为空！')->responseError();
                }
            }

            if ($sell_auth == '')
            {
                $sell_auth = 1; //默认所有人可见
            }
            if ($sell->is_sell == 1)
            {
                if ($sell_price > $sell_price_max)
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(410)->setMessage('最大价格不能小于最小价格！')->responseError();
                }
                $save = [
                    'sell_title' => $sell_title,
                    'sell_pic' => $sell_pic,
                    'cate_id' => $cate_id,
                    'sell_describe' => Helpers::str_replace_add($sell_describe),
                    'sell_price' => $sell_price,
                    'sell_price_max' => $sell_price_max,
                    'sell_area' => $sell_area,
                    'sell_up_time' => time(),
                ];
            }
            else
            {
                $save = [
                    'sell_title' => $sell_title,
                    'sell_pic' => $sell_pic,
                    'cate_id' => $cate_id,
                    'sell_describe' => Helpers::str_replace_add($sell_describe),
                    'sell_price' => $sell_price,
                    'sell_area' => $sell_area,
                    'sell_auth' => $sell_auth,
                    'sell_up_time' => time(),
                ];
            }
            $statues = Sell::where('id', $sell_id)->update($save);
            if ($statues)
            {
                return $this->result->responses([
                    'status' => 'success',
                    'status_code' => '',
                    'message' => '编辑成功！'
                ]);
            }
            else
            {
                return $this->result->setStatusMsg('error')->setStatusCode(411)->setMessage('编辑失败！')->responseError();
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(409)->setMessage('没有查找到！')->responseError();
        }
    }

    /**
     * 我要买卖删除
     * @param Request $request
     */
    public function findSellDelete (Request $request)
    {
        $uid = $request->item['uid'];
        $sell_id = $request->get('sell_id');
        $sell = Sell::where('id', $sell_id)->first();
        if ($sell)
        {
            if ($sell->is_del == 1)
            {
                return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('该信息已被删除！')->responseError();
            }

            if ($sell->sell_uid != $uid)
            {
                return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('该信息不是您发布的，不可删除！')->responseError();
            }

            $sell->is_del = 1;
            $statues = $sell->update();
            if ($statues)
            {
                return $this->result->responses([
                    'status' => 'success',
                    'status_code' => '',
                    'message' => '删除成功！'
                ]);
            }
            else
            {
                return $this->result->setStatusMsg('error')->setStatusCode(406)->setMessage('删除失败！')->responseError();
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('没有查找到！')->responseError();
        }
    }
}
