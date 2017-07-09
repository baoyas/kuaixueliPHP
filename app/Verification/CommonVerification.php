<?php

namespace App\Verification;
use App\Helpers\Helpers;
use App\Model\Common;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ResultController as Result;
/**
 * Class CommonVerification
 *
 * @package \App\Verification
 */
class CommonVerification
{
    public function __construct ()
    {
        $this->result = new Result();
    }

    /**
     * 发布评论
     * @param Request $request
     */
    public function common (Request $request)
    {
        $uid = $request->item['uid'];
        $sell_id = $request->get('sell_id');
        $to_uid = ($request->get('to_uid'))?$request->get('to_uid'):0;
        $content = $request->get('content');
        $type = $request->get('type'); //1买卖 2朋友
        if ($content == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('评论内容不能为空！')->responseError();
        }
        if ($type == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('评论类型不能为空！')->responseError();
        }
        $_content = Helpers::str_replace_add($content);
        $save = [
            'common_id' => $sell_id,
            'form_uid' => $uid,
            'to_uid' => $to_uid,
            'common_time' => time(),
            'common_content' => $_content,
            'common_type' => $type
        ];
        $statues = Common::create($save);
        if ($statues)
        {
            User::addPoints($uid, 4);
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'message' => '评论成功！'
            ]);
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('评论失败！')->responseError();
        }
    }
}
