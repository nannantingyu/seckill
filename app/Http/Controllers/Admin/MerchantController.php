<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Tools\JsonMessage;
use Illuminate\Http\Request;
use App\Repository\Dao\MerchantRepository;
use App\Http\Requests\MerchantRequest;

class MerchantController extends Controller
{
    private $merchantRepository;
    public function __construct(MerchantRepository $merchantRepository) {
        $this->merchantRepository = $merchantRepository;
    }

    /**
     * 获取所有的商家
     * @return \Illuminate\Http\JsonResponse
     */
    public function list() {
        return response()->json(["data"=>$this->merchantRepository->all()]);
    }

    /**
     * 添加商家
     * @param MerchantRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(MerchantRequest $request) {
        $request->validated();
        $merchant = $this->merchantRepository->create($request->except('_token'));
        return response()->json(['data'=>['merchant_id'=>$merchant->id]]);
    }

    /**
     * 更新商家
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request) {
        $request->validate([
            "id"            =>  "required|integer",
            "account_name"  =>  "required|between:5,32",
            "nick_name"     =>  "required|between:2,256",
            "scope"         =>  "required",
            "email"         =>  "required",
            "avatar"        =>  "required",
        ]);

        $merchantId = $request->input('id');
        $this->merchantRepository->updateById($merchantId, $request->except('_token'));

        return $this->jsonResponse(JsonMessage::UPDATE_SUCCESS);
    }

    /**
     * 删除商家
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request) {
        $request->validate([
            "id"    =>  "required|integer"
        ]);

        if ($this->merchantRepository->deleteById($request->input('id'))) {
            return $this->jsonResponse(JsonMessage::DELETE_SUCCESS);
        }

        return $this->jsonResponse(JsonMessage::INTERNAL_ERROR);
    }
}