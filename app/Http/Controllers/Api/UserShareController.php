<?php

namespace App\Http\Controllers\Api;

use App\Model\UserShare;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ResultController as Result;

use App\Fcore\Facades\Fast;
use App\Fcore\Grid;
use App\Fcore\Form;
use App\Fcore\Layout\Content;
use App\Fcore\Controllers\ModelForm;

class UserShareController extends JaseController
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
        return Fast::grid(UserShare::class, function(Grid $grid) use ($id){
            $where = [];
            $id and $where['id'] = $id;
            $where['user_id'] = app('request')->item['uid'];
            $grid->model()->where($where);
            $grid->column('id', 'id');
            $grid->column('user_id', 'user_id');
            $grid->column('biz_type', 'biz_type');
            $grid->column('biz_id', 'biz_id');
            $grid->column('channel', 'channel');
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
        return Fast::form(UserShare::class, function (Form $form) {
            $request = app('request');
            $form->where(function($query) use($request) {
                $query->where(['user_id'=>$request->item['uid']]);
            });
            $form->text('user_id', '用户ID')->rules('required|integer')->default($request->item['uid']);
            $form->text('biz_type', '业务类型')->rules('required|integer|regex:/^[123]$/');
            $form->number('biz_id', '业务ID')->rules('required|integer|min:1');
            $form->number('channel', '渠道')->rules('required|integer|regex:/^[1243]$/');
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
                $userShare = UserShare::where(['user_id'=>$form->user_id, 'biz_type'=>$form->biz_type, 'biz_id'=>$form->biz_id, 'channel'=>$form->channel])->first();
                if($userShare) {
                    return response()->json([
                        'status'  => 'error',
                        'error' => [
                            'status_code' => strval("602"),
                            'message' => '已经分享'
                        ]
                    ]);
                }
            });
            $form->saved(function (Form $form) {
                $data = json_decode($this->grid($form->model()->id)->render('object'), true);
                return response()->json([
                    'status'  => 'success',
                    'status_code' => '200',
                    'object' => $data,
                    'message' => '感谢分享,获得3积分！'
                ]);
            });
        });
    }
}
