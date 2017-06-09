<?php

namespace App\Http\Controllers\Admin;

use App\Model\Ad;
use App\Model\Adplace;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class AdplaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Adplace::orderBy('id', 'asc')->paginate(Config::get('web.admin_page'));
        return view('admin.adplace.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.adplace.add');
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
            'ad_place_name' => 'required',
        ];
        $message = [
            'ad_place_name.required' => '分类名称不能为空！'
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->passes())
        {
            $re = Adplace::create($input);
            if ($re)
            {
                return redirect('admin/adplace');
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
        $file = Adplace::where('id', $id)->first();
        return view('admin.adplace.edit', compact('file'));
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
        $data = Adplace::where('id', $id)->first();
        $rules = [
            'ad_place_name' => 'required',
        ];
        $message = [
            'ad_place_name.required' => '分类名称不能为空！'
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->passes())
        {
            $data->ad_place_name = $input['ad_place_name'];
            $status = $data->update();
            if ($status)
            {
                return redirect('admin/adplace');
            }
            else
            {
                return back()->with('errors', '更新失败！');
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
        $adplace = Adplace::where('id', $id)->first();
        $ad = Ad::where('ad_place_id', $id)->first();
        if ($ad)
        {
            $data = [
                'status' => 1,
                'msg' => '广告中已使用此显示位置不可删除！'
            ];
        }
        else
        {
            if ($adplace)
            {
                if ($adplace->ad_place_power)
                {
                    $data = [
                        'status' => 1,
                        'msg' => '系统定义的显示位置不可删除！'
                    ];
                }
                else
                {
                    $status = Adplace::where('id', $id)->delete();
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
