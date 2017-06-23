<?php

namespace App\Adminlte\Controllers;

use App\Model\Ctree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Encore\Admin\Form;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Tree;
use Encore\Admin\Grid\Displayers\Editable;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Row;
use Encore\Admin\Layout\Column;
use Encore\Admin\Widgets\Box;

class CateController extends Controller
{
    use ModelForm;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$request['abc'] = 'acb';
        //print_r(Input::all());exit();
        Admin::script('$(document).ready(function(){
            $("a[data-action=collapse]").click();
        });');
        //$request['id'] = '1';
        $grid = Admin::grid(Ctree::class ,function(Grid $grid){
            $grid->disablePagination();
            $grid->column('id');
            $grid->cate_power()->editable();
            $grid->cate_sort()->editable();
        });
        $grid->build();
        $gridHtml = [];
        foreach($grid->rows() as $row) {
            $gridHtml[$row->id] = $row->column('cate_sort');
        }
        return Admin::content(function (Content $content) use($gridHtml) {
            $content->header('分类管理');
            $content->body(Ctree::tree(function (Tree $tree) use($gridHtml) {
                //$tree->disableCreate();
                $tree->query(function($query){
                    return $query->where('cate_level','<=', 10);
                });
                $tree->branch(function ($branch) use ($gridHtml){
                    $html = empty($gridHtml[$branch['id']]) ? '' : $gridHtml[$branch['id']];
                    $payload = '<span style="float:left;">'."{$branch['id']} - {$branch['cate_name']}".'</span>'.
                    '<span class="dd-nodrag" style="position:absolute;right:100px;">排序：'.$html.'</span>';
                    if($branch['level']<=1) {
                        $payload .= '<span class="dd-nodrag" style="position:absolute;right:46px;"><a href="'.admin_url('cate/create').'?pid='.$branch['id'].'"><i class="fa fa-plus-circle"></i></a></span>';
                    }
                    return $payload;
                });
            }));
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return Admin::content(function (Content $content) {
            $content->header('分类创建');
            $content->description(trans('admin::lang.create'));
            $content->body($this->form());
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header(trans('admin::lang.administrator'));
            $content->description(trans('admin::lang.edit'));
            $content->body($this->form()->edit($id));
        });
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $cate = Cate::where('id', $id)->first();
        if ($cate)
        {
            $next_cate = Cate::where('pid', $id)->first();
            if ($next_cate)
            {
                $data = [
                    'status' => 1,
                    'msg' => '删除失败，请先删除其下的分类，然后再进行删除！'
                ];
            }
            else
            {
                $statues = Cate::where('id', $id)->delete();
                if ($statues)
                {
                    $data = [
                        'status' => 0,
                        'msg' => '删除成功！'
                    ];
                }
                else
                {
                    $data = [
                        'status' => 1,
                        'msg' => '删除失败，请稍后重试！'
                    ];
                }
            }
        }
        else
        {
            $data = [
                'status' => 1,
                'msg' => '删除失败，没有查找到该分类！'
            ];
        }
        return $data;
    }

    public function changeorder ()
    {
        $input = Input::all();
        $cate = Cate::find($input['id']);
        $cate->cate_sort = $input['conf_order'];
        $re = $cate->update();
        if ($re)
        {
            $data = [
                'status' => 0,
                'msg' => '排序更新成功！'
            ];
        }
        else
        {
            $data = [
                'status' => 1,
                'msg' => '排序更新失败，请稍后重试！'
            ];
        }
        return $data;
    }

    public function upDown ()
    {
        $input = Input::except('_token');
        $msg = ($input['power'] == 0)?'禁用':'开启';
        $cate = Cate::where('id', $input['id'])->first();
        $cate->cate_power = $input['power'];
        $re = $cate->update();
        $child = Cate::where('pid', $cate->id)->get();
        $child_id = [];
        foreach ($child as $k=>$v)
        {
            $child_id[] = $v->id;
        }
        if ($re)
        {
            $save = [
                'cate_power' => $input['power']
            ];
            Cate::where('pid', $cate->id)->update($save);
            Cate::whereIn('pid', $child_id)->update($save);
            $data = [
                'status' => 0,
                'msg' => '分类' . $msg . '成功！'
            ];
        }
        else
        {
            $data = [
                'status' => 1,
                'msg' => '分类' . $msg . '失败！'
            ];
        }
        return $data;
    }
    
    /**
     * Make a form builder.
     *
     * @return Form
     */
public function form()
    {
        return Admin::form(Ctree::class, function (Form $form) {
            $request = app('request');
            $all = $request->all();

            if($request->isMethod('POST')) {
                $form->text('cate_name', '名称')->rules('required');
            } elseif ($request->isMethod('PUT')) {
                if(isset($all['cate_name'])) {
                    $form->text('cate_name', '名称')->rules('required');
                } else {
                    $form->ignore('cate_name');
                }
            } else {
                $form->text('cate_name', '名称')->rules('required');
            }

            if(strrchr($request->path(),"edit") == 'edit') {
                //$form->display('piddesc', '上级品类')->default('（根分类---）');
                //$form->hidden('pid')->default(0);
            } elseif(strrchr($request->path(),"create") == 'create') {
                if(is_null(Input::get('pid'))) {
                    $form->display('pid', '上级品类')->default('（根分类）');
                    $form->hidden('pid', '上级品类')->default(0);
                } else {
                    $ctree = Ctree::find(Input::get('pid'));
                    $form->display('pid', '上级品类')->default($ctree->cate_name);
                    $form->hidden('pid', '上级品类')->default($ctree->id);
                }
            }

            $states = [
                'on'  => ['value' => 1, 'text' => '是', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '否', 'color' => 'danger'],
            ];
            $form->switch('cate_power', '是否启用')->states($states);

            $form->text('cate_sort', '排序');
            $form->saving(function (Form $form) {

            });
        });
    }
}
