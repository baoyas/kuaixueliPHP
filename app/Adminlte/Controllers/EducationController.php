<?php

namespace App\Adminlte\Controllers;

use DB;
use App\Model\Ad;
use App\Model\Education;
use App\Model\EducationLevel;
use App\Model\EducationSchool;
use App\Model\EducationProvince;
use App\Model\Province;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Auth\Database\Administrator;


class EducationController extends Controller
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('学历管理');
            $content->body($this->grid());
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('学历管理');
            $content->description(trans('admin::lang.create'));
            $content->body($this->form());
        });
    }

    public function edit($id) {
        return Admin::content(function (Content $content) use ($id){
            $content->header('学历管理');
            $content->description(trans('admin::lang.edit'));
            $content->body($this->form($id)->edit($id));
        });
    }

    public function grid($id=0) {
        $eLevel = EducationLevel::all()->pluck('name', 'id');
        return Admin::grid(Education::class, function(Grid $grid) use ($id, $eLevel){
            $where = [];
            $id and $where['id'] = $id;
            $grid->model()->where($where);
            $grid->column('id', 'id');
            $grid->column('name', '学历名称');
            $grid->column('school.name', '学校名称')->display(function($school_name){
                return $school_name;
            });
            $grid->column('level', '学历级别')->display(function() use($eLevel) {
                return $eLevel[$this->level_1_id]."-".$eLevel[$this->level_2_id]."-".$eLevel[$this->level_3_id];
            });
            $grid->column('studymode_id', '进修方式')->display(function($studymode_id){
                return isset(Education::$studyMode[$studymode_id]) ? Education::$studyMode[$studymode_id] : '';
            });
            $grid->column('fulltime_id', '是否全日制')->display(function($fulltime_id){
                return isset(Education::$fullTime[$fulltime_id]) ? Education::$fullTime[$fulltime_id] : '';
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form($id='')
    {
        return Admin::form(Education::class, function (Form $form) use($id) {
            $form->display('id', '学历ID');
            $form->text('name', '学历名称')->rules('required');
            $form->select('school_id', '学校名称')->options(
                EducationSchool::all()->pluck('name', 'id')
            );
            $form->select('level_1_id', '学历级别1')->options(
                EducationLevel::where(['pid'=>0])->get()->pluck('name', 'id')
            )->load('level_2_id', '/adminlte/education/level2')->default(0);
            $form->select('level_2_id', '学历级别2')->options(function ($id) {
                return EducationLevel::options($id);
            })->load('level_3_id', '/adminlte/education/level3')->default(0);
            $form->select('level_3_id', '学历级别3')->options(function ($id) {
                return EducationLevel::options($id);
            })->default(0);

            $form->select('studymode_id', '进修方式')->options(
                Education::$studyMode
            )->default(0);

            $fullTimeStates = [
                'on'  => ['value' => 1, 'text' => '是', 'color' => 'success'],
                'off' => ['value' => 2, 'text' => '否', 'color' => 'danger'],
            ];
            $form->switch('fulltime_id', '是否全日制')->states($fullTimeStates)->tshow('notfulltime_id', 'on')->default(0);
            $form->radio('notfulltime_id', '脱产')->options([
                1 => '全日制脱产',
                2 => '周末脱产'
            ])->default(0);
            $form->select('length', '学制')->options([
                '1' => '1年',
                '1.5' => '1.5年',
                '2' => '2年',
                '2.5' => '2.5年',
                '3' => '3年',
                '4' => '4年',
                '5' => '5年',
            ]);
            $form->multipleSelect('provinces', '户籍限制')->options(Province::all()->pluck('name', 'id'))->default(
                EducationProvince::where('education_id', $id)->get()->pluck('id', 'id')
            );
            $form->text('province_desc', '户籍限制说明')->default('');
            $form->text('major', '可选专业')->default('');
            $form->hasMany('contacts', '课程顾问',function (Form\NestedForm $form) {
                $atypeStates = [
                    'on'  => ['value' => 1, 'text' => 'QQ', 'color' => 'success'],
                    'off' => ['value' => 2, 'text' => '微信', 'color' => 'danger'],
                ];
                $form->switch('atype', '类型')->states($atypeStates);
                $form->text('account', '账号');
            });
            $form->checkbox('coaches', '学位辅导')->options([
                1 => '论文辅导',
                2 => '计算机',
                3 => '英语',
            ]);
            $form->text('entry_fee', '报名费')->rules('required');
            $form->text('market_fee', '官方学费')->rules('required');
            $form->text('kxl_fee', '快学历学费')->rules('required');
            $form->saving(function(Form $form){
                $form->level_1_id  = $form->level_1_id ? $form->level_1_id : 0;
                $form->level_2_id  = $form->level_2_id ? $form->level_2_id : 0;
                $form->level_3_id  = $form->level_3_id ? $form->level_3_id : 0;
                $form->fulltime_id = $form->fulltime_id ? $form->fulltime_id : 0;
            });
        });
    }

    public function level2(Request $request)
    {
        $pid = $request->get('q');
        if(empty($pid)) {
            return [];
        }
        $data = EducationLevel::where(['pid'=>$pid])->get(['id', DB::raw('name as text')])->toArray();
        //array_unshift($data,['id'=>0, 'text'=>'请选择']);
        return $data;
    }

    public function level3(Request $request)
    {
        $pid = $request->get('q');
        if(empty($pid)) {
            return [];
        }
        $data = EducationLevel::where(['pid'=>$pid])->get(['id', DB::raw('name as text')])->toArray();
        //array_unshift($data,['id'=>0, 'text'=>'请选择']);
        return $data;
    }
}

