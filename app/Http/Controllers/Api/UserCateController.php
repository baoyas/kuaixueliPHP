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
        $userId = $request->item['uid'];
        $userCate = UserCate::with(['cate'=>function($query){
            $query->select(['id', 'cate_name']);
        }])->where(['user_id'=>$userId])->get()->toArray();
        return $this->result->responses([
            'status' => 'success',
            'status_code' => '',
            'object' => $userCate
        ]);
    }

    public function store (Request $request)
    {
        $all = $request->all();
        $userId = $request->item['uid'];
        if(isset($all['cate_ids'])) {
            $cateIds = array_filter(array_unique(explode(',', $all['cate_ids'])));
            $cates = Cate::whereIn('id', $cateIds)->get(['id'])->toArray();
            $cates = array_column($cates, 'id');
            if(empty($cates)) {
                UserCate::where(['user_id'=>$userId])->delete();
            } else {
                UserCate::where(['user_id'=>$userId])->whereNotIn('cate_id', $cates)->delete();
                $userCates = [];
                foreach($cates as $cate_id) {
                    $userCates[] = ['user_id'=>$userId, 'cate_id'=>$cate_id];
                }
                UserCate::InsertOnDuplicateKey($userCates);
            }
        }
        $userCate = UserCate::with(['cate'=>function($query){
            $query->select(['id', 'cate_name']);
        }])->where(['user_id'=>$userId])->get()->toArray();
        return $this->result->responses([
            'status' => 'success',
            'status_code' => '',
            'object' => $userCate
        ]);
    }
}
