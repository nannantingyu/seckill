<?php
namespace App\Repository\Dao;
use Illuminate\Support\Facades\DB;
use App\Facades\MerchantAuth;
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
     * @return \Illuminate\Support\Collection
     */
    public function getAllFlashSaleGoods($order = 'new', $counts = 5) {
        $orderMap = [
            "new"=>"flash_sale_goods.created_at"
        ];

        $goods = DB::table('flash_sale_goods')->join('flash_sale_shopper', 'flash_sale_goods.merchant_id', '=', 'flash_sale_shopper.id');
        if ($order and isset($orderMap[$order])) {
            $goods = $goods->orderBy($orderMap[$order], 'desc');
        }

        return $goods->select('flash_sale_goods.goods_id', 'flash_sale_goods.goods_name', 'flash_sale_goods.detail_pictures', 'flash_sale_shopper.id as merchant_id', 'flash_sale_shopper.nick_name')
            ->take($counts)
            ->get();
    }
}