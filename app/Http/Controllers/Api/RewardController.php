<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Model\User;
use App\Model\RewardConf;
use App\Model\UserReward;
use App\Model\UserShare;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ResultController as Result;

use App\Fcore\Facades\Fast;
use App\Fcore\Grid;
use App\Fcore\Form;
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
        $user_id = app('request')->item['uid'];
        $sTime = date('Y-m-d');
        $eTime = date('Y-m-d', strtotime($sTime)+3600*24);
        $count = UserReward::where('user_id', $user_id)->whereBetween('created_at', [$sTime, $eTime])->groupBy(DB::raw('substring(created_at,1,10)'))->count();
        $points = User::find($user_id)->points;
        $points = empty($points) ? 0 : $points;
        return $this->result->responses([
            'status' => 'success',
            'status_code' => '200',
            'object' => [
                'list' => $this->grid()->getFormatData(),
                'points'=>$points,
                'use_points' => 20,
                'can_use_count' => 10-$count
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
            $where['is_del'] = 0;
            $id and $where['id'] = $id;
            $grid->model()->where($where)->orderBy('sort', 'asc');
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
        return Fast::form(UserReward::class, function (Form $form) {
            $reward = RewardConf::where('is_del', 0)->get()->toArray();
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
            $request = app('request');
            $form->where(function($query) use($request) {
                $query->where(['user_id'=>$request->item['uid']]);
            });
            $form->text('user_id', '用户ID')->rules('required|integer')->default($request->item['uid']);
            $form->text('reward_id', '奖品ID')->rules('required|integer')->default($rew['id']);
            $form->text('rname', '奖品名称')->rules('required|string')->default($rew['rname']);
            $form->text('img_uri', '奖品图片')->rules('string')->default($rew['img_uri']);
            $form->text('weight', '奖品权重')->rules('required|integer')->default($rew['weight']);
            $form->text('type', '奖品类型')->rules('required|integer')->default($rew['type']);
            $form->text('value', '奖品数量')->rules('required|integer')->default($rew['value']);

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
                $sTime = date('Y-m-d');
                $eTime = date('Y-m-d', strtotime($sTime)+3600*24);
                $count = UserReward::where('user_id', $form->user_id)->whereBetween('created_at', [$sTime, $eTime])->groupBy(DB::raw('substring(created_at,1,10)'))->count();
                if($count>=10) {
                    return response()->json([
                        'status'  => 'error',
                        'error' => [
                            'status_code' => strval("604"),
                            'message' => '抽奖次数已用完'
                        ]
                    ]);
                }
                if(User::find($form->user_id)->points < 20) {
                    return response()->json([
                        'status'  => 'error',
                        'error' => [
                            'status_code' => strval("605"),
                            'message' => '积分不足'
                        ]
                    ]);
                }
            });
            $form->saved(function (Form $form) {
                if($form->type == 1) {
                    User::addMoney($form->user_id, $form->value, 2);
                } elseif($form->type == 4) {
                    User::find($form->user_id)->increment('points', $form->value);
                    UserShare::create(['user_id'=>$form->user_id, 'biz_type'=>5, value=>$form->value]);
                }
                User::where('id', $form->user_id)->decrement('points', 20);
                return response()->json([
                    'status'  => 'success',
                    'status_code' => '200',
                    'object' => $form->model()->toArray()
                ]);
            });
        });
    }
}
