<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helpers;
use App\Model\Ad;
use App\Model\Adplace;
use App\Model\Adskip;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Ad::orderBy('ad_object.ad_object_sort', 'asc')->join('ad_place as ap', 'ad_object.ad_place_id', '=', 'ap.id')->join('ad_skip as ak', 'ad_object.ad_skip_id', '=', 'ak.id')->select('ad_object.*', 'ap.ad_place_name', 'ak.ad_skip_name', 'ak.ad_skip_describe')->paginate(Config::get('web.admin_page'));
        return view('admin.ad.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $adplace = Adplace::orderBy('id', 'asc')->get();
        $adskip = Adskip::orderBy('id', 'asc')->get();
        return view('admin.ad.add', compact('adplace', 'adskip'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $file = Input::file('ad_object_thumb');
        if ($file == null)
        {
            return back()->with('errors', '请选择图片！');
        }
        else
        {
            $input = Input::except('_token','ad_object_thumb');
            $rules = [
                'ad_object_name' => 'required',
                'ad_place_id' => 'required',
                'ad_skip_id' => 'required',
                'ad_object_aim' => 'required',
                'ad_start_at' => 'required',
                'ad_end_at' => 'required',
                'ad_object_sort' => 'required'
            ];
            $message = [
                'ad_object_name.required' => '名称不能为空！',
                'ad_place_id.required' => '显示位置不能为空！',
                'ad_skip_id.required' => '跳转方式不能为空！',
                'ad_object_aim.required' => '目标不能为空！',
                'ad_start_at.required' => '有效日期不能为空！',
                'ad_end_at.required' => '有效日期不能为空！',
                'ad_object_sort.required' => '排序不能为空！',
            ];
            $thumb = Helpers::UploadFile($file);
//            dd($thumb);
            $_thumb = $thumb['key'];
            $input['ad_object_thumb'] = $_thumb;
            $input['ad_start_at'] = strtotime($input['ad_start_at'] . '00:00:00');
            $input['ad_end_at'] = strtotime($input['ad_end_at'] . '23:59:59');
            $validator = Validator::make($input, $rules, $message);
            if ($validator->passes())
            {
                $status = Ad::create($input);
                if ($status)
                {
                    return redirect('admin/ad');
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
        $adplace = Adplace::orderBy('id', 'asc')->get();
        $adskip = Adskip::orderBy('id', 'asc')->get();
        $file = Ad::where('id', $id)->first();
        return view('admin.ad.edit', compact('adplace', 'adskip', 'file'));
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
        $ad = Ad::find($id);
        $rules = [
            'ad_object_name' => 'required',
            'ad_place_id' => 'required',
            'ad_skip_id' => 'required',
            'ad_object_aim' => 'required',
            'ad_start_at' => 'required',
            'ad_end_at' => 'required',
            'ad_object_sort' => 'required'
        ];
        $message = [
            'ad_object_name.required' => '名称不能为空！',
            'ad_place_id.required' => '显示位置不能为空！',
            'ad_skip_id.required' => '跳转方式不能为空！',
            'ad_object_aim.required' => '目标不能为空！',
            'ad_start_at.required' => '有效日期不能为空！',
            'ad_end_at.required' => '有效日期不能为空！',
            'ad_object_sort.required' => '排序不能为空！',
        ];
        if (array_key_exists('ad_object_thumb', $input))
        {
            $file = Input::file('ad_object_thumb');
            $thumb = Helpers::UploadFile($file);
            $_thumb = $thumb['key'];
            $input['ad_object_thumb'] = $_thumb;
        }
        else
        {
            $input['ad_object_thumb'] = $ad->ad_object_thumb;
        }
        $validator = Validator::make($input, $rules, $message);
        if ($validator->passes())
        {
            $ad->ad_object_name = $input['ad_object_name'];
            $ad->ad_place_id = $input['ad_place_id'];
            $ad->ad_skip_id = $input['ad_skip_id'];
            $ad->ad_object_aim = $input['ad_object_aim'];
            $ad->ad_start_at = strtotime($input['ad_start_at'] . '00:00:00');
            $ad->ad_end_at = strtotime($input['ad_end_at'] . '23:59:59');
            $ad->ad_object_sort = $input['ad_object_sort'];
            $ad->ad_object_thumb = $input['ad_object_thumb'];
            $re = $ad->update();
            if ($re)
            {
                return redirect('admin/ad');
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
        $re = Ad::where('id', $id)->delete();
        if ($re)
        {
            $data = [
                'status' => 0,
                'msg' => '广告删除成功！'
            ];
        }
        else
        {
            $data = [
                'status' => 1,
                'msg' => '广告删除成功！'
            ];
        }
        return $data;
    }

    /**
     * 更新排序
     * @return array
     */
    public function changeorder()
    {
        $input = Input::all();
        $cate = Ad::find($input['id']);
        $cate->ad_object_sort = $input['ad_object_sort'];
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

    /**
     * 开启或关闭
     * @return array
     */
    public function upDown ()
    {
        $input = Input::except('_token');
        $msg = ($input['power'] == 1)?'禁用':'开启';
        $cate = Ad::find($input['id']);
        $cate->ad_object_power = $input['power'];
        $re = $cate->update();
        if ($re)
        {
            $data = [
                'status' => 0,
                'msg' => '广告' . $msg . '成功！'
            ];
        }
        else
        {
            $data = [
                'status' => 1,
                'msg' => '广告' . $msg . '失败！'
            ];
        }
        return $data;
    }
}
