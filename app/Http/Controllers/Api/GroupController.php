<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResultController as Result;
use App\Verification\GroupVerification;

class GroupController extends Controller
{
    private $result;
    private $groupverification;
    public function __construct ()
    {
        $this->result = new Result();
        $this->groupverification = new GroupVerification();
    }
    /**
     * 创建群
     * @param Request $request
     */
    public function create (Request $request)
    {
        return $this->groupverification->create($request);
    }

    /**
     * 我的群组 的信息
     * @param Request $request
     * @return mixed
     */
    public function groupInfo (Request $request)
    {
        return $this->groupverification->groupInfo($request);
    }

    /**
     * 群组修改昵称
     * @param Request $request
     * @return mixed
     */
    public function groupEditGroupname (Request $request)
    {
        return $this->groupverification->groupEditGroupname($request);
    }

    /**
     * 群组修改描述
     * @param Request $request
     * @return mixed
     */
    public function groupEditDescribe (Request $request)
    {
        return $this->groupverification->groupEditDescribe($request);
    }

    /**
     * 群组修改头像
     * @param Request $request
     */
    public function groupEditGroupFace (Request $request)
    {
        return $this->groupverification->groupEditGroupFace($request);
    }

    /**
     * 圈全部成员
     * @param Request $request
     * @return mixed
     */
    public function members (Request $request)
    {
        return $this->groupverification->members($request);
    }

    /**
     * 搜索群
     * @param Request $request
     * @return mixed
     */
    public function search (Request $request)
    {
        return $this->groupverification->search($request);
    }
}
