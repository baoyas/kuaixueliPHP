<?php

namespace App\Http\Controllers\Api;

use App\Model\Sell;
use App\Model\User;
use App\Model\UserMoney;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ResultController as Result;

use App\Fcore\Facades\Fast;
use App\Fcore\Grid;
use App\Fcore\Form;
use App\Fcore\Layout\Content;
use App\Fcore\Controllers\ModelForm;
use Cache;

class UserMoneyController extends JaseController
{
    use ModelForm;
    private $result;
    public function __construct (Request $request)
    {
        $this->result = new Result();
    }

    public function index()
    {
        return Fast::content(function (Content $content) {
            $content->body($this->grid());
        });
    }

    public function grid($id=0) {
        return Fast::grid(UserMoney::class, function(Grid $grid) use ($id){
            $where = [];
            $id and $where['id'] = $id;
            $where['user_id'] = app('request')->item['uid'];
            $grid->model()->where($where)->orderBy('id', 'desc');
            $grid->column('id', 'id');
            $grid->column('user_id', 'user_id');
            $grid->column('biz_type', 'biz_type');
            $grid->column('biz_desc', 'biz_desc')->display(function(){
                if($this->biz_type == '1') {
                    return '分享获得';
                } elseif($this->biz_type == '2') {
                    return '抽奖获得';
                }
            });
            $grid->column('flow_type', 'flow_type');
            $grid->column('value', 'value');
            $grid->column('created_at', 'created_at');
            $grid->disableActions();
            $grid->disableBatchDeletion();
            $grid->disableCreation();
            $grid->disableRowSelector();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        
    }
}
