<?php

namespace App\Verification;
use App\Helpers\Helpers;
use App\Http\Controllers\Api\ResultController as Result;
use App\Model\Findback;
use Illuminate\Http\Request;

/**
 * Class FindbackVerification
 *
 * @package \App\Verification
 */
class FindbackVerification
{
    public function __construct ()
    {
        $this->result = new Result();
    }

    /**
     * 意见反馈
     * @param Request $request
     */
    public function feedback (Request $request)
    {
        $uid = $request->item['uid'];
        $content = $request->get('content');
        /**
         * 判断内容是否为空
         */
        if ($content == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('反馈内容不能为空！')->responseError();
        }
        $save = [
            'findback_uid' => $uid,
            'findback_content' => Helpers::str_replace_add($content),
            'findback_time' => time()
        ];
        $statues = Findback::create($save);
        if ($statues)
        {
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'message' => '意见反馈成功！'
            ]);
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('添加反馈意见失败！')->responseError();
        }
    }
}
