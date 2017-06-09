<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResultController as Result;
use App\Verification\FindsellVerification;

class FindsellController extends JaseController
{
    private $result;
    public function __construct()
    {
        $this->result = new Result();
        $this->findsellverification = new FindsellVerification();
    }
    /**
     * 发现列表
     * @param Request $request
     */
    public function index (Request $request)
    {
        return $this->findsellverification->index($request);
    }

    /**
     * 发现详情 - 买卖
     * @param Request $request
     * @return mixed
     */
    public function findSellInfo (Request $request)
    {
        return $this->findsellverification->findSellInfo($request);
    }

    /**
     * 发现详情 - 朋友圈
     * @param Request $request
     * @return mixed
     */
    public function findSellInfos (Request $request)
    {
        return $this->findsellverification->findSellInfos($request);
    }

    /**
     * 我要买卖编辑权限
     * @param Request $request
     * @return mixed
     */
    public function findSellChangeAuth (Request $request)
    {
        return $this->findsellverification->findSellChangeAuth($request);
    }

    /**
     * 我要买卖编辑查看
     * @param Request $request
     * @return mixed
     */
    public function findSellEdit (Request $request)
    {
        return $this->findsellverification->findSellEdit($request);
    }

    /**
     * 我要买卖编辑
     * @param Request $request
     * @return mixed
     */
    public function findSellUpdate (Request $request)
    {
        return $this->findsellverification->findSellUpdate($request);
    }

    /**
     * 我要买卖删除
     * @param Request $request
     * @return mixed
     */
    public function findSellDelete (Request $request)
    {
        return $this->findsellverification->findSellDelete($request);
    }
}
