<?php

namespace App\Http\Controllers\Api;

use App\Model\UserArea;
use App\Model\Area;
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

class UserAddrController extends JaseController
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

    public function edit($id) {
        response('', 200, ['Content-Type'=>'application/json']);
        return Fast::content(function (Content $content) use($id) {
            $content->body($this->grid($id)->render('object'));
        });
    }

    public function grid($id=0) {
        return Fast::grid(UserArea::class, function(Grid $grid) use ($id){
            $where = [];
            $id and $where['id'] = $id;
            $where['user_id'] = app('request')->item['uid'];
            $grid->model()->where($where)->orderBy('is_default', 'desc');
            $grid->column('id', 'id');
            $grid->column('user_id', 'user_id');
            $grid->column('real_name', 'real_name');
            $grid->column('mobile', 'mobile');
            $grid->column('detail', 'detail');
            $grid->column('province_id', 'province_id');
            $grid->column('city_id', 'city_id');
            $grid->column('area_id', 'area_id');
            $grid->column('is_default', 'is_default');
            
            $grid->province('province_name')->display(function($province){
                return $province['name'];
            });
            
            $grid->city()->name('city_name')->display(function($name){
                return $name;
            });
            
            $grid->area()->name('area_name')->display(function($name){
                return $name;
            });
            $grid->disableActions();
            $grid->disableBatchDeletion();
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

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Fast::form(UserArea::class, function (Form $form) {
            $form->model($form->model()->where(['user_id'=>app('request')->item['uid']]));
            $form->text('user_id', '用户ID')->rules('required')->default(app('request')->item['uid']);
            $form->text('is_default', '是否默认')->rules('required');
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
                        'status_code' => strval("401"),
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
