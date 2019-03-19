<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\FlashSale;
use Storage;
use App\Repository\FlashSaleRepository;
use App\Repository\FlashSaleGoodsRepository;

class GenerateFlashSalePageHtml implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $FlashSale;
    public $tries = 5;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(FlashSale $FlashSale)
    {
        $this->FlashSale = $FlashSale;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 更新列表页
        $new_goods = app()->make(FlashSaleRepository::class)->listFlashSale();

        $FlashSale_index_view = view('shopper.goods_kill_home', ['new_goods'=>$new_goods]);
        Storage::put(get_html_cache_path('kill_home', 'FlashSale'), $FlashSale_index_view);

        // 更新详情页
        $goods_detail = app()->make(FlashSaleGoodsRepository::class)->findById($this->FlashSale->goods_id);
        $detail_view = view('shopper.goods_kill', ['FlashSale'=>$this->FlashSale, 'goods'=>$goods_detail]);
        Storage::put(get_html_cache_path($this->FlashSale->id, 'FlashSale'), $detail_view);
    }
}
