<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/15
 * Time: 10:59 AM
 */

namespace App\Http\Controllers;
use Illuminate\Cache\Repository;
use Illuminate\Http\Request;
use App\Repository\ArticleRepository;

class ArticleController extends Controller
{
    private $articleRepository;
    public function __construct(ArticleRepository $articleRepository) {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @param Request $request
     * @return array article lists with pagination
     */
    public function getPageList(Request $request) {
        $page = (int)$request->get('page', 1);
        $perpage = (int)$request->get('per', 10);
        if ($perpage > 30) {
            return response("Per page should not bigger than 30", 400);
        }

        $category_id = (int)$request->get('cid');
        $with_body = (boolean)$request->get('detail', false);
        $order = $request->get('order', 'publish_time');

        return $this->articleRepository->getPageList($category_id, $page, $perpage, $order, $with_body);
    }
}