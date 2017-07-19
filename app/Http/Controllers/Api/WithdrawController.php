<?php

namespace App\Http\Controllers\Api;

use App\Model\Sell;
use App\Model\User;
use App\Model\UserWithdraw;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ResultController as Result;

use App\Fcore\Facades\Fast;
use App\Fcore\Grid;
use App\Fcore\Form;
use App\Fcore\Layout\Content;
use App\Fcore\Controllers\ModelForm;
use DB;
use Cache;

class WithdrawController extends JaseController
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
        return Fast::grid(UserWithdraw::class, function(Grid $grid) use ($id){
            $where = [];
            $id and $where['id'] = $id;
            $where['user_id'] = app('request')->item['uid'];
            $grid->model()->where($where)->orderBy('id', 'desc');
            $grid->column('id', 'id');
            $grid->column('user_id', 'user_id');
            $grid->column('value', 'value');
            $grid->column('status_cn', 'status_cn')->display(function(){
                if($this->status == '0') {
                    return '带审核';
                } elseif($this->status == '2') {
                    return '成功';
                } elseif($this->status == '2') {
                    return '拒绝';
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
        return Fast::form(UserWithdraw::class, function (Form $form) {
            $request = app('request');
            $form->where(function($query) use($request) {
                $query->where(['user_id'=>$request->item['uid']]);
            });
            $form->text('user_id', '用户ID')->rules('required|integer')->default($request->item['uid']);
            $form->text('value',  '提现金额')->rules('required|integer');
            $form->text('status',  '状态')->rules('required|integer|regex:/^[012]$/')->default(0);
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
                $user = User::find($form->user_id);
                if($user->money < 1000) {
                    return response()->json([
                        'status'  => 'error',
                        'error' => [
                            'status_code' => strval("601"),
                            'message' => '资金10元以上才可以提现'
                        ]
                    ]);
                } elseif($user->money - $this->value < 0) {
                    return response()->json([
                        'status'  => 'error',
                        'error' => [
                            'status_code' => strval("601"),
                            'message' => '提现金额大于余额'
                        ]
                    ]);
                }
            });
            $form->saved(function (Form $form) {
                $sTime = date('Y-m-d');
                $eTime = date('Y-m-d', strtotime($sTime)+3600*24);
                $count = UserWithdraw::where(['user_id'=>$form->user_id])->whereBetween('created_at', [$sTime, $eTime])->groupBy(DB::raw('substring(created_at,1,10)'))->count();
                if($count>=2) {

                }
                User::find($form->user_id)->decrement('money', $form->value);
                $data = json_decode($this->grid($form->model()->id)->render('object'), true);
                return response()->json([
                    'status'  => 'success',
                    'status_code' => '200',
                    'object' => $data,
                    'message' => "提现申请成功"
                ]);
            });
        });
    }
}
