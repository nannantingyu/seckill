<?php
namespace App\Repository\Cache;
use App\Repository\Dao\FlashSaleRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class FlashSaleCacheRepository extends FlashSaleRepository
{
    /**
     * 获取缓存的键
     * @param $id
     * @return string
     */
    protected function getFlashSaleCacheKey($id) {
        return "flashSale:".$id;
    }

    /**
     * 查找秒杀商品，带详情
     * @param $id
     * @return mixed
     */
    public function findWithInfo($id)
    {
        return Cache::remember($this->getFlashSaleCacheKey($id), 2, function() use($id) {
            return parent::findWithInfo($id);
        });
    }

    /**
     * @param $merchantId
     * @return FlashSaleRepository[]|\Illuminate\Database\Eloquent\Collection|mixed
     */
    public function getMerchantFlashSale($merchantId) {
        return Cache::remember('merchant:flashSale:'.$merchantId, 2, function() use($merchantId) {
            return parent::getMerchantFlashSale($merchantId);
        });
    }

    /**
     * 获取库存
     * @param $saleId
     * @return mixed
     */
    public function getFlashSaleStock($saleId)
    {
        $stockKey = 'flashSaleStock:'.$saleId;
        if (! Redis::exists($stockKey)) {
            Redis::setnx($stockKey, parent::getFlashSaleStock($saleId));
        }

        return Redis::get($stockKey);
    }
}