<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Model\Cate;
use App\Model\Area;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResultController as Result;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class AreaController extends JaseController
{
    private $result;
    public function __construct ()
    {
        $this->result = new Result();
    }

    public function index (Request $request)
    {
        $area = Area::where('level_type', '>', 0)->orderBy('parent_id', 'asc')->get(['id', 'parent_id', 'name'])->toArray();
        $data = [];
        foreach($area as $v) {
            if(empty($v['parent_id']) || $v['parent_id']==100000) {
                $data[$v['id']] = ['name'=>$v['name'], 'child'=>[]];
            } else if(isset($data[$v['parent_id']])) {
                $data[$v['parent_id']]['child'][$v['id']] = ['name'=>$v['name'], 'child'=>[]];
            } else {
                list($province_id, $city_id, $area_id) = str_split($v['parent_id'], 2);
                $parent_province_id = $province_id.'0000';
                $parent_city_id = $province_id.$city_id.'00';
                $data[$parent_province_id]['child'][$parent_city_id]['child'][$v['id']] = $v['name'];
            }
        }
        return $this->result->responses([
            'status' => 'success',
            'status_code' => '',
            'object' => $data
        ]);
    }
}
