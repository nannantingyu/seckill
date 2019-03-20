<?php
namespace App\Http\Controllers\Merchant;
use App\Http\Controllers\Controller;
use App\Repository\Dao\FlashSaleRepository;
use App\Repository\Dao\FlashSaleGoodsRepository;
use App\Http\Requests\FlashSaleRequest;
use Illuminate\Http\Request;
use App\Facades\MerchantAuth;
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
        $merchantId = MerchantAuth::id();
        return view('merchant.flash_sale_list', ['goods'=>$this->flashSaleRepository->getMerchantFlashSale($merchantId)]);
    }

    /**
     * 添加秒杀页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function pageFlashSaleAdd(Request $request) {
        $goods_id = $request->input('id');
        if (is_null($goods_id)) {
            return redirect(route('merchantGoodsList'));
        }

        $goods = $this->flashSaleGoodsRepository->getByColumn($goods_id, 'goods_id');
        if (empty($goods)) {
            return redirect(route('merchantGoodsList'));
        }

        return view('merchant.flash_sale_add', ['goods'=>$goods]);
    }

    /**
     * 秒杀商品更新页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pageFlashSaleUpdate(Request $request) {
        $id = $request->input('id');
        if (! is_null($id)) {
            return view('merchant.flash_sale_update', [
                'goods'=>$this->flashSaleRepository->getById($id)
            ]);
        }

        return redirect(route('merchantGoodsList'));
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
            return redirect(route('merchantGoodsList'));
        }

        $goods = $this->flashSaleGoodsRepository->getByColumn($goods_id, 'goods_id');
        if (empty($goods)) {
            return redirect(route('merchantGoodsList'));
        }

        $detailPictures = [];
        foreach ($request->file('pictures') as $file) {
            array_push($detailPictures, img_save_path($file->store('uploads/merchant/flash_sale/'.date("Ymd"))));
        }

        $this->flashSaleRepository->create([
            "goods_id"      =>  $goods->id,
            "pictures"      =>  json_encode($detailPictures),
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

        return redirect(route('merchantGoodsList'));
    }

    /**
     * 更新秒杀商品
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Illuminate\Support\MessageBag
     */
    public function update(Request $request) {
        $oldPictures = $request->input('old_pictures');
        if(! is_null($request->file('pictures'))) {
            foreach ($request->file('pictures') as $file) {
                array_push($oldPictures, img_save_path($file->store('uploads/shopper/FlashSale/'.date("Ymd"))));
            }
        }

        $data = array_merge($request->except(['pictures', '_token', 'old_pictures']), ['pictures'=>json_encode($oldPictures)]);
        $validator = Validator::make($data, [
            "title"             =>  "required|between:2,128",
            "description"       =>  "required|between:2,256",
            "ori_price"         =>  "required|numeric",
            "kill_price"        =>  "required|numeric",
            "begin_time"        =>  "required|date",
            "end_time"          =>  "required|date",
            "quantity"          =>  "required|integer|min:0",
            "stock"             =>  "required|integer|min:0",
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $id = $request->input("id");
        if (empty($id) or !is_numeric($id)) {
            return response(["message"=>"Params error", "code"=>10001], 401);
        }

        $data['state'] = -2;
        $this->flashSaleRepository->updateById($id, $data);
//        return $this->jsonResponse(JsonMessage::UPDATE_SUCCESS);
        return redirect(route('merchantFlashSaleList'));
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