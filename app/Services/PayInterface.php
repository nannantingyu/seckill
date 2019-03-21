<?php
namespace App\Services;

interface PayInterface {
    public function pay($order_no, $total_amount, $subject, $content);
    public function checkPayStatus(array $params);
}