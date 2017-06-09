<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResultController as Result;
use App\Verification\CommonVerification;

class CommonController extends Controller
{
    private $result;
    private $commonverification;
    public function __construct ()
    {
        $this->result = new Result();
        $this->commonverification = new CommonVerification();
    }

    /**
     * 发布评论
     * @param Request $request
     */
    public function common (Request $request)
    {
        return $this->commonverification->common($request);
    }
}
