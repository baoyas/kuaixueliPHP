<?php

namespace App\Http\Controllers\Api;

use App\Model\UserArea;
use App\Model\RewardConf;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResultController as Result;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

use App\Fcore\Facades\Fast;
use App\Fcore\Grid;
use App\Fcore\Form;
use App\Fcore\Layout\Content;
use App\Fcore\Controllers\ModelForm;

class RewardController extends JaseController
{
    use ModelForm;
    private $result;
    public function __construct (Request $request)
    {
        $this->result = new Result();
    }

    public function index()
    {

        return $this->result->responses([
            'status' => 'success',
            'status_code' => '200',
            'object' => [
                'list' => $this->grid()->getFormatData(),
                'points' => 20
            ]
        ]);
    }

    public function edit($id) {
        response('', 200, ['Content-Type'=>'application/json']);
        return $this->grid($id)->render('object');
    }

    public function grid($id=0) {
        return Fast::grid(RewardConf::class, function(Grid $grid) use ($id){
            $where = [];
            $id and $where['id'] = $id;
            $grid->model()->where($where)->orderBy('id', 'asc');
            $grid->column('id', 'id');
            $grid->column('rname', 'rname')->display(function($rname){
                return html_entity_decode($rname);
            });
            $grid->column('type', 'type');
            $grid->column('img_uri', 'img_uri');

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
        return Fast::form(UserArea::class, function (Form $form) {
            $request = app('request');
            $form->where(function($query) use($request) {
                $query->where(['user_id'=>$request->item['uid']]);
            });
            $form->text('user_id', '用户ID')->rules('required|integer')->default($request->item['uid']);
            $form->text('is_default', '是否默认')->rules('required|integer|regex:/^[01]$/');
            $form->number('province_id', '省份ID')->rules('required|integer|min:1');
            $form->number('city_id', '城市ID')->rules('required|integer|min:1');
            $form->number('area_id', '区域ID')->rules('required|integer|min:1');
            $form->text('detail', '详细地址')->rules('required');
            $form->text('real_name', '收件人姓名')->rules('required|min:2|max:10');
            $form->text('mobile', '收件人手机号')->rules('required|regex:/^1[34578]\d{9}$/');
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
                //$request = app('request');
                //$form->model()->user_id = $request->item['uid'];
                //$form->user_id = $request->item['uid'];
            });
            $form->saved(function (Form $form) {
                if($form->is_default==1) {
                    UserArea::where('id', '!=', $form->model()->id)->update(['is_default'=>0]);
                }
                $data = json_decode($this->grid($form->model()->id)->render('object'), true);
                return response()->json([
                    'status'  => 'success',
                    'status_code' => '200',
                    'object' => $data
                ]);
            });
        });
    }
}
