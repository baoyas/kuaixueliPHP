<?php

namespace App\Http\Controllers\Admin;

use App\Model\Ad;
use App\Model\Content;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class CeditorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Content::orderBy('id', 'asc')->paginate(Config::get('web.admin_page'));
        return view('admin.ceditor.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.ceditor.add');
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
            $re = Content::create($input);
            if ($re)
            {
                return redirect('admin/ceditor');
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
        $file = Content::where('id', $id)->first();
        return view('admin.ceditor.edit', compact('file'));
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
        $data = Content::where('id', $id)->first();
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
                return redirect('admin/ceditor');
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
}
