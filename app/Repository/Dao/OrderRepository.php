<?php
namespace App\Repository\Dao;
use App\Models\FlashSaleOrder;
use Illuminate\Support\Facades\Auth;
use App\Repository\BaseRepository;

class OrderRepository extends BaseRepository
{
    function model()
    {
        return FlashSaleOrder::class;
    }

    public function paySuccess($order_no, $pay_time, $pay_type, $pay_order_no) {
        $order = $this->getByColumn('order_no', $order_no);
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
}