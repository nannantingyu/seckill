<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/28
 * Time: 3:51 PM
 */

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SeckillShopper;
use App\Repository\SeckillShopperRepository;
use App\Http\Requests\SeckillShopper as SeckillShopperRequest;

class ShopperController extends Controller
{
    private $seckillShopperRepository;
    public function __construct(SeckillShopperRepository $seckillShopperRepository) {
        $this->seckillShopperRepository = $seckillShopperRepository;
    }

    /**
     * Get all shoppers
     * @return \Illuminate\Http\JsonResponse
     */
    public function list() {
        return response()->json(["data"=>$this->seckillShopperRepository->listSeckillShopper()]);
    }

    /**
     * add new shopper
     */
    public function add_shopper(SeckillShopperRequest $request) {
        $request->validated();
        return response()->json(['data'=>['shopper_id'=>$this->seckillShopperRepository->createSeckillShopper($request->except('_token'))]]);
    }

    /**
     * update new shopper
     */
    public function update_shopper(Request $request) {
        $request->validate([
            "account_name"          =>  "required|between:5,32",
            "nick_name"             =>  "required|between:2,256",
            "scope"                 =>  "required",
            "email"                 =>  "required",
            "avatar"                =>  "required",
        ]);

        $request->validate([
            "id"    =>  "required|integer"
        ]);

        return response()->json(['data'=>['shopper_id'=>$this->seckillShopperRepository->updateSeckillShopper($request->except('_token'))]]);
    }

    /**
     * Delete shopper
     * @param Request $request
     * @return int
     */
    public function delete_shopper(Request $request) {
        $request->validate([
            "id"    =>  "required|integer"
        ]);

        if ($this->seckillShopperRepository->deleteSeckillShopper($request->input('id'))) {
            return response()->json("");
        }

        return response()->json(["errors"=>["sever error"=>["Failed to delete"]]], 500);
    }
}