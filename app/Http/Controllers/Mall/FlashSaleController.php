<?php
namespace App\Http\Controllers\Mall;
use App\Tools\JsonMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\Dao\FlashSaleRepository;
use App\Repository\Dao\FlashSaleGoodsRepository;

class FlashSaleController extends Controller
{
    private $flashSaleRepository, $flashSaleGoodsRepository;
    public function __construct(FlashSaleRepository $FlashSaleRepository, FlashSaleGoodsRepository $FlashSaleGoodsRepository) {
        $this->flashSaleRepository = $FlashSaleRepository;
        $this->flashSaleGoodsRepository = $FlashSaleGoodsRepository;
    }

    /**
     * 秒杀首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home() {
        $view = view('mall.goods_kill_home');
        return response($view, 200);
    }

    /**
     * 获取秒杀链接
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function flashSaleUrl(Request $request) {
        $request->validate([
            'skid'  => 'required|integer'
        ]);

        $skid = $request->input('skid');
        $flashUrl = $this->flashSaleRepository->getFlashSaleUrl($skid);
        if ($flashUrl) {
            return response()->json(["url"=>$flashUrl]);
        }

        return $this->jsonResponse(JsonMessage::INTERNAL_FORBIDDEN);
    }

    /**
     * 获取即将开始或者未结束的秒杀商品
     * @return array
     */
    public function getFlashSale() {
        $flashSale = $this->flashSaleRepository->getFlashSale()->toArray();
        foreach ($flashSale as $key=>$flash) {
            $flashSale[$key]['url'] = "/".get_html_cache_path($flash['id'], 'FlashSale');
        }

        return $flashSale;
    }

    /**
     * 获取库存
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFlashSaleStock(Request $request) {
        $saleId = $request->input('id');
        if (empty($saleId)) {
            return $this->jsonResponse(JsonMessage::INVALID_REQUEST);
        }

        return response()->json(['stock'=>$this->flashSaleRepository->getFlashSaleStock($saleId)]);
    }
}