<?php

namespace App\Http\Controllers\Home;

use Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Education;
use App\Model\EducationOrder;


class OrderController extends Controller
{
    public function pay (Request $request)
    {
        //return view('order/pay')->header('Cache-Control', 'no-store');
        $order_id = $request->get('order_id', 0);
        $education_id = $request->get('education_id', 0);
        if($order_id) {
            $eduOrder = EducationOrder::find($order_id);
        } elseif($education_id) {
            $education = Education::find($education_id)->toArray();
            $education['education_id'] = $education['id'];
            $education['order_no']     = date('YmdHis').mt_rand(100000, 999999);
            $education['status']       = 0;
            $eduOrder = EducationOrder::create($education);
        }
        return Response::view('order/pay', ['eduOrder'=>$eduOrder])->header('Cache-Control', 'no-store');
    }
}