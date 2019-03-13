<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/25
 * Time: 5:57 PM
 */

namespace App\Repository;
use App\Models\SeckillShopper;
use Illuminate\Support\Facades\DB;
use ShopperAuth;
use App\Models\SeckillGoods;

class SeckillGoodsRepository
{
    /**
     * Create seckill_goods
     * @param $data
     * @return int
     */
    public function createSeckillGoods($data) {
        $seckillGoods = new SeckillGoods();
        $seckillGoods->shopper_id = ShopperAuth::id();
        $seckillGoods->goods_id = uuid();
        $seckillGoods->brand = $data['brand'];
        $seckillGoods->place = $data['place'];
        $seckillGoods->packing = $data['packing'];
        $seckillGoods->weight = $data['weight'];
        $seckillGoods->goods_name = $data['goods_name'];
        $seckillGoods->detail_pictures = json_encode($data['detail_pictures']);

        $seckillGoods->save();
        return $seckillGoods->id;
    }

    /**
     * update seckill goods
     * @param $data
     * @return int
     */
    public function updateSeckillGoods($data) {
        $data['detail_pictures'] = json_encode($data['detail_pictures']);
        return DB::table("seckill_goods")->where("id", $data['id'])->update($data);
    }

    /**
     * delete seckill goods
     * @param $id
     * @return int
     */
    public function deleteSeckillGoods($id) {
        return SeckillGoods::where("id", $id)->delete();
    }

    /**
     * Get all goods of shopper
     * @return mixed
     */
    public function listSeckillGoods() {
        return SeckillGoods::where('shopper_id', ShopperAuth::id())->get();
    }

    /**
     * Get seckill goods by goods_id
     * @param $id int
     * @return
     */
    public function find($goods_id) {
        return SeckillGoods::where("goods_id", $goods_id)->first();
    }

    public function findById($id) {
        return SeckillGoods::find($id);
    }

    /**
     * List all goods
     */
    public function listAllSeckillGoods($order = 'new', $counts = 5) {
        $orderMap = [
            "new"=>"seckill_goods.created_at"
        ];

        $goods = DB::table('seckill_goods')->join('seckill_shopper', 'seckill_goods.shopper_id', '=', 'seckill_shopper.id');
        if ($order and isset($orderMap[$order])) {
            $goods = $goods->orderBy($orderMap[$order], 'desc');
        }

        return $goods->select('seckill_goods.goods_id', 'seckill_goods.goods_name', 'seckill_goods.detail_pictures', 'seckill_shopper.id as shopper_id', 'seckill_shopper.nick_name')
            ->take($counts)
            ->get();
    }
}