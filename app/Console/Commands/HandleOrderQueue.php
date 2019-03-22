<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AmqpFactory;
use App\Repository\Dao\OrderRepository;

class HandleOrderQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \ErrorException
     */
    public function handle()
    {
        AmqpFactory::consumeDirect(AmqpFactory::DIRECT_EXCHANGE, AmqpFactory::DIRECT_QUEUE, function($message) {
            $content = json_decode($message->body, true);
            app()->make(OrderRepository::class)->makeOrder($content['saleId'], $content['userId'], $content['num']);

            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
        });
    }
}
