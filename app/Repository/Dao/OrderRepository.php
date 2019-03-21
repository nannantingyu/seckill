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

    /**
     * 获取登陆用户的订单
     * @return null
     */
    public function getUserOrderList() {
        $userId = Auth::id();
        if (is_null($userId)) {
            return null;
        }

        return FlashSaleOrder::join('flash_sale', 'flash_sale_order.goods_id', '=', 'flash_sale.id')
            ->select('flash_sale_order.*', 'flash_sale.title')
            ->where('flash_sale_order.user_id', $userId)
            ->orderBy('flash_sale.updated_at', 'desc')
            ->get();
    }
}