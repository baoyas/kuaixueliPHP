<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResultController as Result;
use App\Verification\BusinessVerification;

class BusinessController extends JaseController
{
    private $result;
    private $businessverification;
    public function __construct ()
    {
        $this->result = new Result();
        $this->businessverification = new BusinessVerification();
    }
    /**
     * 我要买
     * @param Request $request
     */
    public function Buystore (Request $request)
    {
        return $this->businessverification->Buystore($request);
    }

    /**
     * 我要卖
     * @param Request $request
     * @return mixed
     */
    public function Sellstore (Request $request)
    {
        return $this->businessverification->Sellstore($request);
    }

    /**
     * 我要买推荐
     * @param Request $request
     * @return mixed
     */
    public function businessRecommend (Request $request)
    {
        return $this->businessverification->businessRecommend($request);
    }

    /**
     * 我要卖推荐
     * @param Request $request
     * @return mixed
     */
    public function sellRecommend (Request $request)
    {
        return $this->businessverification->sellRecommend($request);
    }

    /**
     * 我要买卖 点赞
     * @param Request $request
     */
    public function thumbsUp (Request $request)
    {
        return $this->businessverification->thumbsUp($request);
    }

    /**
     * 我要买卖取消点赞
     * @param Request $request
     * @return mixed
     */
    public function thumbsUpOff (Request $request)
    {
        return $this->businessverification->thumbsUpOff($request);
    }
}
