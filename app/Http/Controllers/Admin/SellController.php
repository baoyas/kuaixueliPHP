<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helpers;
use App\Model\Cate;
use App\Model\Sell;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;

class SellController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Sell::where('sell.is_del', 0)->orderBy('sell.sell_time', 'desc')->join('user as u', 'sell.sell_uid', '=', 'u.id')->select('sell.*', 'u.phone', 'u.nickname')->paginate(Config::get('web.admin_page'));
        return view('admin.sell.index', compact('data'));
    }

    /**
     * 搜索
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function Check ()
    {
        $input = Input::except('_token');
        $search = $input['search'];
        $data = Sell::where('sell.is_del', 0)->orderBy('sell.sell_time', 'desc')->where('u.phone', $input['search'])->join('user as u', 'sell.sell_uid', '=', 'u.id')->select('sell.*', 'u.phone', 'u.nickname')->paginate(Config::get('web.admin_page'));
        return view('admin.sell.Check', compact('data', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cate = (new Cate)->tree();
        return view('admin.sell.add', compact('cate'));
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
        if (array_key_exists('sell_pic', $input))
        {
            $arr = Helpers::UploadFiles($input['sell_pic']);
            $_pic = serialize($arr);
        }
        else
        {
            return back()->with('errors', '请选择封面！');
        }
        $this->validate(request(), [
            'is_sell' => 'required',
            'sell_title' => 'required',
            'cate_id' => 'required',
            'sell_describe' => 'required',
            'sell_price' => 'required',
            'sell_area' => 'required',
            'sell_uid' => 'required',
        ], [
            'is_sell.required' => '类别必填！',
            'sell_title.required' => '标题必填！',
            'cate_id.required' => '分类必选',
            'sell_describe.required' => '描述必填',
            'sell_price.required' => '价格必填',
            'sell_area.required' => '地区必填',
            'sell_uid.required' => '发布人必选'
        ]);
        $input['sell_pic'] = $_pic;
        $input['sell_describe'] = Helpers::str_replace_add($input['sell_describe']);
        $input['sell_time'] = time();
        $statues = Sell::create($input);
        if ($statues)
        {
            return redirect('admin/sell');
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
        $data = Sell::where('sell.id', $id)->join('user as u', 'sell.sell_uid', '=', 'u.id')->select('sell.*', 'u.user_face', 'u.nickname', 'u.phone')->first();
        if ($data->is_del)
        {
            return redirect('admin/sell');
        }
        $data->sell_pic = unserialize($data->sell_pic);
        return view('admin.sell.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cate = (new Cate)->tree();
        $data = Sell::where('sell.id', $id)->join('user as u', 'sell.sell_uid', '=', 'u.id')->select('sell.*', 'u.nickname')->first();
        $data->sell_pic = unserialize($data->sell_pic);
        return view('admin.sell.edit', compact('data', 'cate'));
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
        $sell = Sell::where('id', $id)->first();
        $old_pic = unserialize($sell->sell_pic);
        if (array_key_exists('sell_pic', $input))
        {
            $arr = Helpers::UploadFiles($input['sell_pic']);
            $new_pic = array_merge($old_pic, $arr);
            $_pic = serialize($new_pic);
        }
        else
        {
            $_pic = $sell->sell_pic;
        }
        $this->validate(request(), [
            'is_sell' => 'required',
            'sell_title' => 'required',
            'cate_id' => 'required',
            'sell_describe' => 'required',
            'sell_price' => 'required',
            'sell_area' => 'required',
            'sell_uid' => 'required',
        ], [
            'is_sell.required' => '类别必填！',
            'sell_title.required' => '标题必填！',
            'cate_id.required' => '分类必选',
            'sell_describe.required' => '描述必填',
            'sell_price.required' => '价格必填',
            'sell_area.required' => '地区必填',
            'sell_uid.required' => '发布人必选'
        ]);
        $input['sell_pic'] = $_pic;
        $input['sell_describe'] = Helpers::str_replace_add($input['sell_describe']);
        unset($input['search_name']); //删掉不需要的字段
        $statues = Sell::where('id', $id)->update($input);
        if ($statues)
        {
            return redirect('admin/sell');
        }
        else
        {
            return back()->with('errors', '更新失败！');
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
        $sell = Sell::where('id', $id)->first();
        if ($sell)
        {
            $sell->is_del = 1;
            $statues = $sell->update();
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
                    'msg' => '删除失败！'
                ];
            }
        }
        else
        {
            $data = [
                'status' => 1,
                'msg' => '没有查找到该条朋友圈！'
            ];
        }
        return $data;
    }

    public function search ()
    {
        $input = Input::except('_token');
        $user = User::where('phone', $input['search_name'])->first();
        if ($user)
        {
            /**
             * 判断该用户是否被删除
             */
            if ($user->is_del == 0)
            {
                /**
                 * 判断用户是否被禁用
                 */
                if ($user->statues == 0)
                {
                    $data = [
                        'status' => 0,
                        'msg' => '查询成功！',
                        'uid' => $user->id,
                        'nickname' => $user->nickname
                    ];
                }
                else
                {
                    $data = [
                        'status' => 1,
                        'msg' => '用户已被禁用，不可发布信息！'
                    ];
                }
            }
            else
            {
                $data = [
                    'status' => 1,
                    'msg' => '用户已被删除，不可发布信息！'
                ];
            }
        }
        else
        {
            $data = [
                'status' => 1,
                'msg' => '没有查找到该用户！'
            ];
        }
        return $data;
    }

    public function updown ()
    {
        $input = Input::except('_token');
        $msg = ($input['power'] == 0)?'禁用':'开启';
        $cate = Sell::find($input['id']);
        $cate->recommend = $input['power'];
        $re = $cate->update();
        if ($re)
        {
            $data = [
                'status' => 0,
                'msg' => '信息' . $msg . '成功！'
            ];
        }
        else
        {
            $data = [
                'status' => 1,
                'msg' => '信息' . $msg . '失败！'
            ];
        }
        return $data;
    }

    public function delPic ()
    {
        $input = Input::except('_token');
        $sell = Sell::where('id', $input['id'])->first();
        if ($sell)
        {
            $pic = unserialize($sell->sell_pic);
            foreach($pic as $key=>$val)
            {
                if ($val == $input['key'])
                {
                    unset($pic[$key]);//删除answerContent字段
                }
            }
            $sell->sell_pic = serialize($pic);
            $statues = $sell->update();
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
                    'msg' => '删除失败！'
                ];
            }
        }
        else
        {
            $data = [
                'status' => 1,
                'msg' => '没有查找到！'
            ];
        }
        return $data;
    }

    public function changeorder()
    {
        $input = Input::all();
        $cate = Sell::find($input['id']);
        $cate->sell_order = $input['ad_object_sort'];
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
}
