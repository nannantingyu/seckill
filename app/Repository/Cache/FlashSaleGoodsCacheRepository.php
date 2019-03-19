<?php
namespace App\Repository\Cache;
use App\Repository\Dao\FlashSaleGoodsRepository;
use Illuminate\Support\Facades\Cache;

class FlashSaleGoodsCacheRepository extends FlashSaleGoodsRepository
{
    /**
     * 获取所有秒杀商品，缓存一分钟
     * @param string $order
     * @param int $counts
     * @return \Illuminate\Support\Collection
     */
    public function getAllFlashSaleGoods($order = 'new', $counts = 5) {
        return Cache::remember($this->getFlashSaleGoodsCacheKey($order, $counts), 1, function() use($order, $counts) {
            return parent::getAllFlashSaleGoods($order, $counts);
        });
    }

    public function getFlashSaleGoodsCacheKey($order, $counts) {
        return "flashSaleGoods:".$order.":".$counts;
    }
}