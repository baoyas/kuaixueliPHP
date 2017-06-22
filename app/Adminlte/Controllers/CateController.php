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
                $tree->branch(function ($branch) use ($gridHtml){
                    $html = empty($gridHtml[$branch['id']]) ? '' : $gridHtml[$branch['id']];
                    return '<span style="float:left;">'."{$branch['id']} - {$branch['cate_name']}".'</span>'.
                           '<span class="dd-nodrag" style="position:absolute;right:120px;">排序：'.$html.'</span>';
                });
            }));
        });
        
        return Ctree::tree(function (Tree $tree) {

            $tree->branch(function ($branch) {

                return "{$branch['id']} - {$branch['title']}";

            });

        });
        $p = $request->get('p') ? $request->get('p') : 1 ;
        $data = (new Cate)->tree();
//        $data = collect($data)->forPage($p,20);
//        dd($data);
        return view('admin.cate.index', compact('data'));
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
            $form->text('cate_name', '名称');
            if(Input::get('pid')) {
                $form->hidden('pid', '父id')->default(Input::get('pid', 0));
            } else {
                $form->display('pid', '父id')->default(0);
            }
            $form->text('cate_sort', '排序');//->rules('required');
            $form->saving(function (Form $form) {

            });
        });
    }
}
