<?php
namespace App\Http\Controllers\Shopper;
use App\Http\Controllers\Controller;
use foo\bar;
use Illuminate\Cache\Repository;
use Illuminate\Http\Request;
use App\Repository\SeckillRepository;
use App\Repository\SeckillOrderRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Omnipay\Omnipay;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    private $seckillRepository;
    private $seckillOrderRepository;
    public function __construct(SeckillRepository $seckillRepository, SeckillOrderRepository $seckillOrderRepository)
    {
        $this->seckillRepository = $seckillRepository;
        $this->seckillOrderRepository = $seckillOrderRepository;
    }

    public function orderPage(Request $request) {
        $request->validate([
            "num"       =>  "required|integer|min:0",
            "id"  =>  "required|integer"
        ]);

        /**
         * 1. 检查商品余量，不足的直接返回错误
         */
        $seckill = $this->seckillRepository->find($request->input("id"));
        $now = date("Y-m-d H:i:s");
        if (empty($seckill) or $seckill->stock <= 0 or $seckill->end_time < $now) {
            return response("秒杀已结束", 422);
        }

        if ($order_no = $this->seckillOrderRepository->createOrder([
            "goods_id"      =>  $seckill->goods_id,
            "order_price"   =>  $seckill->kill_price * $request->input('num'),
            "shopper_id"    =>  $seckill->shopper_id,
        ])) {
            return response()->json(['order'=>[
                'title'         => $seckill->title,
                'order_no'      => $order_no,
                'order_price'   => $request->input('num') * $seckill->kill_price
            ]]);
        }

        return response("下单失败", 422);
    }

    public function orderFillPage(Request $request) {
        $order_no = $request->input('no');
        if (is_null($order_no) or strlen($order_no) != 36 or ! ($order = $this->seckillOrderRepository->findOrder($order_no))) {
            return back();
        }

        return view("shopper.seckill_order", ['order'=>$order]);
    }

    public function orderFill(Request $request) {
        $request->validate([
            "phone"     =>  "required|regex:/^1[3,5,7,8,9]\d{9}$/",
            "address"   =>  "required|between:5,50",
            "username"  =>  "required|between:2,20",
            'order_no'  =>  "required"
        ]);

        if ($this->seckillOrderRepository->fillOrder($request->only(["phone", "address", "username", "order_no"]))) {
            return redirect('/orderinfo?order_no='.$request->input('order_no'));
        }
    }

    public function orderAliPay(Request $request) {
        $request->validate([
            'order_no'  =>  "required"
        ]);

        $order = $this->seckillOrderRepository->findOrder($request->input('order_no'));
        $gateway = Omnipay::create('Alipay_AopPage');
        $gateway->setSignType('RSA2');
        $gateway->setAppId('2016092800614064');
        $gateway->setPrivateKey(config('site.alipay_private_key'));
        $gateway->setAlipayPublicKey(config('site.alipay_public_key'));
        $gateway->setNotifyUrl(config('site.alipay_notify_url'));
        $gateway->setReturnUrl(config('site.alipay_return_url'));
        $gateway->sandbox();

        $seckill = $this->seckillRepository->find($order->goods_id);
        $response = $gateway->purchase()->setBizContent([
            'subject'           =>  "海贼商城支付",
            'out_trade_no'      =>  $order->order_no,
            'total_amount'      =>  $order->order_price,
            'product_code'      =>  'FAST_INSTANT_TRADE_PAY',
            'body'              =>  $seckill->title,
            'timeout_express'   =>  "10m",
            'charge_type'       =>  'wallet'
        ])->send();

        $response->redirect();
    }

    /**
     * 阿里支付回调
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function alipayCallback(Request $request) {
        return view('shopper.seckill_pay_failed');
    }

    public function alipayNotify(Request $request) {
        $gateway = Omnipay::create('Alipay_AopPage');
        $gateway->setSignType('RSA2');
        $gateway->setAppId('2016092800614064');
        $gateway->setPrivateKey(config('site.alipay_private_key'));
        $gateway->setAlipayPublicKey(config('site.alipay_public_key'));

        $res = $gateway->completePurchase();
        $res->setParams($_POST);

        try {
            $response = $res->send();
            if ($response->isPaid()) {
                $order_no= $request->input('out_trade_no');
                $pay_time = $request->input('notify_time');
                $pay_order_no = $request->input('trade_no');
                if ($this->seckillOrderRepository->alipaySuccess($order_no, $pay_time, $pay_order_no)) {
                    return new Response('failure');
                }

                return new Response('success');
            }
        }
        catch (Exception $e) {
            return new Response('failure');
        }
    }

    public function orderList(Request $request) {
        $orders = $this->seckillOrderRepository->listOrders();
        return view('shopper.seckill_order_list', ['orders'=>$orders]);
    }

    public function orderinfoPage(Request $request) {
        $order_no = $request->input('order_no');
        if ($order_no) {
            $order = $this->seckillOrderRepository->findOrder($order_no);
            if (! is_null($order)) {
                return view('shopper.seckill_order_info', ['order'=>$order]);
            }
        }
    }
}