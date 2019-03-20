<?php
namespace App\Repository\Dao;
use App\Models\FlashSaleGoods;
use App\Repository\BaseRepository;

class FlashSaleGoodsRepository extends BaseRepository
{
    public function model()
    {
        return FlashSaleGoods::class;
    }

    /**
     * 生成秒杀商品的ID
     * @return string
     */
    public function newGoodsId() {
        return uuid();
    }

    /**
     * 获取所有秒杀商品
     * @param string $order
     * @param int $counts
     * @param int $merchantId
     * @return \Illuminate\Support\Collection
     */
    public function getAllGoods($order = 'new', $counts = null, $merchantId = null) {
        $orderMap = [
            "new"=>"flash_sale_goods.created_at"
        ];

        $goods = FlashSaleGoods::join('flash_sale_merchant', 'flash_sale_goods.merchant_id', '=', 'flash_sale_merchant.id');
        if ($order and isset($orderMap[$order])) {
            $goods = $goods->orderBy($orderMap[$order], 'desc');
        }

        if (! is_null($merchantId)) {
            $goods = $goods->where('merchant_id', $merchantId);
        }

        $goods = $goods->select(
            'flash_sale_goods.goods_id',
            'flash_sale_goods.goods_name',
            'flash_sale_goods.detail_pictures',
            'flash_sale_goods.place',
            'flash_sale_goods.weight',
            'flash_sale_goods.packing',
            'flash_sale_goods.brand',
            'flash_sale_merchant.id as merchant_id',
            'flash_sale_merchant.nick_name');
        if (! is_null($counts)) {
            $goods = $goods->take($counts);
        }

        return $goods->get();
    }

    /**
     * 获取商家的所有商品
     * @param $merchantId
     * @param string $order
     * @param null $counts
     * @return mixed
     */
    public function getMerchantGoods($merchantId, $order = 'new', $counts = null) {
        return $this->getMerchantGoods($order, $counts, $merchantId);
    }
}