<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Seckill;
use Storage;
use App\Repository\SeckillRepository;
use App\Repository\SeckillGoodsRepository;

class GenerateSeckillPageHtml implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $seckill;
    public $tries = 5;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Seckill $seckill)
    {
        $this->seckill = $seckill;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 更新列表页
        $new_goods = app()->make(SeckillRepository::class)->listSeckill();

        $seckill_index_view = view('shopper.goods_kill_home', ['new_goods'=>$new_goods]);
        Storage::put(get_html_cache_path('kill_home', 'seckill'), $seckill_index_view);

        // 更新详情页
        $goods_detail = app()->make(SeckillGoodsRepository::class)->findById($this->seckill->goods_id);
        $detail_view = view('shopper.goods_kill', ['seckill'=>$this->seckill, 'goods'=>$goods_detail]);
        Storage::put(get_html_cache_path($this->seckill->id, 'seckill'), $detail_view);
    }
}
