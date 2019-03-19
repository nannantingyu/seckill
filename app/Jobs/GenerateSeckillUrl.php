<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repository\Dao\FlashSaleRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class GenerateFlashSaleUrl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 5;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $FlashSale_goods = app()->make(FlashSaleRepository::class)->getAlmostBeginFlashSales();
        foreach ($FlashSale_goods as $goods) {
            Redis::executeRaw(['set', 'flash_sale_url_key:'.$goods->id, uuid(), 'ex', strtotime($goods->end_time) - time(), 'nx']);
        }

        // 记录日志
        echo "生成秒杀商品成功".PHP_EOL;
    }
}
