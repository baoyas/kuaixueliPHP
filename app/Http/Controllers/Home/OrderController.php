<?php

namespace App\Http\Controllers\Home;

use App\Model\EducationOrderProduct;
use DB;
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
        $user_id = $request->user()->id;

        if($order_id) {
            $eduOrder = EducationOrder::with('products.school.province')->where(['id'=>$order_id, 'user_id'=>$user_id])->first();
        } elseif($education_id) {
            $cacheKey = "cart_{$user_id}";
            $cart = app('cache')->get($cacheKey);
            $cart = empty($cart) ? [] : \json_decode($cart, true);
            if($cart) {
                $edus = Education::whereIn('id', array_keys($cart))->get()->toArray();
                $fee = 0;
                foreach($edus as $edu) {
                    $fee += $edu['entry_fee'] + $edu['kxl_fee'];
                }
                $data['user_id']  = $request->user()->id;
                $data['fee']      = $fee;
                $data['order_no'] = date('YmdHis').mt_rand(100000, 999999);
                $data['status']   = 0;
                $eduOrder = EducationOrder::create($data);
                foreach($edus as $edu) {
                    $edu['order_id'] = $eduOrder->id;
                    EducationOrderProduct::create($edu);
                }
                $eduOrder = EducationOrder::with('products.school.province')->where(['id'=>$eduOrder->id, 'user_id'=>$user_id])->first();
            }
        }
        return Response::view('order/pay', ['eduOrder'=>$eduOrder])->header('Cache-Control', 'no-store');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function list (Request $request)
    {
        $user_id = $request->user()->id;
        $status = $request->get('status', NULL);
        $where['user_id'] = $user_id;
        if(!is_null($status)) {
            $where['status'] = $status;
        }
        $eduOrder = EducationOrder::with('products.school.province')->where($where)->where('status', '<>', 3)->get();

        $stat = EducationOrder::where([['user_id', '=', $user_id], ['status', '<>', 3]])->groupBy('status')
                    ->get([
                        'status',
                        DB::raw('COUNT(*) as num')
                    ])->pluck('num', 'status')->toArray();

        return Response::view('order/list', ['eduOrder'=>$eduOrder, 'status'=>$status, 'stat'=>$stat])->header('Cache-Control', 'no-store');
    }

    public function cancel (Request $request)
    {
        $user_id = $request->user()->id;
        $order_id = $request->get('order_id', 0);
        $where['user_id'] = $user_id;
        $where['id'] = $order_id;
        $eduOrder = EducationOrder::where($where)->first();
        if($eduOrder) {
            $eduOrder->status = 2;
            $eduOrder->save();
            return $this->response(NULL);
        }
        return $this->response(NULL, '-1', '订单不存在');
    }

    public function uncancel (Request $request)
    {
        $user_id = $request->user()->id;
        $order_id = $request->get('order_id', 0);
        $where['user_id'] = $user_id;
        $where['id'] = $order_id;
        $eduOrder = EducationOrder::where($where)->first();
        if($eduOrder) {
            $eduOrder->status = 0;
            $eduOrder->save();
            return $this->response(NULL);
        }
        return $this->response(NULL, '-1', '订单不存在');
    }

    public function delete (Request $request)
    {
        $user_id = $request->user()->id;
        $order_id = $request->get('order_id', 0);
        $where['user_id'] = $user_id;
        $where['id'] = $order_id;
        $eduOrder = EducationOrder::where($where)->first();
        if($eduOrder) {
            $eduOrder->status = 3;
            $eduOrder->save();
            return $this->response(NULL);
        }
        return $this->response(NULL, '-1', '订单不存在');
    }


}