<?php
namespace App\Http\Controllers\Mall;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Tools\JsonMessage;
use Illuminate\Http\Request;
use App\Repository\Dao\FlashSaleRepository;
use App\Repository\Dao\OrderRepository;
use Illuminate\Http\Response;
use App\Services\PayFactory;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private $flashSaleRepository;
    private $orderRepository;
    public function __construct(FlashSaleRepository $flashSaleRepository, OrderRepository $orderRepository)
    {
        $this->flashSaleRepository = $flashSaleRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * 订单列表页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pageOrderList() {
        $orders = $this->orderRepository->getUserOrderList();

        return view('mall.order_list', ['orders'=>$orders]);
    }

    /**
     * 填充订单页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pageOrderFill(Request $request) {
        $order_no = $request->input('no');
        if (! ($order = $this->orderRepository->getByColumn($order_no, 'order_no'))) {
            return $this->pageResponse('404');
        }

        return view("mall.order", ['order'=>$order, 'goods'=>$this->flashSaleRepository->getById($order->goods_id)]);
    }

    /**
     * 订单详情页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function pageOrderInfo(Request $request) {
        $order_no = $request->input('order_no');
        if ($order_no) {
            $order = $this->orderRepository->getByColumn($order_no, 'order_no');
            if (! is_null($order)) {
                return view('mall.order_info', ['order'=>$order]);
            }
        }

        return back();
    }

    /**
     * 订单收件信息Action
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function orderFill(Request $request) {
        $request->validate([
            "phone"     =>  "required|regex:/^1[3,5,7,8,9]\d{9}$/",
            "address"   =>  "required|between:5,50",
            "username"  =>  "required|between:2,20",
            'order_no'  =>  "required|between:36,36"
        ]);

        $order_no = $request->input('order_no');
        $updates = $request->only(["phone", "address", "username", "order_no"]);
        if ($this->orderRepository->updateByColumn($order_no, 'order_no', $updates)) {
            return redirect('/orderInfo?order_no='.$order_no);
        }

        return back();
    }

    /**
     * 下单
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|Response
     */
    public function orderMake(Request $request) {
        $request->validate([
            "num"       =>  "required|integer|min:0",
            "id"  =>  "required|integer"
        ]);

        $saleId = $request->input("id");
        $orderNum = (int)$request->input('num');
        /**
         * 1. 检查商品余量，不足的直接返回错误
         */
        $flashSale = $this->flashSaleRepository->getById($saleId);
        $now = date("Y-m-d H:i:s");
        if (empty($flashSale) or $flashSale->stock <= 0 or $flashSale->end_time < $now) {
            return $this->jsonResponse(JsonMessage::FLASH_FINISHED_ERROR);
        }

        if ($order = $this->orderRepository->create([
            "user_id"       =>  Auth::id(),
            "order_no"      =>  uuid(),
            "pay_status"    =>  0,
            "goods_id"      =>  $flashSale->goods_id,
            "order_price"   =>  $flashSale->kill_price * $orderNum,
            "merchant_id"    =>  $flashSale->merchant_id,
        ])) {
            return response()->json(['order'=>[
                'title'         => $flashSale->title,
                'order_no'      => $order->order_no,
                'order_price'   => $request->input('num') * $flashSale->kill_price
            ]]);
        }

        return $this->jsonResponse(JsonMessage::FLASH_FAILED);
    }


    /**
     * 订单支付
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function orderPay(Request $request) {
        $orderNo = $request->input('order_no');
        if (is_null($orderNo) or strlen($orderNo) != 36) {
            return $this->pageResponse('404');
        }

        $orderInfo = $this->orderRepository->getByColumn($orderNo, 'order_no');
        $flashSaleGoods = $this->flashSaleRepository->getById($orderInfo->goods_id);
        $subject = '"海贼商城支付"';

        $payWay = $request->input('payWay', 'aliPay');
        try{
            PayFactory::getPay($payWay)
                ->pay($orderInfo->order_no, $orderInfo->order_price, $subject, $flashSaleGoods->title);
        }
        catch (GeneralException $exception) {
            return $this->jsonResponse(JsonMessage::INTERNAL_ERROR);
        }

        return $this->jsonResponse(JsonMessage::COMMON_SUCCESS);
    }

    /**
     * 支付宝支付回调通知接口
     * @param Request $request
     * @return Response
     * @throws \App\Exceptions\GeneralException
     */
    public function alipayNotify(Request $request) {
        $status = PayFactory::getPay('aliPay')->checkPayStatus($_POST);

        if ($status) {
            $order_no= $request->input('out_trade_no');
            $pay_time = $request->input('notify_time');
            $pay_order_no = $request->input('trade_no');

            $this->orderRepository->updateByColumn($order_no, 'order_no', [
                'pay_time'      =>  $pay_time,
                'pay_order_no'  =>  $pay_order_no
            ]);

            return new Response('success');
        }

        return new Response('failure');
    }
}