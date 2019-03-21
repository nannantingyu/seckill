<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/3/15
 * Time: 1:44 PM
 */

namespace App\Services;
use Omnipay\Omnipay;

class Alipay implements PayInterface
{
    /**
     * 调起支付宝支付
     * @param $order_no string 订单号
     * @param $total_amount float 订单金额
     * @param $subject string 订单主题
     * @param $content string 订单详情
     */
    public function pay($order_no, $total_amount, $subject, $content)
    {
        $gateway = Omnipay::create('Alipay_AopPage');
        $gateway->setSignType(config('site.alipay_sign_type'));
        $gateway->setAppId(config('site.alipay_app_id'));
        $gateway->setPrivateKey(config('site.alipay_app_private_key'));
        $gateway->setAlipayPublicKey(config('site.alipay_public_key'));
        $gateway->setNotifyUrl(route('orderAliPayNotify'));
        $gateway->setReturnUrl(route('orderList'));
        $gateway->sandbox();

        $response = $gateway->purchase()->setBizContent([
            'subject'           =>  $subject,
            'out_trade_no'      =>  $order_no,
            'total_amount'      =>  $total_amount,
            'product_code'      =>  'FAST_INSTANT_TRADE_PAY',
            'body'              =>  $content,
            'timeout_express'   =>  "10m",
            'charge_type'       =>  'wallet'
        ])->send();

        $response->redirect();
    }

    /**
     * 检查订单支付状态
     * @param array $params
     * @return bool
     */
    public function checkPayStatus(array $params) : bool
    {
        $gateway = Omnipay::create('Alipay_AopPage');
        $gateway->setSignType(config('site.alipay_sign_type'));
        $gateway->setAppId(config('site.alipay_app_id'));
        $gateway->setPrivateKey(config('site.alipay_app_private_key'));
        $gateway->setAlipayPublicKey(config('site.alipay_public_key'));
        $gateway->sandbox();

        $res = $gateway->completePurchase();
        $res->setParams($params);

        try {
            $response = $res->send();
            return $response->isPaid();
        }
        catch (Exception $e) {
            return false;
        }
    }
}