<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helpers;
use App\Model\Version;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class VersionController extends Controller
{
    /**
     *  GET|HEAD                       | admin/version                | version.index
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Version::orderBy('id', 'desc')->paginate(Config::get('web.admin_page'));
        return view('admin.version.index', compact('data'));
    }

    /**
     * GET|HEAD                       | admin/version/create         | version.create
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.version.add');
    }

    /**
     * POST                           | admin/version                | version.store
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::except('_token');
        $rules = [
            'ver_number' => 'required',
            'ver_terminal' => 'required',
            'ver_content' => 'required',
        ];
        $message = [
            'ver_number.required' => '版本号不能为空！',
            'ver_terminal.required' => '终端不能为空！',
            'ver_content.required' => '更新内容不能为空！'
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->passes())
        {
            $input['ver_content'] = Helpers::str_replace_add_admin($input['ver_content']);
            $input['ver_create_at'] = time();
            $re = Version::create($input);
            if ($re)
            {
                return redirect('admin/version');
            }
            else
            {
                return back()->with('errors', '添加更新版本失败！');
            }
        }
        else
        {
            return back()->withErrors($validator);
        }
    }

    /**
     *  GET|HEAD                       | admin/version/{version}      | version.show
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
     * GET|HEAD                       | admin/version/{version}/edit | version.edit
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * PUT|PATCH                      | admin/version/{version}      | version.update
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     *  DELETE                         | admin/version/{version}      | version.destroy
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
