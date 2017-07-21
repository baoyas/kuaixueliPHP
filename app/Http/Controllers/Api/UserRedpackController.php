<?php

namespace App\Http\Controllers\Api;

use App\Model\User;
use App\Model\UserMoney;
use App\Model\UserRedpack;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ResultController as Result;

use App\Fcore\Facades\Fast;
use App\Fcore\Grid;
use App\Fcore\Form;
use App\Fcore\Layout\Content;
use App\Fcore\Controllers\ModelForm;
use DB;
use Cache;

class UserRedpackController extends JaseController
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
        return Fast::grid(UserRedpack::class, function(Grid $grid) use ($id){
            $where = [];
            $id and $where['id'] = $id;
            $where['user_id'] = app('request')->item['uid'];
            $grid->model()->where($where)->orderBy('id', 'desc');
            $grid->column('id', 'id');
            $grid->column('user_id', 'user_id');
            $grid->column('value', 'value');
            $grid->column('biz_type', 'biz_type');
            $grid->column('biz_desc', 'biz_desc')->display(function(){
                if($this->biz_type == '1') {
                    return '分享获得';
                } elseif($this->biz_type == '2') {
                    return '抽奖获得';
                }elseif($this->biz_type == '3') {
                    return '邀请获得';
                }
            });
            $grid->column('status', 'status');
            $grid->column('status_cn', 'status_cn')->display(function(){
                if($this->status == '0') {
                    return '未领取';
                } elseif($this->status == '1') {
                    return '已领取';
                }
            });
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
        return Fast::form(UserRedpack::class, function (Form $form) {
            $request = app('request');
            $form->where(function($query) use($request) {
                $query->where(['user_id'=>$request->item['uid']]);
            });
            $form->text('user_id', '用户ID')->rules('required|integer')->default($request->item['uid']);
            $form->text('value',   '红包金额')->rules('required|integer');
            $form->text('status',  '状态')->rules('required|integer|regex:/^[01]$/')->default(1);
            $form->error(function (Form $form) {
                return response()->json([
                    'status'  => 'error',
                    'error' => [
                        'status_code' => strval("601"),
                        'message' => $form->getValidator()->messages()->first()
                    ]
                ]);
            });
            $form->saving(function (Form $form) {

            });
            $form->saved(function (Form $form) {
                $data = json_decode($this->grid($form->model()->id)->render('object'), true);
                User::find($form->user_id)->increment('money', $form->value);
                UserMoney::create(['user_id'=>$form->user_id, 'biz_type'=>$data['biz_type'], 'flow_type'=>1, 'value'=>$form->value]);
                return response()->json([
                    'status'  => 'success',
                    'status_code' => '200',
                    'object' => $data,
                    'message' => "红包领取成功"
                ]);
            });
        });
    }
}
