<?php

namespace App\Adminlte\Controllers;

use App\Model\Ad;
use App\Model\Education;
use App\Model\EducationLevel;
use App\Model\EducationSchool;
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
            $content->body($this->form()->edit($id));
        });
    }

    public function grid($id=0) {
        return Admin::grid(Education::class, function(Grid $grid) use ($id){
            $where = [];
            $id and $where['id'] = $id;
            $grid->model()->where($where);
            $grid->column('id', 'id');
            $grid->column('name', '学历名称');
            $grid->column('school.name', '学校名称')->display(function($school_name){
                return $school_name;
            });
            $grid->column('level.name', '学历级别')->display(function($level_name){
                return $level_name;
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
    public function form()
    {
        return Admin::form(Education::class, function (Form $form) {
            $form->display('id', '学历ID');
            $form->text('name', '学历名称')->rules('required');
            $form->select('school_id', '学历级别')->options(
                EducationSchool::all()->pluck('name', 'id')
            );
            $form->select('level_id', '学历级别')->options(
                EducationLevel::all()->pluck('name', 'id')
            );
            $form->select('studymode_id', '进修方式')->options(
                Education::$studyMode
            );
            $fullTimeStates = [
                'on'  => ['value' => 1, 'text' => '是', 'color' => 'success'],
                'off' => ['value' => 2, 'text' => '否', 'color' => 'danger'],
            ];
            $form->switch('fulltime_id', '是否全日制')->states($fullTimeStates);
            $form->radio('notfulltime_id', '脱产')->options([
                1 => '全日制脱产',
                2 => '周末脱产'
            ]);
            $form->select('length', '学制')->options([
                '1' => '1年',
                '1.5' => '1.5年',
                '2' => '2年',
                '2.5' => '2.5年',
                '3' => '3年',
                '4' => '4年',
                '5' => '5年',
            ]);
        });
    }
}

