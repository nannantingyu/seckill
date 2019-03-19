<?php

namespace App\Http\Middleware;
use App\Repository\Dao\FlashSaleRepository;
use Closure;

class FlashSaleSubmit
{
    /**
     * 处理非法提交的秒杀表单
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $url = $request->url();
        preg_match('/flashSaleBuy\/(\d+)\/(.*)\/?/', $url, $matches);
        if (count($matches) < 3 or ! app()->make(FlashSaleRepository::class)->checkFlashSaleUrl($matches[1], $matches[2])) {
            return response()->json(['error'=>'submit is forbidden'], 403);
        }

        return $next($request);
    }
}
