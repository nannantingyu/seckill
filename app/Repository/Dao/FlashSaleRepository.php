<?php
namespace App\Repository\Dao;
use App\Models\FlashSale;
use Illuminate\Support\Facades\Redis;
use App\Repository\BaseRepository;

class FlashSaleRepository extends BaseRepository
{
    function model()
    {
        return FlashSale::class;
    }

    /**
     * 获取秒杀商品详情
     * @param $id int
     * @return FlashSale
     */
    public function findWithInfo($id) {
        return FlashSale::where("flash_sale.id", $id)
            ->join('flash_sale_goods', 'flash_sale.goods_id', '=', 'flash_sale_goods.id')
            ->select('flash_sale.*', 'flash_sale_goods.goods_id as gid', 'flash_sale_goods.goods_name', "flash_sale_goods.place", "flash_sale_goods.brand", "flash_sale_goods.weight", "flash_sale_goods.packing")
            ->first();
    }

    /**
     * 获取商家的所有秒杀商品
     * @param $merchantId
     * @return FlashSaleRepository[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getMerchantFlashSale($merchantId) {
        return $this->where('merchant_id', $merchantId)->get();
    }

    /**
     * 获取已经开始秒杀且未结束或者距离秒杀还有1天的秒杀商品
     * @param string $order
     * @param int $counts
     * @return \Illuminate\Support\Collection
     */
    public function getFlashSale($order = 'new', $counts = null) {
        $orderMap = [
            "new"=>"flash_sale.created_at"
        ];

        $goods = FlashSale::join('flash_sale_merchant', 'flash_sale.merchant_id', '=', 'flash_sale_merchant.id')
            ->where(function($query) {
                $now = date("Y-m-d H:i:s");
                $query->where(function($query) use ($now){
                    $query->where('flash_sale.end_time', '>=', $now)
                        ->where('flash_sale.begin_time', '<=', $now);
                })->orWhere(function($query) use ($now) {
                    $query->where('flash_sale.begin_time', '>=', $now)
                        ->where('flash_sale.begin_time', '<=', date("Y-m-d H:i:s", time()+20*3600));
                });
            })
            ->where('flash_sale.state', 1)
            ->orderBy('flash_sale.begin_time', 'asc');

        if ($order and isset($orderMap[$order])) {
            $goods = $goods->orderBy($orderMap[$order], 'desc');
        }

        if (!is_null($counts) and is_numeric($counts)) {
            $goods = $goods->take($counts);
        }

        return $goods->select('flash_sale.id', 'flash_sale.goods_id', 'flash_sale.begin_time', 'flash_sale.end_time', 'flash_sale.title', 'flash_sale.ori_price', 'flash_sale.kill_price', 'flash_sale.description', 'flash_sale.pictures', 'flash_sale_merchant.id as merchant_id', 'flash_sale.quantity', 'flash_sale.stock', 'flash_sale_merchant.nick_name')
            ->get();
    }

    /**
     * 获取即将开始秒杀的商品
     * @return mixed
     */
    public function getAlmostBeginFlashSales() {
        $beforeFlashSaleStartTime = env('FLASH_SALE_URL_GENERATE_TIME', 60);
        return FlashSale::where('begin_time', '<', date("Y-m-d H:i:s", time() + (int)$beforeFlashSaleStartTime))
            ->where('end_time', '>', date('Y-m-d H:i:s'))
            ->where('state', 1)
            ->get();
    }

    /**
     * 获取秒杀链接
     * @param $flashSaleId
     * @return string
     */
    public function getFlashSaleUrl($flashSaleId) {
        $randKey = Redis::get('flashSaleUrlKey:'.$flashSaleId);
        if (is_null($randKey)) {
            return null;
        }

        return "/flashSaleBuy/".$flashSaleId."/".$randKey;
    }

    /**
     * 检查秒杀链接是否正确
     * @param $flashSaleId
     * @param $randKey
     * @return bool
     */
    public function checkFlashSaleUrl($flashSaleId, $randKey) {
        if (! ($urlKey = $this->getFlashSaleUrl($flashSaleId))) {
            return false;
        }

        return explode('/', $urlKey)[3] == $randKey;
    }

    /**
     * 获取库存
     * @param $saleId
     * @return mixed
     */
    public function getFlashSaleStock($saleId) {

        $flashSale = $this->getById($saleId);
        return $flashSale->stock;
    }
}