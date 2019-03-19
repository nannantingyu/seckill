<?php
namespace App\Http\Controllers\Merchant;
use App\Http\Controllers\Controller;
use App\Repository\Dao\FlashSaleRepository;
use App\Repository\Dao\FlashSaleGoodsRepository;
use App\Http\Requests\FlashSaleRequest;
use Illuminate\Http\Request;
use App\Facades\MerchantAuth;
use App\Jobs\GenerateFlashSalePageHtml;
use Illuminate\Support\Facades\Validator;
use App\Tools\JsonMessage;

class FlashSaleController extends Controller
{
    private $flashSaleRepository, $flashSaleGoodsRepository;
    public function __construct(FlashSaleRepository $FlashSaleRepository, FlashSaleGoodsRepository $FlashSaleGoodsRepository) {
        $this->flashSaleRepository = $FlashSaleRepository;
        $this->flashSaleGoodsRepository = $FlashSaleGoodsRepository;
    }

    /**
     * 秒杀列表页面（商家管理）
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pageFlashSale() {
        return view('shopper.flash_sale_list', ['goods'=>$this->flashSaleRepository->all()]);
    }

    /**
     * 添加秒杀页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function pageFlashSaleAdd(Request $request) {
        $goods_id = $request->input('id');
        if (is_null($goods_id)) {
            return redirect('/FlashSale_goods_list');
        }

        $goods = $this->flashSaleGoodsRepository->getById($goods_id);
        if (empty($goods)) {
            return redirect('/FlashSale_goods_list');
        }

        return view('shopper.shopper_FlashSale_add', ['goods'=>$goods]);
    }

    /**
     * 秒杀商品更新页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pageFlashSaleUpdate(Request $request) {
        $id = $request->input('id');
        if (! is_null($id)) {
            return view('shopper.flash_sale_update', [
                'goods'=>$this->flashSaleRepository->find($id)
            ]);
        }

        return redirect('/FlashSale_FlashSale_add');
    }

    /**
     * 添加秒杀商品
     * @param FlashSaleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(FlashSaleRequest $request) {
        $request->validated();

        $goods_id = $request->input('goods_id');
        if (is_null($goods_id)) {
            return redirect('/FlashSale_goods_list');
        }

        $goods = $this->flashSaleGoodsRepository->first($goods_id);
        if (empty($goods)) {
            return redirect('/FlashSale_goods_list');
        }

        $detail_pictures = [];
        foreach ($request->file('pictures') as $file) {
            array_push($detail_pictures, img_save_path($file->store('uploads/shopper/FlashSale_detail/'.date("Ymd"))));
        }

        $FlashSale = $this->flashSaleRepository->create([
            "goods_id"      =>  $goods->id,
            "pictures"      =>  json_encode($detail_pictures),
            "title"         =>  $request->input('title'),
            "description"   =>  $request->input('description'),
            "ori_price"   =>  $request->input('ori_price'),
            "kill_price"   =>  $request->input('kill_price'),
            "merchant_id"   =>  MerchantAuth::id(),
            "begin_time"   =>  $request->input('begin_time'),
            "end_time"   =>  $request->input('end_time'),
            "quantity"   =>  $request->input('quantity'),
            "stock"   =>  $request->input('stock'),
        ]);

        GenerateFlashSalePageHtml::dispatch($FlashSale);
        return redirect('/flash_sale_list');
    }

    /**
     * 更新秒杀商品
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Illuminate\Support\MessageBag
     */
    public function update(Request $request) {
        $old_pictures = $request->input('old_pictures');
        if(! is_null($request->file('pictures'))) {
            foreach ($request->file('pictures') as $file) {
                array_push($old_pictures, img_save_path($file->store('uploads/shopper/FlashSale/'.date("Ymd"))));
            }
        }

        $data = array_merge($request->except(['pictures', '_token', 'old_pictures']), ['pictures'=>$old_pictures]);
        $validator = Validator::make($data, [
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
            return $validator->errors();
        }

        $id = $request->input("id");
        if (empty($id) or !is_numeric($id)) {
            return response(["message"=>"Params error", "code"=>10001], 401);
        }

        $FlashSale = $this->flashSaleRepository->updateById($id, $data);
        GenerateFlashSalePageHtml::dispatch($FlashSale);
        return $this->jsonResponse(JsonMessage::UPDATE_SUCCESS);
    }

    /**
     * 删除秒杀商品
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request) {
        $id = $request->input("id");
        if (empty($id) or !is_numeric($id)) {
            return $this->jsonResponse(JsonMessage::PAGE_NOT_FOUND);
        }

        if($this->flashSaleRepository->deleteById($id)) {
            return $this->jsonResponse(JsonMessage::DELETE_SUCCESS);
        }

        return $this->jsonResponse(JsonMessage::INTERNAL_ERROR);
    }
}