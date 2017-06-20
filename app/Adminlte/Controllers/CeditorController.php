<?php

namespace App\Adminlte\Controllers;

use App\Model\Ad;
use App\Model\Content as ContentText;
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

class CeditorController extends Controller
{
    use ModelForm;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('内容管理');
            $content->description('列表');

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
            $content->header('内容创建');
            $content->description(trans('admin::lang.create'));
            $content->body($this->form());
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::except('_token');
        $rules = [
            'richtext' => 'required'
        ];
        $message = [
            'richtext.required' => '内容不能为空！'
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->passes())
        {
            $re = ContentText::create($input);
            if ($re)
            {
                return redirect('adminlte/ceditor');
            }
            else
            {
                return back()->with('errors', '添加失败！');
            }
        }
        else
        {
            return back()->withErrors($validator);
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
        //$file = ContentText::where('id', $id)->first();
        //return view('admin.ceditor.edit', compact('file'));
        return Admin::content(function (Content $content) use ($id) {
            $content->header(trans('admin::lang.administrator'));
            $content->description(trans('admin::lang.edit'));
            $content->body($this->form()->edit($id));
        });
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
        $input = Input::except('_token', '_method');
        $data = ContentText::where('id', $id)->first();
        $rules = [
            'richtext' => 'required'
        ];
        $message = [
            'richtext.required' => '内容不能为空！'
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->passes())
        {
            $data->richtext = $input['richtext'];
            $status = $data->update();
            if ($status)
            {
                return redirect('adminlte/ceditor');
            }
            else
            {
                return back()->with('errors', '编辑失败！');
            }
        }
        else
        {
            return back()->withErrors($validator);
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
        $adskip = Adskip::where('id', $id)->first();
        $ad = Ad::where('ad_skip_id', $id)->first();
        if ($ad)
        {
            $data = [
                'status' => 1,
                'msg' => '广告中已使用此跳转方式不可删除！'
            ];
        }
        else
        {
            if ($adskip)
            {
                if ($adskip->ad_skip_power)
                {
                    $data = [
                        'status' => 1,
                        'msg' => '系统定义的跳转方式不可删除！'
                    ];
                }
                else
                {
                    $status = Adskip::where('id', $id)->delete();
                    if ($status)
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
                            'msg' => '删除失败！'
                        ];
                    }
                }
            }
            else
            {
                $data = [
                    'status' => 1,
                    'msg' => '参数错误！'
                ];
            }
        }
        return $data;
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        //Admin::script('$(document).ready(function(){$("#pjax-container").attr("id", "")});');
        return Admin::grid(ContentText::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->column('jumpUrl', '跳转地址')->display(function () {
                return url('content/'.$this->id);
            });

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableEdit();
                $actions->prepend('<a onclick=javascript:{location.href="/adminlte/ceditor/'.$this->getKey().'/edit"}>编辑</a>');
                //$actions->append('<a data-token="'.csrf_token().'" onclick="upDown('.$this->getKey().','.$this->row->statues.')">'.($this->row->statues==0?'禁用':'开启').'</a>');

            });

            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });

            $grid->disableExport();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return ContentText::form(function (Form $form) {
            $form->display('id', 'ID');
            $form->ueditor('richtext', '内容');
            $form->hidden('richtext');
            $form->disableReset();
            $form->disableSubmit();
            $script = <<<EOT
    $('[name=richtext]').val(UE.getEditor('richtext').getAllHtml());
    $('[name=richtext]').closest('form').submit();
EOT;
            $form->button('提交')->on('click', $script);
        });
    }
}
