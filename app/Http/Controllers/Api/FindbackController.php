<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResultController as Result;
use App\Verification\FindbackVerification;

class FindbackController extends Controller
{
    private $result;
    private $findbackverification;
    public function __construct ()
    {
        $this->result = new Result();
        $this->findbackverification = new FindbackVerification();
    }
    /**
     * 意见反馈
     * @param Request $request
     */
    public function feedback (Request $request)
    {
        return $this->findbackverification->feedback($request);
    }
}
