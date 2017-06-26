<?php

namespace App\Http\Controllers\Api;

use App\Model\UserArea;
use App\Model\Area;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResultController as Result;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class UserAddrController extends JaseController
{
    private $result;
    public function __construct ()
    {
        $this->result = new Result();
    }

    public function index (Request $request)
    {
        $userId = $request->item['uid'];
        $userArea = UserArea::with('province')->with('city')->with('area')->where(['user_id'=>$userId,'is_delete'=>0])
                    ->orderBy('is_default', 'desc')
                    ->get(['id','user_id', 'real_name', 'mobile', 'detail', 'province_id', 'city_id', 'area_id', 'is_default'])
                    ->toArray();
        $data = [];
        foreach($userArea as $area) {
            $data[] = [
                'id'=>$area['id'],
                'user_id'=>$area['user_id'],
                'real_name'=>$area['real_name'],
                'mobile'=>$area['mobile'],
                'detail'=>$area['detail'],
                'province_id'=>$area['province_id'],
                'city_id'=>$area['city_id'],
                'area_id'=>$area['area_id'],
                'is_default'=>$area['is_default'],
                'province_name'=>$area['province']['name'],
                'ctiy_name'=>$area['city']['name'],
                'area_name'=>$area['area']['name']
            ];
        }
        return $this->result->responses([
            'status' => 'success',
            'status_code' => '',
            'object' => $data
        ]);
    }

    public function store (Request $request)
    {
        $userId = $request->item['uid'];
    }
}
