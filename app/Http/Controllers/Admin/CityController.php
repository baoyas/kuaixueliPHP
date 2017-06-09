<?php

namespace App\Http\Controllers\Admin;

use App\Model\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $city = City::orderBy('dredge_time', 'desc')->paginate(Config::get('web.admin_page'));
        return view('admin.city.index', compact('city'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.city.add');
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
            'city_code' => 'required',
            'city_name' => 'required',
            'letter' => 'required'
        ], [
            'city_code.required' => '城市编号必填！',
            'city_name.required' => '城市名称必填！',
            'letter.required' => '城市名称首字母必填！'
        ]);
        $input = Input::except('_token');
        $input['dredge_time'] = time();
        $statues = City::create($input);
        if ($statues)
        {
            return redirect('admin/cityList');
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
        $field = City::find($id);
        return view('admin.city.edit', compact('field'));
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
            'city_code' => 'required',
            'city_name' => 'required',
            'letter' => 'required'
        ], [
            'city_code.required' => '城市编号必填！',
            'city_name.required' => '城市名称必填！',
            'letter.required' => '城市名称首字母必填！'
        ]);
        $input = Input::except('_token', '_method');
        $statues = City::where('id', $id)->update($input);
        if ($statues)
        {
            return redirect('admin/cityList');
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
        $re = City::where('id', $id)->delete();
        if ($re)
        {
            $data = [
                'status' => 0,
                'msg' => '城市删除成功！'
            ];
        }
        else
        {
            $data = [
                'status' => 1,
                'msg' => '城市删除失败！'
            ];
        }
        return $data;
    }

    public function upDown ()
    {
        $input = Input::except('_token');
        $msg = ($input['power'] == 0)?'禁用':'开启';
        $cate = City::where('id', $input['id'])->first();
        $cate->power = $input['power'];
        $re = $cate->update();

        if ($re)
        {
            $data = [
                'status' => 0,
                'msg' => '城市' . $msg . '成功！'
            ];
        }
        else
        {
            $data = [
                'status' => 1,
                'msg' => '城市' . $msg . '失败！'
            ];
        }
        return $data;
    }

    public function ishot ()
    {
        $input = Input::except('_token');
        $msg = ($input['power'] == 0)?'普通':'热门';
        $cate = City::where('id', $input['id'])->first();
        $cate->is_hot = $input['power'];
        $re = $cate->update();

        if ($re)
        {
            $data = [
                'status' => 0,
                'msg' => '城市' . $msg . '成功！'
            ];
        }
        else
        {
            $data = [
                'status' => 1,
                'msg' => '城市' . $msg . '失败！'
            ];
        }
        return $data;
    }
}
