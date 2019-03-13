<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/25
 * Time: 5:57 PM
 */

namespace App\Repository;
use App\Models\Seckill;
use ShopperAuth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class SeckillRepository
{
    /**
     * Create seckill
     * @param $data
     * @return int
     */
    public function createSeckill($data) {
        $seckill = new Seckill();
        $seckill->goods_id = $data['goods_id'];
        $seckill->title = $data['title'];
        $seckill->pictures = $data['pictures'];
        $seckill->description = $data['description'];
        $seckill->ori_price = $data['ori_price'];
        $seckill->kill_price = $data['kill_price'];
        $seckill->shopper_id = ShopperAuth::id();
        $seckill->begin_time = $data['begin_time'];
        $seckill->end_time = $data['end_time'];
        $seckill->quantity = $data['quantity'];
        $seckill->stock = $data['stock'];

        $seckill->save();
        return $seckill;
    }

    /**
     * update seckill
     * @param $data
     * @return int
     */
    public function updateSeckill($data) {
        $data['pictures'] = json_encode($data['pictures']);
        $seckill = Seckill::where('id', $data['id'])->first();
        if (!is_null($seckill)) {
            $seckill->pictures = $data['pictures'];
            $seckill->title = $data['title'];
            $seckill->description = $data['description'];
            $seckill->ori_price = $data['ori_price'];
            $seckill->kill_price = $data['kill_price'];
            $seckill->stock = $data['stock'];
            $seckill->quantity = $data['quantity'];
            $seckill->begin_time = $data['begin_time'];
            $seckill->end_time = $data['end_time'];
            $seckill->save();

            return $seckill;
        }

        return null;
    }

    /**
     * delete seckill
     * @param $id
     * @return int
     */
    public function deleteSeckill($id) {
        return Seckill::where("id", $id)->delete();
    }

    /**
     * Get all goods of shopper
     * @return mixed
     */
    public function listShopperSeckill() {
        return Seckill::where('shopper_id', ShopperAuth::id())->get();
    }

    /**
     * Get all goods of shopper
     * @return mixed
     */
    public function listAllSeckill() {
        return Seckill::get();
    }

    /**
     * Get seckill goods by id
     * @param $id int
     * @return Seckill
     */
    public function find($id) {
        return Seckill::where("seckill.id", $id)->join('seckill_goods', 'seckill.goods_id', '=', 'seckill_goods.id')
            ->select('seckill.*', 'seckill_goods.goods_id as gid', 'seckill_goods.goods_name', "seckill_goods.place", "seckill_goods.brand", "seckill_goods.weight", "seckill_goods.packing")
            ->first();
    }

    /**
     * @param $id
     * @param $state
     * @return bool
     */
    public function check($id, $state) {
        $seckill = Seckill::find($id);
        if (empty($seckill)) {
            return false;
        }

        $seckill->state = $state;
        $seckill->check_at = date("Y-m-d H:i:s");
        $seckill->save();
        return $seckill;
    }

    /**
     * List all goods
     */
    public function listSeckill($order = 'new', $counts = 5) {
        $orderMap = [
            "new"=>"seckill.created_at"
        ];

        $goods = DB::table('seckill')->join('seckill_shopper', 'seckill.shopper_id', '=', 'seckill_shopper.id')
            ->where(function($query) {
                $now = date("Y-m-d H:i:s");
                $query->where(function($query) use ($now){
                    $query->where('seckill.end_time', '>=', $now)
                        ->where('seckill.begin_time', '<=', $now);
                })->orWhere(function($query) use ($now) {
                    $query->where('seckill.begin_time', '>=', $now)
                        ->where('seckill.begin_time', '<=', date("Y-m-d H:i:s", time()+20*3600));
                });
            })
            ->where('state', 1)
            ->orderBy('begin_time', 'asc');

        if ($order and isset($orderMap[$order])) {
            $goods = $goods->orderBy($orderMap[$order], 'desc');
        }

        return $goods->select('seckill.id', 'seckill.goods_id', 'seckill.begin_time', 'seckill.end_time', 'seckill.title', 'seckill.ori_price', 'seckill.kill_price', 'seckill.description', 'seckill.pictures', 'seckill_shopper.id as shopper_id', 'seckill.quantity', 'seckill.stock', 'seckill_shopper.nick_name')
            ->take($counts)
            ->get();
    }

    public function getAlmostBeginSeckills() {
        // 在秒杀开始60秒之前，生成秒杀提交的
        $before_seckill_start_time = env('SECKILL_URL_GENERATE_TIME', 60);
        return Seckill::where('begin_time', '<', date("Y-m-d H:i:s", time() + (int)$before_seckill_start_time))
            ->where('end_time', '>', date('Y-m-d H:i:s'))
            ->where('state', 1)
            ->get();
    }

    public function getSeckillUrl($seckill_id) {
        return Redis::get('seckill_url:'.$seckill_id);
    }

    public function checkSeckillUrl($seckill_id, $randkey) {
        if (! ($url_key = $this->getSeckillUrl($seckill_id))) {
            return false;
        }

        return $url_key == $randkey;
    }
}