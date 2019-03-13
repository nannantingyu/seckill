<?php
namespace App\Http\Controllers\Shopper;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Request;
use App\Http\Requests\Seckill;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Validator;
use Ramsey\Uuid\Uuid;
use App\Repository\SeckillRepository;
use App\Repository\SeckillGoodsRepository;
use App\Jobs\GenerateSeckillPageHtml;

class SeckillController extends Controller
{
    private $seckillRepository, $seckillGoodsRepository;
    public function __construct(SeckillRepository $seckillRepository, SeckillGoodsRepository $seckillGoodsRepository) {
        $this->seckillRepository = $seckillRepository;
        $this->seckillGoodsRepository = $seckillGoodsRepository;
    }

    /**
     * list shopper's goods
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listShopperSeckill() {
        return view('shopper.shopper_seckill_list', ['goods'=>$this->seckillRepository->listShopperSeckill()]);
    }

    /**
     * Show page of adding new seckill
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function seckillAddPage(Request $request) {
        $goods_id = $request->input('id');
        if (is_null($goods_id)) {
            return redirect('/seckill_goods_list');
        }

        $goods = $this->seckillGoodsRepository->find($goods_id);
        if (empty($goods)) {
            return redirect('/seckill_goods_list');
        }

        return view('shopper.shopper_seckill_add', ['goods'=>$goods]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function seckillUpdatePage(Request $request) {
        $id = $request->input('id');
        if (! is_null($id)) {
            return view('shopper.shopper_seckill_update', ['goods'=>$this->seckillRepository->find($id)]);
        }

        return redirect('/seckill_seckill_add');
    }

    /**
     * Insert seckill
     * @param Seckill $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeSeckill(Seckill $request) {
        $request->validated();

        $goods_id = $request->input('goods_id');
        if (is_null($goods_id)) {
            return redirect('/seckill_goods_list');
        }

        $goods = $this->seckillGoodsRepository->find($goods_id);
        if (empty($goods)) {
            return redirect('/seckill_goods_list');
        }

        $detail_pictures = [];
        foreach ($request->file('pictures') as $file) {
            array_push($detail_pictures, img_save_path($file->store('/shopper/seckill_detail/'.date("Ymd"))));
        }

        $seckill = $this->seckillRepository->createSeckill(array_merge($request->except('pictures'), ['pictures'=>json_encode($detail_pictures), 'goods_id'=>$goods->id]));

        GenerateSeckillPageHtml::dispatch($seckill)->delay(now()->addSeconds(10));
        return redirect('/seckill_list');
    }

    /**
     * update Seckill
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function updateSeckill(Request $request) {
        $old_pictures = $request->input('old_pictures');
        if(! is_null($request->file('pictures'))) {
            foreach ($request->file('pictures') as $file) {
                array_push($old_pictures, img_save_path($file->store('/shopper/seckill/'.date("Ymd"))));
            }
        }

        $datas = array_merge($request->except(['pictures', '_token', 'old_pictures']), ['pictures'=>$old_pictures]);
        $validator = Validator::make($datas, [
            "title"             =>  "required|between:2,128",
            "description"       =>  "required|between:2,256",
            "ori_price"         =>  "required|numeric",
            "kill_price"        =>  "required|numeric",
            "begin_time"        =>  "required|date",
            "end_time"          =>  "required|date",
            "quantity"          =>  "required|integer|min:0",
            "stock"             =>  "required|integer|min:0"
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

        $seckill = $this->seckillRepository->updateSeckill($datas);
        GenerateSeckillPageHtml::dispatch($seckill)->delay(now()->addSeconds(10));
        return back();
    }

    /**
     * delete seckill
     * @param Request $request
     * @return mixed
     */
    public function deleteSeckill(Request $request) {
        $id = $request->input("id");
        if (empty($id) or !is_numeric($id)) {
            return response(["message"=>"Params error", "code"=>10001], 401);
        }

        if($this->seckillRepository->deleteSeckill($request->input("id"))) {
            return response()->json(["code"=>10000]);
        }

        return response()->json(["code"=>10002, "message"=>"unhandle error"], 501);
    }

    public function listSeckill(Request $request) {
        return view('shopper.shopper_seckill_list', ['goods'=>$this->seckillRepository->listAllSeckill()]);
    }

    public function getSeckillUrl(Request $request) {
        $request->validate([
            'skid'  => 'required|integer'
        ]);

        $skid = $request->input('skid');
        $randkey = $this->seckillRepository->getSeckillUrl($skid);
        if ($randkey) {
            return response()->json(["url"=>"/seckill_buy/".$skid."/".$randkey]);
        }

        return response()->json(["error"=>"Forbidden"], 403);
    }
}