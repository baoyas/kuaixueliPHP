<?php

namespace App\Http\Controllers\Api;

//use Fast;
use App\Fcore\Facades\Fast;
use App\Fcore\Grid;
use App\Fcore\Layout\Content;
use App\Fcore\Controllers\ModelForm;
use App\Helpers\Helpers;
use App\Model\UserArea;
use App\Model\Sell;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResultController as Result;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class TestController extends JaseController
{
    use ModelForm;
    
    
    public function index()
    {
        return Fast::content(function (Content $content) {
            $content->header('出售/购买管理');
            $content->body($this->grid());
        });
    }
    
    public function grid() {
        return new Grid(Fast::getModel(Sell::class), function(Grid $grid){
            $grid->model()->where(['is_del'=>0])->orderBy('sell_time', 'desc');
            $grid->column('id', 'ID')->sortable();
            $grid->column('sell_order', '排序')->editable()->sortable();
            $grid->column('is_sell', '类别')->sortable()->display(function($is_sell){
                if($is_sell==1) {
                    return '购买';
                } elseif($is_sell==2) {
                    return '出售';
                } elseif($is_sell==3) {
                    return '朋友圈';
                }
            });
            $grid->column('sell_title', '标题');
            $grid->user('用户')->display(function($user){
                return "<p>ID:{$user['id']}, {$user['nickname']}</p><p>{$user['phone']}</p>";
            });
            $grid->column('recommend', '是否推荐')->display(function($recommend){
            return $recommend==1 ? '是' : '否';
            });
                
                    $grid->disableActions();
                    $grid->disableBatchDeletion();
                    $grid->disableExport();
                    $grid->disableCreation();
                    $grid->disableRowSelector();
        });
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_bak()
    {
        return Fast::content(function (Content $content) {
            $content->header('出售/购买管理');
            $content->body($this->grid());
        });
    }

    public function grid_bak() {
        return new Grid(Fast::getModel(UserArea::class), function(Grid $grid){
            //$grid->model()->where(['is_del'=>0])->orderBy('sell_time', 'desc');
            $grid->column('id',  'ID')->display(function($id){
                return $id;
            });
            $grid->column('province_id',  'province_id')->display(function($province_id){
                return $province_id;
            });
            $grid->disableActions();
            //$grid->disableBatchDeletion();
            $grid->disableExport();
            $grid->disableCreation();
            $grid->disableRowSelector();
        });
    }

    public function store (Request $request)
    {
        $userId = $request->item['uid'];
    }
}
