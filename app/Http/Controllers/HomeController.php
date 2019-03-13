<?php

namespace App\Http\Controllers;

use App\Events\UserRegisted;
use Illuminate\Http\Request;
use Auth;
use App\Repository\SeckillRepository;
use Cookie;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Omnipay\Omnipay;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    private $seckillRepository;
    public function __construct(SeckillRepository $seckillRepository)
    {
        $this->seckillRepository = $seckillRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function mail() {
        event(new UserRegisted(Auth::user()));
    }

    public function test() {
//        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
//        $channel = $connection->channel();
//        $exchange_name = "php_exchange";
//        $channel->exchange_declare($exchange_name, "fanout", false, false, false);
//        list($queue_name, ,) = $channel->queue_declare("", false, false, true, false);
//        $channel->queue_bind($queue_name, $exchange_name, '');
//
//        $channel->basic_consume($queue_name, '', false, true, false, false, function($body) {
//            dump($body);
//        });



    }

    public function test2(Request $request) {
        $data = $request->all();
        Storage::put('alipy.log', json_encode($data));
    }
}
