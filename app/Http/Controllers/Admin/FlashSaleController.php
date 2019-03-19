<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Tools\JsonMessage;
use Illuminate\Http\Request;
use App\Repository\Dao\FlashSaleRepository;
use App\Jobs\GenerateFlashSalePageHtml;

class FlashSaleController extends Controller
{
    private $flashSaleRepository;
    public function __construct(FlashSaleRepository $flashSaleRepository) {
        $this->flashSaleRepository = $flashSaleRepository;
    }

    /**
     * 获取所有的秒杀商品
     * @return \Illuminate\Http\JsonResponse
     */
    public function list() {
        return response()->json(["data"=>$this->flashSaleRepository->all()]);
    }

    /**
     * 通过秒杀申请验证
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function checkFlashSale(Request $request) {
        $request->validate([
            "id"        => 'required|integer',
            "state"     => 'required|integer|max:1|min:-1'
        ]);

        $flashSale = $this->flashSaleRepository->updateById($request->input('id'), [
            "state" =>  $request->input('state')
        ]);

        if (is_null($flashSale)) {
            return $this->jsonResponse(JsonMessage::INTERNAL_ERROR);
        }

        GenerateFlashSalePageHtml::dispatch($flashSale);
        return $this->jsonResponse(JsonMessage::UPDATE_SUCCESS);
    }

    /**
     * 删除秒杀商品
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request) {
        $request->validate([
            "id"    =>  "required|integer"
        ]);

        $id = $request->input('id');
        if ($this->flashSaleRepository->deleteById($id)) {
            return $this->jsonResponse(JsonMessage::DELETE_SUCCESS);
        }

        return $this->jsonResponse(JsonMessage::INTERNAL_ERROR);
    }
}