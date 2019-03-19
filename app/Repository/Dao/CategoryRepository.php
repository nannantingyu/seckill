<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/15
 * Time: 9:55 AM
 */

namespace App\Repository;
use App\Models\Category;

class CategoryRepository
{
    public $age = 10;
    /**
     * 获取全部的分类
     * @return mixed
     */
    public function getAllCategory($state=true) {
        $categories = Category::select("id", "category_name", "parent_id", "sequence");
        if($state) {
            $categories = $categories->where("state", 1);
        }

        return $categories->orderBy("sequence")->get();
    }

    /**
     * Get all categories with category id as key
     * @return array categoriy map
     */
    public function getCategoryMap() {
        $categories = $this->getAllCategory();
        $category_map = [];
        foreach ($categories as $category) {
            $category_map[$category->id] = $category;
        }

        return $category_map;
    }

    /**
     * 获取分类树
     * @param $cid int category id
     * @return array 分类的树形结构
     */
    public function getCategoryTree($cid=0) {
        $categories = $this->getCategoryMap();
        if ($cid != 0 and !isset($categories[$cid])) {
            return null;
        }

        $children = $this->buildCategoryTree($categories, $cid);

        if($cid == 0) {
            return $children;
        }

        $category = $categories[$cid];
        $category->children = $children;
        return $category;
    }

    /**
     * 获取分类的所有父分类
     * @param $category_id int 分类id
     * @return array all parents of category
     */
    public function getAllParentCategories($category_id) {
        $category_map = $this->getCategoryMap();
        if(!in_array($category_id, array_keys($category_map))) {
            return [];
        }

        $parents = [];
        while($category_id != 0) {
            array_unshift($parents, $category_map[$category_id]);
            $category_id = $category_map[$category_id]->parent_id;
        }

        return $parents;
    }

    /**
     * 递归创建无限分类树
     * @param $categories array 全部分类
     * @param $parent_id int 父分类
     * @return array 某个分类的子树
     */
    private function buildCategoryTree($categories, $parent_id) {
        $children = [];
        foreach ($categories as $category) {
            if ($category->parent_id == $parent_id) {
                array_push($children, $category);
                $category->children = $this->buildCategoryTree($categories, $category->id);
            }
        }

        return $children;
    }

    /**
     * 获取某个分类的全部子分类
     * @param $category_id
     * @return array
     */
    public function getChildCategories($category_id) {
        $allCategories = $this->getCategoryMap();
        if (!isset($allCategories[$category_id])) {
            return [];
        }

        $root = $this->getCategoryTree($category_id);
        $all_children_ids = [];
        $queue = [$root];
        while($queue) {
            $cate = array_pop($queue);
            array_push($all_children_ids, $cate->id);
            if (isset($cate['children'])) {
                $queue = array_merge($queue, $cate['children']);
            }
        }

        return $all_children_ids;
    }

    /**
     * Get all search key for category
     * @param $cid
     * @return mixed
     */
    public function getSearchKey($cid) {
        return Category::where('id', $cid)->select("id", "category_name")->with('search_key')->get();
    }
}