<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/28
 * Time: 3:51 PM
 */

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\SeckillRepository;
use App\Jobs\GenerateSeckillPageHtml;

class SeckillController extends Controller
{
    private $seckillRepository;
    public function __construct(SeckillRepository $seckillRepository) {
        $this->seckillRepository = $seckillRepository;
    }

    /**
     * Get all shoppers
     * @return \Illuminate\Http\JsonResponse
     */
    public function list() {
        return response()->json(["data"=>$this->seckillRepository->listAllSeckill()]);
    }

    /**
     * check seckill request
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function checkSeckill(Request $request) {
        $request->validate([
            "id"        => 'required|integer',
            "state"     => 'required|integer|max:1|min:-1'
        ]);

        $seckill = $this->seckillRepository->check($request->input('id'), $request->input('state'));
        if (empty($seckill)) {
            return internal_error_json_response();
        }

        GenerateSeckillPageHtml::dispatch($seckill)->delay(now()->addMinutes(2));
        return response([], 200);
    }

    /**
     * Delete shopper
     * @param Request $request
     * @return int
     */
    public function delete_seckill(Request $request) {
        $request->validate([
            "id"    =>  "required|integer"
        ]);

        if ($this->seckillRepository->deleteSeckill($request->input('id'))) {
            return response()->json("");
        }

        return internal_error_json_response();
    }
}