<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Model\Cate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResultController as Result;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class CateController extends JaseController
{
    private $result;
    public function __construct ()
    {
        $this->result = new Result();
    }
    public function index ()
    {
        if (!Cache::has('CateIndex'))
        {
            $data = Cate::orderBy('cate_sort', 'asc')->where('cate_power', 1)->get()->toArray();
            $_data = Helpers::unlimitedForLayer($data);
            if (!empty($_data))
            {
                $re = $this->result->responses([
                    'status' => 'success',
                    'status_code' => '',
                    'object' => $_data
                ]);
                cache(['CateIndex' => $re], Config::get('web.CACHE_TIME'));
            }
            else
            {
                return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('区域不能为空！')->responseError();
            }
        }
        return Cache::get('CateIndex');
    }

    public function industry ()
    {
        $data = Cate::where(['cate_power'=>1,'pid'=>0])->orderBy('cate_sort', 'asc')->get()->toArray();
        return $this->result->responses([
            'status' => 'success',
            'status_code' => '',
            'object' => $data
        ]);
    }
}
