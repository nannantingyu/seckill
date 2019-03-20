<?php
namespace App\Repository\Cache;
use App\Repository\Dao\FlashSaleGoodsRepository;
use Illuminate\Support\Facades\Cache;

/**
 * Class FlashSaleGoodsCacheRepository
 * @package App\Repository\Cache
 */
class FlashSaleGoodsCacheRepository extends FlashSaleGoodsRepository
{
    /**
     * 获取所有秒杀商品，缓存一分钟
     * @param string $order
     * @param int $counts
     * @param int $merchantId
     * @return \Illuminate\Support\Collection
     */
    public function getAllGoods($order = 'new', $counts = null, $merchantId = null) {
        return Cache::remember($this->getFlashSaleGoodsCacheKey($order, $counts, $merchantId), 1, function() use($order, $counts, $merchantId) {
            return parent::getAllGoods($order, $counts, $merchantId);
        });
    }

    public function getFlashSaleGoodsCacheKey($order, $counts, $merchantId) {
        return "flashSaleGoods:".$order.":".$counts.($merchantId??"");
    }


    /**
     * @param $merchantId
     * @param string $order
     * @param null $counts
     * @return mixed
     */
    public function getMerchantGoods($merchantId, $order = 'new', $counts = null) {
        return Cache::remember($this->getFlashSaleGoodsCacheKey($order, $counts, $merchantId), 1, function() use($order, $counts, $merchantId) {
            return parent::getAllGoods($order, $counts, $merchantId);
        });
    }
}