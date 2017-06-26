<?php

namespace App\Http\Controllers\Api;

use App\Model\UserArea;
use App\Model\Area;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResultController as Result;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

use App\Fcore\Facades\Fast;
use App\Fcore\Grid;
use App\Fcore\Layout\Content;
use App\Fcore\Controllers\ModelForm;

class UserAddrController extends JaseController
{
    private $result;
    public function __construct ()
    {
        $this->result = new Result();
    }

    public function index()
    {
        return Fast::content(function (Content $content) {
            $content->header('出售/购买管理');
            $content->body($this->grid());
        });
    }
    
    public function grid() {
        return Fast::grid(UserArea::class, function(Grid $grid){
            $userId = app('request')->item['uid'];
            $grid->model()->where(['user_id'=>$userId])->orderBy('is_default', 'desc');
            $grid->column('id', 'id');
            $grid->column('user_id', 'user_id');
            $grid->column('real_name', 'real_name');
            $grid->column('mobile', 'mobile');
            $grid->column('detail', 'detail');
            $grid->column('province_id', 'province_id');
            $grid->column('city_id', 'city_id');
            $grid->column('area_id', 'area_id');
            $grid->column('is_default', 'is_default');
            
            $grid->column('province.name', 'province_aaaname')->display(function($name){
                return $name;
            });
                
            $grid->city()->display(function($city){
                return $city['name'];
            });
            
            $grid->area()->display(function($area){
                return $area['name'];
            });
            $grid->disableActions();
            $grid->disableBatchDeletion();
            $grid->disableExport();
            $grid->disableCreation();
            $grid->disableRowSelector();
        });
    }
    public function index_bak (Request $request)
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
