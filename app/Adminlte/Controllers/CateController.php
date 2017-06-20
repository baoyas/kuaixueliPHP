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

class CateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Admin::content(function (Content $content) {
            $content->header('分类管理');
            $content->body(Ctree::tree());
        });
        
        return Cate::tree(function (Tree $tree) {

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
        $pid = $request->get('pid');
        return view('admin.cate.add', compact('pid'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'cate_name' => 'required',
            'cate_sort' => 'required'
        ], [
            'cate_name.required' => '分类名称必填！',
            'cate_sort.required' => '分类排序必填！'
        ]);
        $input = Input::except('_token');
        $statues = Cate::create($input);
        if ($statues)
        {
            return redirect('admin/cate');
        }
        else
        {
            return back()->with('errors', '添加失败！');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $field = Cate::find($id);
        return view('admin.cate.edit', compact('field'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(request(), [
            'cate_name' => 'required',
            'cate_sort' => 'required'
        ], [
            'cate_name.required' => '分类名称必填！',
            'cate_sort.required' => '分类排序必填！'
        ]);
        $input = Input::except('_token', '_method');
        $statues = Cate::where('id', $id)->update($input);
        if ($statues)
        {
            return redirect('admin/cate');
        }
        else
        {
            return back()->with('errors', '编辑失败！');
        }
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
}
