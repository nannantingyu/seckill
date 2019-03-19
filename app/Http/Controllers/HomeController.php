<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
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

        return view('test');
    }

    public function test2(Request $request) {
        $data = $request->all();
        Storage::put('alipy.log', json_encode($data));
    }
}
