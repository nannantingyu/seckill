<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repository\SeckillRepository;
use App\Repository\SeckillGoodsRepository;
use Illuminate\Support\Facades\Storage;

class RegenerateSeckill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seckill:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'regenerate seckill html cache';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 更新列表页
        $new_goods = app()->make(SeckillRepository::class)->listSeckill();

        $seckill_index_view = view('shopper.goods_kill_home', ['new_goods'=>$new_goods]);
        Storage::put(get_html_cache_path('kill_home', 'seckill'), $seckill_index_view);

        // 更新详情页
        foreach ($new_goods as $goods) {
            dump($new_goods);
            $goods_detail = app()->make(SeckillGoodsRepository::class)->findById($goods->goods_id);
            $detail_view = view('shopper.goods_kill', ['seckill'=>$goods, 'goods'=>$goods_detail]);
            Storage::put(get_html_cache_path($goods->id, 'seckill'), $detail_view);
        }

        echo "Clear successfully".PHP_EOL;
    }
}
