<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repository\SeckillRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class GenerateSeckillUrl implements ShouldQueue
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
        $seckill_goods = app()->make(SeckillRepository::class)->getAlmostBeginSeckills();
        foreach ($seckill_goods as $goods) {
            Redis::executeRaw(['set', 'seckill_url:'.$goods->id, uuid(), 'ex', strtotime($goods->end_time) - time(), 'nx']);
        }

        echo "Success to generate seckill url".PHP_EOL;
    }
}
