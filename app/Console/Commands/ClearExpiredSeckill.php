<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repository\SeckillRepository;
use Illuminate\Support\Facades\Storage;

class ClearExpiredSeckill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seckill:clear';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // update detail page
        $new_goods = app()->make(SeckillRepository::class)->listSeckill();

        $all_seckill_files = Storage::files('html/seckill');
        $new_files = [];

        array_map(function($goods) use($new_files) {
            array_push($new_files, get_html_cache_path($goods->id));
        }, $new_goods->toArray());

        $expired_caches = array_diff($all_seckill_files, $new_files);
        foreach ($expired_caches as $file) {
            Storage::delete($file);
        }

        // update list page
        $seckill_index_view = view('shopper.goods_kill_home', ['new_goods'=>$new_goods]);
        Storage::put(get_html_cache_path('kill_home', 'seckill'), $seckill_index_view);

        echo "Clear successfully".PHP_EOL;
    }
}
