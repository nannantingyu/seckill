<?php

namespace App\Http\Controllers\Shopper;

use App\Events\UserRegisted;
use Illuminate\Http\Request;
use Auth;
use App\Repository\SeckillRepository;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    private $seckillRepository;
    public function __construct(SeckillRepository $seckillRepository)
    {
        $this->seckillRepository = $seckillRepository;
    }

    public function mail() {
        event(new UserRegisted(Auth::user()));
    }
}
