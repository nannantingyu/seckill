<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repository\FlashSaleRepository;
use App\Repository\FlashSaleGoodsRepository;
use Illuminate\Support\Facades\Storage;

class RegenerateFlashSale extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'FlashSale:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'regenerate FlashSale html cache';

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
        $new_goods = app()->make(FlashSaleRepository::class)->listFlashSale();

        $FlashSale_index_view = view('shopper.goods_kill_home', ['new_goods'=>$new_goods]);
        Storage::put(get_html_cache_path('kill_home', 'FlashSale'), $FlashSale_index_view);

        // 更新详情页
        foreach ($new_goods as $goods) {
            dump($new_goods);
            $goods_detail = app()->make(FlashSaleGoodsRepository::class)->findById($goods->goods_id);
            $detail_view = view('shopper.goods_kill', ['FlashSale'=>$goods, 'goods'=>$goods_detail]);
            Storage::put(get_html_cache_path($goods->id, 'FlashSale'), $detail_view);
        }

        echo "Clear successfully".PHP_EOL;
    }
}
