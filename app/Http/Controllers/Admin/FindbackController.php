<?php

namespace App\Http\Controllers\Admin;

use App\Model\Findback;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use App\Lib\JassEasemob;

class FindbackController extends Controller
{
    private $jasseasemob;
    public function __construct ()
    {
        $this->jasseasemob = new JassEasemob();
    }
    /**
     * 意见列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index ()
    {
        $data = Findback::orderBy('findback.findback_time', 'desc')->join('user as u', 'findback.findback_uid', '=', 'u.id')->select('findback.*', 'u.nickname')->paginate(Config::get('web.admin_page'));
        return view('admin.findback.index', compact('data'));
    }

    /**
     * 意见反馈处理
     */
    public function updown ()
    {
        $input = Input::except('_token');
        $id = $input['id'];
        $handel = $input['content'];
        $report = Findback::where('id', $id)->first();
        $report_users = User::where('id', $report->findback_uid)->first();
        if ($report)
        {
            $report->findback_handle = '提意见者：'.$handel;
            $statues = $report->update();
            if ($statues)
            {
                $ext = [
                    'nickname' => '系统通知',
                    'user_face' => config('web.QINIU_URL').'/'.'system.png'
                ];
                $this->jasseasemob->yy_hxSend(config('web.DEFAULT_UID'), [$report_users->phone], $handel, 'users', $ext);
                $data = [
                    'status' => 0,
                    'msg' => '处理成功！'
                ];
            }
            else
            {
                $data = [
                    'status' => 1,
                    'msg' => '处理失败！'
                ];
            }
        }
        else
        {
            $data = [
                'status' => 1,
                'msg' => '参数错误！'
            ];
        }
        return $data;
    }

    /**
     * 意见删除
     * @param $id
     */
    public function delete ($id)
    {
        $cate = Findback::where('id', $id)->first();
        if ($cate)
        {
            $statues = Findback::where('id', $id)->delete();
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
        else
        {
            $data = [
                'status' => 1,
                'msg' => '删除失败，没有查找到该意见反馈！'
            ];
        }
        return $data;
    }
}
