<?php

namespace App\Verification;
use App\Helpers\Helpers;
use App\Http\Controllers\Api\ResultController as Result;
use Illuminate\Http\Request;

/**
 * Class HomeVerification
 *
 * @package \App\Verification
 */
class HomeVerification
{
    public function __construct ()
    {
        $this->result = new Result();
    }

    /**
     * 获取七牛token
     * @param Request $request
     */
    public function getToken (Request $request)
    {
        $uid = $request->item['uid'];
        $token = Helpers::uploadToken();
        return $this->result->responses([
            'status' => 'success',
            'status_code' => '',
            'QNToken' => $token
        ]);
    }
}
