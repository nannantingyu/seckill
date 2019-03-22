<?php
namespace App\Repository\Dao;
use App\Models\FlashSaleOrder;
use Illuminate\Support\Facades\Auth;
use App\Repository\BaseRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

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

    /**
     * 获取订单状态
     * @param $saleId
     * @return mixed
     */
    public function getOrderStatus($saleId) {
        $userId = Auth::id();
        $orderKey = 'flashSale:Order:'.$saleId.":".$userId;
        return Redis::get($orderKey);
    }

    /**
     * @param $saleId
     * @param $userId
     * @param $num
     * @return bool
     */
    public function makeOrder($saleId, $userId, $num) {
        $stockKey = 'flashSaleStock:'.$saleId;
        $stock = Redis::get($stockKey);
        if ((int)$num > $stock) {
            return false;
        }

        $orderKey = 'flashSale:Order:'.$saleId.":".$userId;
        if (Redis::exists($orderKey)) {
            return false;
        }

        $flashSale = app()->make(FlashSaleRepository::class)->getById($saleId);
        $now = date("Y-m-d H:i:s");

        $orderNo = uuid();
        if ($order = $this->create([
            "user_id"       =>  $userId,
            "order_no"      =>  $orderNo,
            "pay_status"    =>  0,
            "order_time"    =>  $now,
            "goods_id"      =>  $flashSale->goods_id,
            "order_price"   =>  $flashSale->kill_price * $num,
            "merchant_id"    =>  $flashSale->merchant_id,
        ])) {
            // 设置订单超时时间为1小时
            Redis::executeRaw(['set', $orderKey, $orderNo, 'nx', 'ex', 3600]);

            // 减库存
            Redis::descBy($stockKey, $num);
            return true;
        }

        return false;
    }
}