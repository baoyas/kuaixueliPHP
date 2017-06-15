<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ResultController extends JaseController
{
    protected $statusCode = 200;
    protected $statusMsg = 'failed';
    protected $message = 'not found';
    protected $obj;

    /**
     * 设置状态编码
     */
    public function setStatusCode ($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * 获取状态编码
     */
    public function getStatusCode ()
    {
        return $this->statusCode;
    }

    /**
     * 设置状态提示信息
     * @param $statusMsg
     * @return $this
     */
    public function setStatusMsg ($statusMsg)
    {
        $this->statusMsg = $statusMsg;
        return $this;
    }

    /**
     * 读取状态提示信息
     * @return string
     */
    public function getStatusMsg ()
    {
        return $this->statusMsg;
    }

    /**
     * 设置提示信息
     * @param $message
     */
    public function setMessage ($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * 读取提示信息
     * @return string
     */
    public function getMessage ()
    {
        return $this->message;
    }

    /**
     * 设置对象
     * @param $obj
     * @return $this
     */
    public function setObject ($obj)
    {
        $this->obj = $obj;
        return $this;
    }

    /**
     * 获取对象
     * @return mixed
     */
    public function getObject ()
    {
        return $this->obj;
    }

    /**
     * 重构错误返回函数
     */
    public function responseError ()
    {
        return \Response::json([
            'status' => $this->getStatusMsg(),
            'error' => [
                'status_code' => "" . $this->getStatusCode() . "",
                'message' => $this->getMessage()
            ]
        ], 200, [], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }

    /**
     * 重构分页没有数据
     * @return mixed
     */
    public function responsePage ()
    {
        return \Response::json([
            'status' => 'success',
            'status_code' => "" . $this->getStatusCode() . "",
            'message' => $this->getMessage()
        ], 200, [], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }

    /**
     * 重构成功返回函数
     */
    public function responses ($data, $page = null)
    {
        if ($page != null)
        {
            $data['total'] = "" . $page['total'] . "";
            $data['per_page'] = "" . $page['per_page'] . "";
            $next_page_url = explode('?', $page['next_page_url']); //截取下一页连接获取?号后面的
            $prev_page_url = explode('?', $page['prev_page_url']); //如上同理
            $data['next_page_url'] = ($next_page_url[0] != '')?$next_page_url[1]:'';
            $data['prev_page_url'] = ($prev_page_url[0] != '')?$prev_page_url[1]:'';
        }
        $data['status_code'] = "" . $this->getStatusCode() . "";
        // $_data = str_replace("\\n", "\\n",  json_encode($data, true));
        //return stripcslashes(json_encode($data,JSON_UNESCAPED_UNICODE));
        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        // echo '<pre>';
        // print_r($_data);die;
        // return \Response::json($data);
    }
}
