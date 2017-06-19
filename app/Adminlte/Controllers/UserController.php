<?php

namespace App\Adminlte\Controllers;

use App\Helpers\Helpers;
use App\Model\Bankroll;
use App\Model\Sell;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Lib\JassEasemob;



use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    private $jaseeasemob;
    public function __construct ()
    {
        $this->jaseeasemob = new JassEasemob();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Admin::js('style/admin/layer/layer.js');
        Admin::js('js/adminlte/user.js');
        return Admin::content(function (Content $content) {
        
            $content->header('用户管理');
            $content->description('列表');
        
            $content->body($this->grid());
        });
        
        $data = User::where('is_del', 0)->orderBy('id', 'asc')->paginate(Config::get('web.admin_page'));
        $show = 'all';
        return view('admin.user.index', compact('data', 'show'));
    }

    /**
     * 禁用用户
     */
    public function ptUsers ()
    {
        $data = User::where('statues', 1)->where('is_del', 0)->orderBy('id', 'asc')->paginate(Config::get('web.admin_page'));
        $show = 'jinyong';
        return view('admin.user.index', compact('data', 'show'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.add');
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
        if (array_key_exists('user_face', $input))
        {
            //有头像
            $thumb = Helpers::UploadFile($input['user_face']);
            $_thumb = $thumb['key'];
        }
        else
        {
            //无头像
            $_thumb = 'default.png';

        }
        $user = User::where('phone', $input['phone'])->first();
        if ($user)
        {
            return back()->with('errors', '该手机号已经注册！');
        }
        else
        {
            $rules = [
                'phone' => 'required',
                'password' => 'required',
                'nickname' => 'required',
            ];
            $message = [
                'phone.required' => '用户手机号不能为空！',
                'password.required' => '密码不能为空！',
                'nickname.required' => '用户昵称不能为空！'
            ];
            $validator = Validator::make($input, $rules, $message);
            if ($validator->passes())
            {
                $input['user_face'] = $_thumb;
                $input['user_reg_time'] = time();
                $input['password'] = Crypt::encrypt($input['password']);
                $input['autograph'] = '这个家伙很懒，什么也没留下！';
                $input['backgroud_pic'] = 'backgroud.jpg';
                $statues = User::create($input);
                if ($statues)
                {
                    Helpers::get_uuid($statues->id);
                    //环信注册用户
                    $_user = [
                        'username' => $input['phone'],
                        'password' => $input['password'],
                        'nickname' => $input['nickname']
                    ];
                    $this->jaseeasemob->openRegister($_user);
                    return redirect('admin/user');
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
        $user = User::where('id', $id)->first();
        $sell = Sell::where('sell_uid', $id)->where('is_del', 0)->paginate(Config::get('web.admin_page'));
        if ($user->is_del)
        {
            return redirect('admin/user');
        }
        return view('admin.user.show', compact('user', 'sell'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = User::find($id);
        return view('admin.user.edit', compact('data'));
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
        $cate = User::find($id);
        $rules = [
            'user_phone' => 'required',
            'user_nickname' => 'required',
        ];
        $message = [
            'user_phone.required' => '用户手机号不能为空！',
            'user_nickname.required' => '用户昵称不能为空！'
        ];
        if (array_key_exists('user_face', $input))
        {
            $file = Input::file('user_face');
            $thumb = Helpers::UploadFile($file);
            $_thumb = $thumb['key'];
            $input['user_face'] = $_thumb;
        }
        else
        {
            $input['user_face'] = $cate->user_face;
        }
        $validator = Validator::make($input, $rules, $message);
        if ($validator->passes())
        {
            if ($input['user_balance_type'] != 'null')
            {
                if ($input['user_balance_type'] == 'add')
                {
                    //添加
                    $cate->user_balance = ($cate->user_balance+$input['user_balance']);
                    //资金流向
                    $bank = [
                        'bank_uid' => $id,
                        'bank_event' => '后台更新',
                        'bank_money_type' => 0,
                        'bank_money' => $input['user_balance'],
                        'bank_terrace' => '后台操作',
                        'bank_creatr_time' => time(),
                        'serial_number' => Helpers::get_order()
                    ];
                    Bankroll::create($bank);
                }
                elseif ($input['user_balance_type'] == 'cut')
                {
                    //减少
                    $cate->user_balance = ($cate->user_balance-$input['user_balance']);
                    //资金流向
                    $bank = [
                        'bank_uid' => $id,
                        'bank_event' => '后台更新',
                        'bank_money_type' => 1,
                        'bank_money' => $input['user_balance'],
                        'bank_terrace' => '后台操作',
                        'bank_creatr_time' => time(),
                        'serial_number' =>  Helpers::get_order()
                    ];
                    Bankroll::create($bank);
                }
            }
            $cate->user_phone = $input['user_phone'];
            $cate->user_nickname = $input['user_nickname'];
            $cate->user_face =  $input['user_face'];
            $re = $cate->update();
            if ($re)
            {
                $rongCloud = new RongCloud();
                $rongCloud->getToken($cate->id, $input['user_nickname'], Config::get('web.QINIU_URL').'/'.$input['user_face']);
                return redirect('admin/user');
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
        $user = User::where('id', $id)->first();
        if ($user)
        {
            $user->is_del = 1;
            $statues = $user->update();
            if ($statues)
            {
                $data = [
                    'status' => 0,
                    'msg' => '删除用户成功！'
                ];
            }
            else
            {
                $data = [
                    'status' => 1,
                    'msg' => '删除用户失败！'
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

    /**
     *POST                           | admin/user/search
     */
    public function search ()
    {
        $input = Input::except('_token');
        $show = $input['show'];
        $search_name = $input['search_name'];
        switch ($input['show']) {
            case 'all':
                //全部查询
                $data = User::where('phone', '=', $input['search_name'])->orWhere(function ($query) use ($search_name)  {
                    $query->where('is_del', 0);
                    $query->where('nickname', 'like', "%$search_name%");
                })->paginate(Config::get('web.admin_page'));
                break;
            case 'jinyong':
                //j禁用用户
                $data = User::where('phone', '=', $input['search_name'])->orWhere(function ($query) use ($search_name)  {
                    $query->where('is_del', 0);
                    $query->where('statues', '=', 1);
                    $query->where('nickname', 'like', "%$search_name%");
                })->paginate(Config::get('web.admin_page'));
                break;
            default:
                # code..
                break;
        }
        return view('admin.user.search', compact('data', 'show', 'search_name'));
    }

    /**
     * POST                           | admin/user/upDown
     */
    public function upDown ()
    {
        echo "1111";exit();
        $input = Input::except('_token');
        $msg = ($input['power'] == 1)?'禁用':'开启';
        $cate = User::find($input['id']);
        $cate->statues = $input['power'];
        $re = $cate->update();
        if ($re)
        {
            $data = [
                'status' => 0,
                'msg' => '用户' . $msg . '成功！'
            ];
        }
        else
        {
            $data = [
                'status' => 1,
                'msg' => '用户' . $msg . '失败！'
            ];
        }
        return $data;
    }

    /**
     * 重置密码 页面
     */
    public function Reset (Request $request)
    {
        $uid = $request->get('uid');
        $user = User::where('id', $uid)->first();
        return view('admin.user.Reset', compact('user'));
    }

    /**
     * 重置密码处理
     */
    public function ResetPass (Request $request)
    {
        $input = Input::except('_token');
        $rules = [
            'password' => 'required|between:6,20|confirmed',
        ];
        $message = [
            'password.required' => '新密码不能为空！',
            'password.between' => '新密码长度不能小于6位！',
            'password.confirmed' => '两次密码不一致！'
        ];

        $validator = Validator::make($input, $rules, $message);
        if ($validator->passes())
        {
            $user = User::where('id', $input['uid'])->first();
            $user->password = Crypt::encrypt($input['password']);
            $user->update();

            /**
             * 重置环信密码
             */
            $options = [
                'username' => $user->phone,
                'password' => $input['password'],
                'newpassword' => $input['password']
            ];
            $this->jaseeasemob->editPassword($options);

            return back()->with('errors', '密码修改成功！');
        }
        else
        {
            return back()->withErrors($validator);
        }
    }

    /**
     * 给用户发送私聊
     * @param Request $request
     */
    public function Privates (Request $request)
    {
        $uid = $request->get('uid');
        $user = User::where('id', $uid)->first();
        $userInfo = User::where('phone', config('web.DEFAULT_UID'))->first();
        if($request->isMethod('post'))
        {
            $content = $request->get('content');
            $file = $request->file('thumb');

            if($content == '')
            {
                return back()->with('errors', '内容不能为空！');
            }

            /**
             * 判断是否上传图片
             */
            if ($file != null)
            {
                //上传图片

                $qiniu = Helpers::UploadFile($file); //上传到七牛

                $dd = $this->uploadFile($qiniu['key'], 'img');
                if ($dd == false)
                {
                    return back()->with('errors', '发送的文件大小请在1M以下！');
                }
                else
                {
                    $ext = [
                        'nickname' => $userInfo->nickname,
                        'user_face' => config('web.QINIU_URL').'/'.$userInfo->user_face
                    ];
                }
                $this->jaseeasemob->yy_hxSend_img(config('web.DEFAULT_UID'), [$user->phone],$dd['uri'].'/'.$dd['entities'][0]['uuid'], $file, $dd['entities'][0]['share-secret'], $dd['width'], $dd['height'], 'users', $ext);
            }

            /**
             * 判断内容
             */
            if($content == '')
            {
                return back()->with('errors', '内容不能为空！');
            }
            else
            {
                $ext = [
                    'nickname' => $userInfo->nickname,
                    'user_face' => config('web.QINIU_URL').'/'.$userInfo->user_face
                ];
            }
            $this->jaseeasemob->yy_hxSend(config('web.DEFAULT_UID'), [$user->phone], $content, 'users', $ext);
            return back()->with('errors', '发送成功！');
        }
        else
        {
            return view('admin.user.Privates', compact('uid', 'user'));
        }
    }

    /**
     * 上传文件
     * @param $file
     * @return mixed
     */
    public function uploadFile ($file, $type = 'img')
    {
        switch ($type) {
            case 'img':
                $url = config('web.QINIU_URL');
                $pic_name = $file;
                $images = $this->jaseeasemob->uploadFile($url.'/'.$pic_name);
                if ($images == false)
                {
                    return false;
                }
                $qiniu = $this->jaseeasemob->postCurl($url.'/'.$pic_name.'?avinfo');
                if (isset($qiniu['error']))
                {
                    return false;
                }
                $images['width'] = $qiniu['streams'][0]['width'];
                $images['height'] = $qiniu['streams'][0]['height'];
                return $images;
                break;
            case 'audio':
                $url = config('web.QINIU_URL');
                $pic_name = $file;
                $audio = $this->jaseeasemob->uploadFile($url.'/'.$pic_name);
                if ($audio == false)
                {
                    return false;
                }
                $qiniu = $this->jaseeasemob->postCurl($url.'/'.$pic_name.'?avinfo');
                if (isset($qiniu['error']))
                {
                    return false;
                }
                $audio['size'] = ($qiniu['format']['size']/1024);
                return $audio;
                break;
            case 'video':
                $url = config('web.QINIU_URL');
                $pic_name = $file;
                /*上传视频 - 开始*/
                $video = $this->jaseeasemob->uploadFile($url.'/'.$pic_name);
                if ($video == false)
                {
                    return false;
                }
                $qiniu = $this->jaseeasemob->postCurl($url.'/'.$pic_name.'?avinfo');
                $video['duration'] = $qiniu['format']['duration'];
                $video['size'] = ($qiniu['format']['size']/1024);
                if (isset($qiniu['error']))
                {
                    return false;
                }
                /*上传视频 - 结束*/

                /*上传视频缩略图 - 开始*/
                $thumb_video_pic_url = $url.'/'.$pic_name.'?vframe/jpg/offset/1/w/480/h/360';
                $video_images = $this->jaseeasemob->uploadFile($thumb_video_pic_url);
                $video['thumb_image_uuid'] = $video_images['entities'][0]['uuid'];
                $video['thumb_image_share-secret'] = $video_images['entities'][0]['share-secret'];
                $video['thumb_image_uri'] = $video_images['uri'];
                /*上传视频缩略图 - 结束*/
                return $video;
                break;
            default:
                # code...
                break;
        }
    }
    

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(User::class, function (Grid $grid) {
            //Admin::script($script);
            $grid->column('id', 'ID')->sortable();
            $grid->column('phone', '手机号');
            $grid->column('user_face', '头像')->display(function () {
                return '<img class="user-image" style="width:48px;height:48px;display:block;" src="'.config('web.QINIU_URL').'/'.$this->user_face.'" />';
            });
            $grid->column('nickname', '昵称');
            $grid->column('user_reg_time', '注册时间')->display(function () {
                return date('Y-m-d H:i:s', $this->user_reg_time);
            });
            $grid->column('statues', '状态')->display(function () {
                if($this->statues == 0) {
                    return '<span class="stat_1" style="color:#09bb07">正常</span>';
                } else {
                    return '<span class="stat_0" style="color:#ff5644;">禁用</span>';
                }
            });
            
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->prepend('<a href="/admin/user/Private?uid='.$this->getKey().'">私聊</a>');
                $actions->append('<a data-token="'.csrf_token().'" onclick="upDown('.$this->getKey().','.$this->row->statues.')">'.($this->row->statues==0?'禁用':'开启').'</a>');
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
        return Config::form(function (Form $form) {
            $form->display('conf_id', 'ID');
            $form->text('conf_title', '标题');
            $form->text('conf_name', '名称');
            $form->select('field_type', '类型')->options(
                //['input'=>'input', 'textarea'=>'textarea', 'checkbox'=>'checkbox']
                ['input'=>'input', 'textarea'=>'textarea']
            );
            $form->text('conf_order', '排序');
            $form->text('conf_tips', '说明');
        });
    }
}
