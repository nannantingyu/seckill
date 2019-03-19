<?php
namespace App\Services;
use App\Exceptions\GeneralException;

class PayFactory {
    public static $gateways = [];

    /**
     * @param $payway
     * @return mixed
     * @throws GeneralException
     */
    public static function getPay($payway) {
        if (! isset(self::$gateways[$payway])) {
            switch ($payway) {
                case "alipay":
                    self::$gateways[$payway] = new Alipay();
                    break;
                case "wxpay":
                    self::$gateways[$payway] = new Alipay();
                    break;
                default:
                    throw new GeneralException();
            }
        }

        return self::$gateways[$payway];
    }
}