<?php

namespace App\Http\Controllers\Admin;

use App\Model\Group;
use App\Model\Report;
use App\Model\Reportgroup;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use App\Lib\JassEasemob;

class ReportController extends Controller
{
    private $jasseasemob;
    public function __construct ()
    {
        $this->jasseasemob = new JassEasemob();
    }
    /**
     * 投诉列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index ()
    {
        $data = Report::orderBy('report_time', 'desc')->join('user as u', 'report.report_uid', '=', 'u.id')->join('user as us', 'report.report_to_uid', '=', 'us.id')->select('report.*', 'u.nickname', 'us.nickname as to_nickname')->paginate(Config::get('web.admin_page'));
        return view('admin.report.index', compact('data'));
    }

    /**
     * 投诉处理
     */
    public function updown ()
    {
        $input = Input::except('_token');
        $id = $input['id'];
        $handel = $input['content'];
        $handels = $input['contents'];
        $report = Report::where('id', $id)->first();
        $user = User::where('id', $report->report_to_uid)->first();
        $report_users = User::where('id', $report->report_uid)->first();
        if ($report)
        {
            $report->report_handle = '被投诉人：'.$handel.'--'.'投诉人：'.$handels;
            $report->report_statues = 1;
            $statues = $report->update();
            if ($statues)
            {
                $ext = [
                    'nickname' => '系统通知',
                    'user_face' => config('web.QINIU_URL').'/'.'system.png'
                ];
                $this->jasseasemob->yy_hxSend(config('web.DEFAULT_UID'), [$user->phone], $handel, 'users', $ext);
                $this->jasseasemob->yy_hxSend(config('web.DEFAULT_UID'), [$report_users->phone], $handels, 'users', $ext);
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
     * 群组投诉列表
     */
    public function reportGroup ()
    {
        $data = Reportgroup::orderBy('report_group.report_time', 'desc')->join('user as u', 'report_group.report_uid', '=', 'u.id')->join('group as g', 'report_group.report_groupid', '=', 'g.group_id')->select('report_group.*', 'g.group_name', 'g.owner_uid', 'u.nickname')->paginate(Config::get('web.admin_page'));
        return view('admin.report.reportgroup', compact('data'));
    }

    /**
     * 群组投诉处理
     */
    public function reportGroupUpdown ()
    {
        $input = Input::except('_token');
        $id = $input['id'];
        $handel = $input['content'];
        $handels = $input['contents'];
        $report = Reportgroup::where('id', $id)->first();
        $group = Group::where('group_id', $report->report_groupid)->first();
        $report_users = User::where('id', $report->report_uid)->first();
        if ($report)
        {
            $report->report_handle = '被投诉群：'.$handel.'--'.'投诉人：'.$handels;;
            $report->report_statues = 1;
            $statues = $report->update();
            if ($statues)
            {
                $ext = [
                    'nickname' => '系统通知',
                    'user_face' => config('web.QINIU_URL').'/'.'system.png'
                ];
                $this->jasseasemob->yy_hxSend(config('web.DEFAULT_UID'), [$group->group_owner], $handel, 'users', $ext);
                $this->jasseasemob->yy_hxSend(config('web.DEFAULT_UID'), [$report_users->phone], $handels, 'users', $ext);
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
}
