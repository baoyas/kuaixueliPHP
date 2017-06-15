<?php

namespace App\Http\Controllers\Admin;

use App\Model\Ad;
use App\Model\Adskip;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class AdskipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Adskip::orderBy('id', 'asc')->paginate(Config::get('web.admin_page'));
        return view('admin.adskip.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.adskip.add');
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
            'ad_skip_name' => 'required',
            'ad_skip_describe' => 'required'
        ];
        $message = [
            'ad_skip_name.required' => '名称不能为空！',
            'ad_skip_describe.required' => '跳转方式不能为空！'
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->passes())
        {
            $re = Adskip::create($input);
            if ($re)
            {
                return redirect('admin/adskip');
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
        $file = Adskip::where('id', $id)->first();
        return view('admin.adskip.edit', compact('file'));
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
        $data = Adskip::where('id', $id)->first();
        $rules = [
            'ad_skip_name' => 'required',
            'ad_skip_describe' => 'required'
        ];
        $message = [
            'ad_skip_name.required' => '名称不能为空！',
            'ad_skip_describe.required' => '跳转方式不能为空！'
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->passes())
        {
            $data->ad_skip_name = $input['ad_skip_name'];
            $data->ad_skip_describe = $input['ad_skip_describe'];
            $status = $data->update();
            if ($status)
            {
                return redirect('admin/adskip');
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