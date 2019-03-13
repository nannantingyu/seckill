<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/15
 * Time: 11:16 AM
 */

namespace App\Http\Controllers;
use App\Repository\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $categoryRepository;
    public function __construct(CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get all categories
     * @param Request $request
     * @return mixed
     */
    public function getCategories(Request $request) {
        $with_disable = (boolean)$request->get('all', false);
        return $this->categoryRepository->getAllCategory($with_disable);
    }

    /**
     * Get category crumbs
     * @param Request $request
     * @return array category crumbs
     */
    public function getCategoryCrumbs(Request $request) {
        $category_id = $request->get('cid');
        return $this->categoryRepository->getAllParentCategories($category_id);
    }

    /**
     * Get category search key for crawl
     * @param Request $request
     * @return mixed
     */
    public function getSearchKey(Request $request) {
        $category_id = $request->get('cid');
        return $this->categoryRepository->getSearchKey($category_id);
    }

    /**
     * Get category tree
     * @param Request $request
     * @return mixed
     */
    public function getCategoryTree(Request $request) {
        $category_id = (int)$request->get("cid", 0);
        return $this->categoryRepository->getCategoryTree($category_id);
    }
}