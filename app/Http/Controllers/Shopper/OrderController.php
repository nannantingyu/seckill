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
        $gateway->setSignType(config('site.alipay_sign_type'));
        $gateway->setAppId(config('site.alipay_app_id'));
        $gateway->setPrivateKey(config('site.alipay_app_private_key'));
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
        $gateway->setSignType(config('site.alipay_sign_type'));
        $gateway->setAppId(config('site.alipay_app_id'));
        $gateway->setPrivateKey(config('site.alipay_app_private_key'));
        $gateway->setAlipayPublicKey(config('site.alipay_public_key'));
        $gateway->sandbox();

        $res = $gateway->completePurchase();
        $res->setParams($request->except(['_token']));

        try {
            $response = $res->send();
            Storage::put('notify.txt', json_encode($response->getData()));
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
        $data = json_decode('{
    "gmt_create": "2019-03-14 11:02:01",
    "charset": "UTF-8",
    "gmt_payment": "2019-03-14 11:02:08",
    "notify_time": "2019-03-14 11:02:09",
    "subject": "\u6d77\u8d3c\u5546\u57ce\u652f\u4ed8",
    "sign": "GLVQGMfv9LGdh9MiM6cle38z5XWSfWg6t138KF67KATyh3ArXqdwVARUAVfuJV\/yAxGVyZ\/yaBICVJK+6v\/\/DiOVbuqStHmoyyrHoVU0GGu+RTV0bYP8G9RC36uzcCljyrT7IAfMMz3b+hpxaPMVeWj98hCdsZNu6eH77K6Lgqw2DCotAdys7lo3urRPZewMo\/+6uWETDtbbRV7NlxenELbhfgvaDeIweSb6h+EuZNpG30acgNg1xJ2NV\/Tg0WwKp3RAjj32cQilOjbe6dN1av7NAaecjWZLnhdFiG+glpy23DkHmh1o2enZyj1Ss68qhYx9coFZHOubCAd7UTtBPw==",
    "buyer_id": "2088102177632869",
    "body": "\u84dd\u6ce2\u7403\u4f4e\u4ef7\u51fa\u552e\u5566",
    "invoice_amount": "1.00",
    "version": "1.0",
    "notify_id": "5fbf037cd0a4b164820ff9a337059ebmn1",
    "fund_bill_list": "[{\"amount\":\"1.00\",\"fundChannel\":\"ALIPAYACCOUNT\"}]",
    "notify_type": "trade_status_sync",
    "out_trade_no": "7b5adf78-4605-11e9-a402-00163e0e4978",
    "total_amount": "1.00",
    "trade_status": "TRADE_SUCCESS",
    "trade_no": "2019031422001432860500906052",
    "auth_app_id": "2016092800614064",
    "receipt_amount": "1.00",
    "point_amount": "0.00",
    "app_id": "2016092800614064",
    "buyer_pay_amount": "1.00",
    "sign_type": "RSA2",
    "seller_id": "2088102177617291",
    "_url": "\/order_alipay_notify"
}');

        $html = '<form action="order_alipay_notify" method="post">';
        foreach ($data as $key=>$val) {
            $html .= '<input type="text" name="'.$key.'" value="'.$val.'">';
        }

        $html .= '<button>提交</button></form>';
        Storage::put('test.blade.php', $html);
//        $orders = $this->seckillOrderRepository->listOrders();
//        return view('shopper.seckill_order_list', ['orders'=>$orders]);
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