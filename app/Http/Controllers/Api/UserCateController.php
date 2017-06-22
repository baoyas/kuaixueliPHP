<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Model\Cate;
use App\Model\UserCate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResultController as Result;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class UserCateController extends JaseController
{
    private $result;
    public function __construct ()
    {
        $this->result = new Result();
    }

    public function index (Request $request)
    {
        $userCate = UserCate::with(['cate'=>function($query){
            $query->select(['id', 'cate_name']);
        }])->where(['user_id'=>$request->item['uid']])->get()->toArray();
        return $this->result->responses([
            'status' => 'success',
            'status_code' => '',
            'object' => $userCate
        ]);
    }

    public function store (Request $request)
    {
        $data = Cate::where(['cate_power'=>1,'pid'=>0])->orderBy('cate_sort', 'asc')->get()->toArray();
        return $this->result->responses([
            'status' => 'success',
            'status_code' => '',
            'object' => $data
        ]);
    }
}
