<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repository\Dao\FlashSaleRepository;
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
     * 生成秒杀链接，同时将库存保存到redis中
     *
     * @return void
     */
    public function handle()
    {
        $FlashSale_goods = app()->make(FlashSaleRepository::class)->getAlmostBeginFlashSales();
        foreach ($FlashSale_goods as $goods) {
            $urlKey = 'flashSaleUrlKey:'.$goods->id;
            $stockKey = 'flashSaleStock:'.$goods->id;

            Redis::executeRaw(['set', $urlKey, uuid(), 'ex', strtotime($goods->end_time) - time(), 'nx']);
            Redis::executeRaw(['set', $stockKey, $goods->stock, 'ex', strtotime($goods->end_time) - time(), 'nx']);
        }

        // 记录日志
        echo "生成秒杀商品成功".PHP_EOL;
    }
}
