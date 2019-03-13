<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/25
 * Time: 5:52 PM
 */

namespace App\Http\Controllers\Shopper;
use Illuminate\Http\Request;
use App\Http\Requests\Seckill;
use App\Http\Requests\SeckillGoods;
use App\Http\Requests\SeckillOrder;
use App\Http\Requests\SeckillShopper;
use Illuminate\Support\Facades\DB;
use App\Repository\SeckillGoodsRepository;
use App\Http\Controllers\Controller;
use Validator;
use Cookie;

class SeckillGoodsController extends Controller
{
    private $seckillGoodsRepository;
    public function __construct(SeckillGoodsRepository $seckillGoodsRepository) {
        $this->seckillGoodsRepository = $seckillGoodsRepository;
    }

    /**
     * list all shopper's goods
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listSeckillGoods() {
        return view('shopper.seckill_goods_list', ['goods'=>$this->seckillGoodsRepository->listSeckillGoods()]);
    }

    /**
     * Show page of adding new goods
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function seckillGoodsAddPage(Request $request) {
        dump(Cookie::get('name'));
        return view('shopper.seckill_goods_add');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function seckillGoodsUpdatePage(Request $request) {
        $id = $request->input('id');
        if (! is_null($id)) {
            return view('shopper.seckill_goods_update', ['goods'=>$this->seckillGoodsRepository->find($id)]);
        }

        return redirect('/seckill_goods_add');
    }

    /**
     * Insert seckill goods
     * @param SeckillGoods $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeSeckillGoods(SeckillGoods $request) {
        $request->validated();
        $detail_pictures = [];
        foreach ($request->file('detail_pictures') as $file) {
            array_push($detail_pictures, img_save_path($file->store('/shopper/goods_detail/'.date("Ymd"))));
        }

        $this->seckillGoodsRepository->createSeckillGoods(array_merge($request->except('detail_pictures'), ['detail_pictures'=>$detail_pictures]));

        return redirect('/seckill_goods_list');
    }

    /**
     * update Seckill goods
     * @param SeckillGoods $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function updateSeckillGoods(Request $request) {
        $old_detail_pictures = $request->input('old_detail_pictures');
        if(! is_null($request->file('detail_pictures'))) {
            foreach ($request->file('detail_pictures') as $file) {
                array_push($old_detail_pictures, img_save_path($file->store('/shopper/goods_detail/'.date("Ymd"))));
            }
        }

        $datas = array_merge($request->except(['detail_pictures', '_token', 'old_detail_pictures']), ['detail_pictures'=>$old_detail_pictures]);
        $validator = Validator::make($datas, [
                "goods_name"        =>  "required|between:2,256",
                "place"             =>  "required|between:2,256",
                "brand"             =>  "required|between:2,128",
                "weight"            =>  "required|Numeric|min:0|max:10000",
                "detail_pictures"   =>  "required"
            ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $id = $request->input("id");
        if (empty($id) or !is_numeric($id)) {
            return response(["message"=>"Params error", "code"=>10001], 401);
        }

        if($this->seckillGoodsRepository->updateSeckillGoods($datas)) {
            return back();
        }

        return back();
    }

    /**
     * delete seckill goods
     * @param Request $request
     * @return mixed
     */
    public function deleteSeckillGoods(Request $request) {
        $id = $request->input("id");
        if (empty($id) or !is_numeric($id)) {
            return response(["message"=>"Params error", "code"=>10001], 401);
        }

        if($this->seckillGoodsRepository->deleteSeckillGoods($request->input("id"))) {
            return response()->json(["code"=>10000]);
        }

        return response()->json(["code"=>10002, "message"=>"unhandle error"], 501);

    }
}