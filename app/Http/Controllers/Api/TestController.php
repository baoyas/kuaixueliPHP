<?php

namespace App\Http\Controllers\Api;

//use Fast;
use App\Fcore\Facades\Fast;
use App\Fcore\Grid;
use App\Fcore\Layout\Content;
use App\Fcore\Controllers\ModelForm;
use App\Helpers\Helpers;
use App\Model\UserArea;
use App\Model\Area;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResultController as Result;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class TestController extends JaseController
{
    use ModelForm;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Fast::content(function (Content $content) {
            $content->header('出售/购买管理');
            $content->body($this->grid());
        });
    }

    public function grid() {
        return new Grid(Fast::getModel(UserArea::class), function(Grid $grid){
            //$grid->model()->where(['is_del'=>0])->orderBy('sell_time', 'desc');
            $grid->column('id',  'ID')->display(function($id){
                echo $id;
            });
            $grid->disableActions();
            //$grid->disableBatchDeletion();
            $grid->disableExport();
            $grid->disableCreation();
            //$grid->disableRowSelector();
        });
    }

    public function store (Request $request)
    {
        $userId = $request->item['uid'];
    }
}
