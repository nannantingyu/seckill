<?php

namespace App\Http\Controllers\Mall;

use App\Events\UserRegisted;
use Illuminate\Http\Request;
use Auth;
use App\Repository\FlashSaleRepository;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    private $FlashSaleRepository;
    public function __construct(FlashSaleRepository $FlashSaleRepository)
    {
        $this->FlashSaleRepository = $FlashSaleRepository;
    }

    public function mail() {
        event(new UserRegisted(Auth::user()));
    }
}
