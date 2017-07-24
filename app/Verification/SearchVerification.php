<?php

namespace App\Verification;
use App\Helpers\Helpers;
use App\Http\Controllers\Api\ResultController as Result;
use App\Model\Sell;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Api\JaseController;
use App\Transformer\SearchbusinessTransformer;
use App\Transformer\UsersTransformer;

/**
 * Class SearchVerification
 *
 * @package \App\Verification
 */
class SearchVerification
{
    public function __construct()
    {
        $this->result = new Result();
        $this->jasecontroller = new JaseController();
        $this->searchbusinesstransformer = new SearchbusinessTransformer();
        $this->userstransformer = new UsersTransformer();
    }

    /**
     * 我要买搜索
     * @param Request $request
     */
    public function searchBusiness (Request $request)
    {
        $uid = $request->item['uid'];
        $search = $request->get('search_name');
        if ($search == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('请填写您要搜索的关键字！')->responseError();
        }
        $sell = Sell::where('sell.is_sell', '!=', 3)->where('sell.is_del', 0)->where('sell.is_circle', 0)->where('sell.sell_title', 'like', "%$search%")->orderBy('sell.sell_up_time', 'desc')->join('user as u', 'sell.sell_uid', '=', 'u.id')->select('sell.*', 'u.user_face', 'u.nickname', 'u.phone')->paginate(Config::get('web.api_page'))->toArray();
        if (empty($sell['data']))
        {
            return $this->result->setStatusMsg('error')->setStatusCode(0)->setMessage('没有更多的数据了！')->responsePage();
        }
        else
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
                $_show_sell[$k]['sell_time'] = Helpers::timeFormat($v['sell_time']);
                $_show_sell[$k]['sell_thumbsUp'] = $this->jasecontroller->sell_thumbsUp($v['id']);
                $_show_sell[$k]['sell_comment'] = $this->jasecontroller->sell_comment($v['id']);
                $_show_sell[$k]['is_thumbsUp'] = $this->jasecontroller->is_thumbsUp($uid, $v['id']);
            }
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'object' => $this->searchbusinesstransformer->transformController($_show_sell)
            ], $sell);
        }
    }

    /**
     * 我要卖搜索
     * @param Request $request
     */
    public function searchBusinessSell (Request $request)
    {
        $uid = $request->item['uid'];
        $search = $request->get('search_name');
        if ($search == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('请填写您要搜索的关键字！')->responseError();
        }
        $sell = Sell::where('sell.is_sell', 2)->where('sell.is_del', 0)->where('sell.is_circle', 0)->where('sell.sell_title', 'like', "%$search%")->orderBy('sell.sell_up_time', 'desc')->join('user as u', 'sell.sell_uid', '=', 'u.id')->select('sell.*', 'u.user_face', 'u.nickname', 'u.phone')->paginate(Config::get('web.api_page'))->toArray();
        if (empty($sell['data']))
        {
            return $this->result->setStatusMsg('error')->setStatusCode(0)->setMessage('没有更多的数据了！')->responsePage();
        }
        else
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
                $_show_sell[$k]['sell_time'] = Helpers::timeFormat($v['sell_time']);
                $_show_sell[$k]['sell_thumbsUp'] = $this->jasecontroller->sell_thumbsUp($v['id']);
                $_show_sell[$k]['sell_comment'] = $this->jasecontroller->sell_comment($v['id']);
                $_show_sell[$k]['is_thumbsUp'] = $this->jasecontroller->is_thumbsUp($uid, $v['id']);
            }
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'object' => $this->searchbusinesstransformer->transformController($_show_sell)
            ], $sell);
        }
    }

    /**
     * 搜索好友
     * @param Request $request
     */
    public function searchPeople (Request $request)
    {
        $uid = $request->item['uid'];
        $search = $request->get('search_name');
        if (!is_numeric($search))
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('请填写您要搜索的快学历账号！')->responseError();
        }
        $user = User::where('accounts', $search)->where('is_del', 0)->paginate(Config::get('web.api_page'))->toArray();
        if (empty($user['data']))
        {
            return $this->result->setStatusMsg('error')->setStatusCode(0)->setMessage('没有更多的数据了！')->responsePage();
        }
        else
        {
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'object' => $this->userstransformer->transformController($user['data'])
            ], $user);
        }
    }
}
