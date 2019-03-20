<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repository\Dao\FlashSaleRepository;
use App\Repository\Dao\FlashSaleGoodsRepository;
use Illuminate\Support\Facades\Storage;

class RegenerateFlashSale extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashSale:make';

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
        $new_goods = app()->make(FlashSaleRepository::class)->getFlashSale();

        // 更新详情页
        foreach ($new_goods as $goods) {
            $goods_detail = app()->make(FlashSaleGoodsRepository::class)->getById($goods->goods_id);
            $detail_view = view('mall.goods_kill', ['flashSale'=>$goods, 'goods'=>$goods_detail]);
            Storage::put(get_html_cache_path($goods->id, 'FlashSale'), $detail_view);
        }

        echo "Clear successfully".PHP_EOL;
    }
}
