<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\FlashSale;
use Illuminate\Support\Facades\Storage;
use App\Repository\Dao\FlashSaleGoodsRepository;

class GenerateFlashSalePageHtml implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $flashSale;
    public $tries = 5;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(FlashSale $flashSale)
    {
        $this->flashSale = $flashSale;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 更新详情页
        $goods_detail = app()->make(FlashSaleGoodsRepository::class)->getById($this->flashSale->goods_id);
        $detail_view = view('mall.goods_kill', ['flashSale'=>$this->flashSale, 'goods'=>$goods_detail]);
        Storage::put(get_html_cache_path($this->flashSale->id, 'FlashSale'), $detail_view);
    }
}
