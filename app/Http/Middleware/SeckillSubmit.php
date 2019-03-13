<?php

namespace App\Http\Middleware;
use App\Repository\SeckillRepository;
use Closure;

class SeckillSubmit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $url = $request->url();
        preg_match('/seckill_buy\/(\d+)\/(.*)\/?/', $url, $matches);
        if (count($matches) < 3 or ! app()->make(SeckillRepository::class)->checkSeckillUrl($matches[1], $matches[2])) {
            return response()->json(['error'=>'submit is forbidden'], 403);
        }

        return $next($request);
    }
}
