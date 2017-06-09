<?php

namespace App\Verification;

use App\Http\Controllers\Api\ResultController as Result;
use App\Model\Report;
use App\Model\Reportgroup;
use Illuminate\Http\Request;

/**
 * Class ReportVerification
 *
 * @package \App\Verification
 */
class ReportVerification
{
    public function __construct ()
    {
        $this->result = new Result();
    }

    /**
     * 投诉
     * @param Request $request
     */
    public function report (Request $request)
    {
        $uid = $request->item['uid'];
        $report_content = $request->get('content');
        $report_to_uid = $request->get('report_to_uid');

        if ($report_content == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('投诉内容不能为空！')->responseError();
        }

        $save = [
            'report_uid' => $uid,
            'report_content' => $report_content,
            'report_time' => time(),
            'report_to_uid' => $report_to_uid
        ];
        $statues = Report::create($save);
        if ($statues)
        {
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'message' => '投诉成功！'
            ]);
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('投诉失败！')->responseError();
        }
    }

    /**
     * 群投诉
     * @param Request $request
     */
    public function reportGroup (Request $request)
    {
        $uid = $request->item['uid'];
        $report_content = $request->get('content');
        $report_groupId = $request->get('report_groupId');  //群id

        if ($report_content == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('投诉内容不能为空！')->responseError();
        }

        $save = [
            'report_uid' => $uid,
            'report_content' => $report_content,
            'report_time' => time(),
            'report_groupid' => $report_groupId
        ];
        $statues = Reportgroup::create($save);
        if ($statues)
        {
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'message' => '投诉成功！'
            ]);
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('投诉失败！')->responseError();
        }
    }
}
