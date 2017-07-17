<?php

namespace App\Http\Controllers\Api;

use App\Model\Sell;
use App\Model\User;
use App\Model\UserShare;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ResultController as Result;

use App\Fcore\Facades\Fast;
use App\Fcore\Grid;
use App\Fcore\Form;
use App\Fcore\Layout\Content;
use App\Fcore\Controllers\ModelForm;
use Cache;

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
            $grid->column('value', 'value');
            $grid->column('biz_desc', 'biz_desc')->display(function(){
                if($this->biz_type==1 || $this->biz_type==2 || $this->biz_type==3) {
                    return '分享获得';
                } else if($this->biz_type==4) {
                    return '分享了当了获得';
                } else if($this->biz_type==5) {
                    return '抽奖获得';
                } else {
                    return '';
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
        return Fast::form(UserShare::class, function (Form $form) {
            $request = app('request');
            $form->where(function($query) use($request) {
                $query->where(['user_id'=>$request->item['uid']]);
            });
            $form->text('user_id', '用户ID')->rules('required|integer')->default($request->item['uid']);
            $form->text('biz_type', '业务类型')->rules('required|integer|regex:/^[1234]$/');
            if($request->get('biz_type', NULL)!=4) {
                $form->number('biz_id', '业务ID')->rules('required|integer|min:1');
            }
            //$form->number('biz_id', '业务ID')->rules('required|integer|min:1|required_if:biz_type,1,2,3');
            $form->number('channel', '渠道')->rules('required|integer|regex:/^[13456]$/');
            //$form->text('user.id', '业务类型')->default(1);
            //$form->text('user.points', '业务类型')->default(1);
            //$form->text('user.address', '业务类型')->default(1);
            /*$form->hasMany('user', function (Form\NestedForm $form) {
                //echo "-----+++---";exit();
                //throw new \Exception('111', 111);
                $form->text('points')->rules('required');//->default(1);
            });*/
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
                if($form->biz_type!=4) {
                    $sell = Sell::find($form->biz_id);
                    if(empty($sell)) {
                        return response()->json([
                            'status'  => 'error',
                            'error' => [
                                'status_code' => strval("601"),
                                'message' => '要分享的不存在'
                            ]
                        ]);
                    }
                }
            });
            $form->saved(function (Form $form) {
                $count = UserShare::where(['user_id'=>$form->user_id, 'biz_type'=>$form->biz_type, 'biz_id'=>$form->biz_id, 'channel'=>$form->channel])->count();
                if($count>=2) {
                    $data = json_decode($this->grid($form->model()->id)->render('object'), true);
                    return response()->json([
                        'status'  => 'success',
                        'status_code' => '200',
                        'object' => $data,
                        'message' => '感谢分享！'
                    ]);
                } else {
                    //User::where(['id'=>$form->user_id])->increment('points', config('web.SHARE_POINTS'));
                    $points = 0;
                    if($form->biz_type==1 || $form->biz_type==2) {
                        $sell = Sell::find($form->biz_id);
                        if($sell->recommend==1) {
                            $points = config('web.SHARE_RECOMMEND_SELL_POINTS');
                            User::addPoints($form->user_id, $points);
                        } else {
                            $points = config('web.SHARE_SELL_POINTS');
                            User::addPoints($form->user_id, $points);
                        }
                        $date = date('Y-m-d');
                        $cacheKey = "user_money_day_{$date}_times_{$form->user_id}";
                        $moneyDayTimes = Cache::get($cacheKey);
                        if($moneyDayTimes <= 5) {
                            User::addMoney($form->user_id, mt_rand(1, 100), '1');
                            Cache::increment($cacheKey, 1);
                        }
                    } else {
                        $points = config('web.SHARE_POINTS');
                        User::addPoints($form->user_id, $points);
                    }
                    $data = json_decode($this->grid($form->model()->id)->render('object'), true);
                    return response()->json([
                        'status'  => 'success',
                        'status_code' => '200',
                        'object' => $data,
                        'message' => "感谢分享,获得{$points}积分！"
                    ]);
                }
            });
        });
    }
}
