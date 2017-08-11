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


class SchoolController extends Controller
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('学校管理');
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
            $content->header('学校管理');
            $content->description(trans('admin::lang.create'));
            $content->body($this->form());
        });
    }

    public function edit($id) {
        return Admin::content(function (Content $content) use ($id){
            $content->header('学校管理');
            $content->description(trans('admin::lang.edit'));
            $content->body($this->form($id)->edit($id));
        });
    }

    public function grid($id=0) {
        return Admin::grid(EducationSchool::class, function(Grid $grid) use ($id){
            $where = [];
            $id and $where['id'] = $id;
            $grid->model()->where($where);
            $grid->column('id', 'id');
            $grid->column('name', '学校名称');
            $grid->column('province.name', '所在省份')->display(function($province_name){
                return $province_name;
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
        return Admin::form(EducationSchool::class, function (Form $form) use($id) {
            $form->display('id', '学历ID');
            $form->text('name', '学校名称')->rules('required');
            $form->select('province_id', '所在省份')->options(
                Province::all()->pluck('name', 'id')
            );
            $form->image('logo_url', '学校logo')->dir(function(Form $form){
                return 'school';
            });//->default('');
            $form->saving(function(Form $form){
                $form->logo_url = $form->logo_url ? $form->logo_url : '';
            });
        });
    }

    public function levelNext(Request $request)
    {
        $pid = $request->get('q');
        return EducationLevel::where(['pid'=>$pid])->get(['id', DB::raw('name as text')]);
    }
}

