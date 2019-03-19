<?php
namespace App\Http\Controllers\Mall;
use App\Tools\JsonMessage;
use Illuminate\Http\Request;
use App\Http\Requests\FlashSaleGoodsRequest;
use App\Repository\Dao\FlashSaleGoodsRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Facades\MerchantAuth;

class FlashSaleGoodsController extends Controller
{
    private $flashSaleGoodsRepository;
    public function __construct(FlashSaleGoodsRepository $FlashSaleGoodsRepository) {
        $this->flashSaleGoodsRepository = $FlashSaleGoodsRepository;
    }

    /**
     * 商品列表页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pageList() {
        return view('shopper.flashSale_goods_list',
            ['goods'=>$this->flashSaleGoodsRepository->all()]);
    }

    /**
     * 商品添加页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pageAdd() {
        return view('shopper.flashSale_goods_add');
    }

    /**
     * 商品更新页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function pageUpdate(Request $request) {
        $id = $request->input('id');
        if (! is_null($id)) {
            return view('shopper.flashSale_goods_update',
                ['goods'=>$this->flashSaleGoodsRepository->find($id)]);
        }

        return redirect('/flashSale_goods_list');
    }

    /**
     * 添加新的商品
     * @param FlashSaleGoodsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(FlashSaleGoodsRequest $request) {
        $request->validated();
        $detail_pictures = [];
        foreach ($request->file('detail_pictures') as $file) {
            array_push($detail_pictures, img_save_path($file->store('uploads/shopper/goods_detail/'.date("Ymd"))));
        }

        $data = array_merge($request->except('detail_pictures'), [
            'detail_pictures'   =>  json_encode($detail_pictures),
            'merchant_id'        =>  MerchantAuth::id(),
            'goods_id'          =>  $this->flashSaleGoodsRepository->newGoodsId(),
        ]);

        $this->flashSaleGoodsRepository->create($data);


        return $this->jsonResponse(JsonMessage::INSERT_SUCCESS);
    }

    /**
     * 更新商品
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request) {
        $old_detail_pictures = $request->input('old_detail_pictures');
        if(! is_null($request->file('detail_pictures'))) {
            foreach ($request->file('detail_pictures') as $file) {
                array_push($old_detail_pictures, img_save_path($file->store('uploads/shopper/goods_detail/'.date("Ymd"))));
            }
        }

        $data = array_merge($request->except(['detail_pictures', '_token', 'old_detail_pictures']),
            ['detail_pictures'=>json_encode($old_detail_pictures)]);

        $validator = Validator::make($data, [
                "id"                =>  "required|integer",
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

        if($this->flashSaleGoodsRepository->updateById($request->input('id'), $data)) {
            return $this->jsonResponse(JsonMessage::UPDATE_SUCCESS);
        }

        return $this->jsonResponse(JsonMessage::MODEL_NOT_FOUND);
    }

    /**
     * 删除商品
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request) {
        $request->validate([
            'id'    =>  "required|integer"
        ]);

        if($this->flashSaleGoodsRepository->deleteById($request->input("id"))) {
            return response()->json(JsonMessage::DELETE_SUCCESS);
        }

        return $this->jsonResponse(JsonMessage::MODEL_NOT_FOUND);
    }
}