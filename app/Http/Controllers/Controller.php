<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function response($data, $code=0, $msg='') {
        return response()->json(['code'=>$code, 'msg'=>$msg, 'data'=>$data], 200, [], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }
}
