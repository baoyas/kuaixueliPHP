<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResultController as Result;
use App\Verification\SendVerification;

class SendController extends Controller
{
    private $result;
    private $sendverification;
    public function __construct ()
    {
        $this->result = new Result();
        $this->sendverification = new SendVerification();
    }

    /**
     * 群发 - 多用户
     * @param Request $request
     * @return mixed
     */
    public function sendUsers (Request $request)
    {
        return $this->sendverification->sendUsers($request);
    }

    /**
     * 群发 - 我今天剩余的次数
     * @param Request $request
     * @return mixed
     */
    public function surplusNum (Request $request)
    {
        return $this->sendverification->surplusNum($request);
    }

    public function uploadFile (Request $request)
    {
        return $this->sendverification->uploadFile($request);
    }
}
