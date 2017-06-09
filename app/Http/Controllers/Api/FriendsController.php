<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResultController as Result;
use App\Verification\FriendsVerification;

class FriendsController extends Controller
{
    private $result;
    private $friendsverification;
    public function __construct ()
    {
        $this->result = new Result();
        $this->friendsverification = new FriendsVerification();
    }
    /**
     * 发布朋友圈
     * @param Request $request
     */
    public function store (Request $request)
    {
        return $this->friendsverification->store($request);
    }

    /**
     * 我的朋友圈
     * @param Request $request
     */
    public function friendsList (Request $request)
    {
        return $this->friendsverification->friendsList($request);
    }

    /**
     * 其他人的主页
     * @param Request $request
     */
    public function outherFriends (Request $request)
    {
        return $this->friendsverification->outherFriends($request);
    }
}
