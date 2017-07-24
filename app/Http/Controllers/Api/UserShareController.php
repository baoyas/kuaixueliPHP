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

    public function ldl(Request $request) {
        $user_id = $request->item['uid'];
        $user = User::find($user_id);
        return $this->response([
            'accounts'=>strval($user->accounts), 
            'invite_max_count_day'=>'200',
            'invite_current_count_day'=>'3',
            'invite_income_money_day'=>'400',
        ]);
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
                    return '分享快学历获得';
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
                        if($sell->recommend==1 && ($sell->is_sell==1||$sell->is_sell==2)) {
                            $date = date('Y-m-d');
                            $cacheKey = "user_money_day_{$date}_times_{$form->user_id}";
                            $moneyDayTimes = Cache::get($cacheKey);
                            if($moneyDayTimes <= 5) {
                                /*（0.01元­­­—1元）。此种方式每天最多获得红包5个，之后可以继续分享，但是不得红包。
                                这里
                                0.01至0.10占比60% ，
                                0.11至0.60占比30%，
                                0.61至0.80占比4%，
                                0.81至1.00占比1%*/
                                $reward = [
                                    ['weight'=>60, 'min'=>1, 'max'=>10],
                                    ['weight'=>35, 'min'=>11, 'max'=>60],
                                    ['weight'=>4, 'min'=>61, 'max'=>80],
                                    ['weight'=>1, 'min'=>81, 'max'=>100]
                                ];
                                /****准备奖励--start--**/
                                $rew = [];
                                if($reward) {
                                    $min = 0;
                                    $max = 0;
                                    foreach($reward as $key=>$val) {
                                        if(empty($val['weight']) || intval($val['weight'])<0) {
                                            continue;
                                        }
                                        $max = $max+$val['weight'];
                                    }

                                    if($max>0) {
                                        $rand_num = mt_rand(1, $max);
                                        $bonusIndex = '';

                                        foreach ($reward as $key=>$val) {
                                            if(empty($val['weight']) || intval($val['weight'])<0) {
                                                continue;
                                            }
                                            if ($rand_num > $min && $rand_num <= $min+$val['weight']) {
                                                $bonusIndex = $key;
                                                break;
                                            }
                                            $min = $min+$val['weight'];
                                        }
                                        if($bonusIndex!=='') {
                                            $rew = $reward[$bonusIndex];
                                        }
                                    }
                                }
                                /****准备奖励--end--**/
                                User::addMoney($form->user_id, mt_rand($rew['min'], $rew['max']), '1');
                                Cache::increment($cacheKey, 1);
                            }
                            $points = config('web.SHARE_RECOMMEND_SELL_POINTS');
                            User::addPoints($form->user_id, $points);
                        } else {
                            $points = config('web.SHARE_SELL_POINTS');
                            User::addPoints($form->user_id, $points);
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
