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
                case "aliPay":
                    self::$gateways[$payway] = new Alipay();
                    break;
                case "wxPay":
                    self::$gateways[$payway] = new Alipay();
                    break;
                default:
                    throw new GeneralException();
            }
        }

        return self::$gateways[$payway];
    }
}