<?php
namespace App\Repository;
use App\Models\SeckillOrder;
use Auth;

class SeckillOrderRepository
{
    public function createOrder($data) {
        $seckillOrder = new SeckillOrder($data);
        $seckillOrder->user_id = Auth::id();
        $seckillOrder->order_no = uuid();
        $seckillOrder->pay_status = 0;
        if ($seckillOrder->save()) {
            return $seckillOrder->order_no;
        }

        return false;
    }

    public function fillOrder($data) {
        $seckillOrder = SeckillOrder::where("order_no", $data['order_no'])->first();
        if (is_null($seckillOrder)) {
            return false;
        }

        $seckillOrder->username = $data['username'];
        $seckillOrder->address = $data['address'];
        $seckillOrder->phone = $data['phone'];

        return $seckillOrder->save();
    }

    public function findOrder($order_no) {
        return SeckillOrder::where('order_no', $order_no)->first();
    }

    public function paySuccess($order_no, $pay_time, $pay_type, $pay_order_no) {
        $order = $this->findOrder($order_no);
        if ($order and $order->pay_status == 0) {
            $order->pay_time = $pay_time;
            $order->pay_type = $pay_type;
            $order->pay_order_no = $pay_order_no;
            $order->pay_status = 1;

            $order->save();
            return true;
        }

        return false;
    }

    public function alipaySuccess($order_no, $pay_time, $pay_order_no) {
        return $this->paySuccess($order_no, $pay_time, 1, $pay_order_no);
    }

    public function weixinpaySuccess($order_no, $pay_time, $pay_order_no) {
        return $this->paySuccess($order_no, $pay_time, 2, $pay_order_no);
    }
}