<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/2/15
 * Time: 2:40 PM
 */

namespace App\Repository\Dao;
use App\Models\Area;
use App\Repository\BaseRepository;

class AreaRepository extends BaseRepository
{
    public function model()
    {
        return Area::class;
    }

    /**
     * Get all areas with area id as key
     * @return array categoriy map
     */
    public function getAreaMap() {
        $areas = $this->all();
        $area_map = [];
        foreach ($areas as $area) {
            $area_map[$area->id] = $area;
        }

        return $area_map;
    }

    /**
     * 获取地区树
     * @return array 地区的树形结构
     */
    public function getAreaTree() {
        $areas = $this->all();
        $tree = $this->buildAreaTree($areas, 0);

        return $tree;
    }

    /**
     * 获取地区的所有父地区
     * @param $area_id int 地区id
     * @return array all parents of area
     */
    public function getAllParentAreas($area_id) {
        $area_map = $this->getAreaMap();
        if(!in_array($area_id, array_keys($area_map))) {
            return [];
        }

        $parents = [];
        while($area_id != 0) {
            array_unshift($parents, $area_map[$area_id]);
            $area_id = $area_map[$area_id]->parent_id;
        }

        return $parents;
    }

    /**
     * 递归创建无限地区树
     * @param $areas array 全部地区
     * @param $parent_id int 父地区
     * @return array 某个地区的子树
     */
    private function buildAreaTree($areas, $parent_id) {
        $children = [];
        foreach ($areas as $area) {
            if ($area->parent_id == $parent_id) {
                array_push($children, $area);
                $area->children = $this->buildAreaTree($areas, $area->id);
            }
        }

        return $children;
    }

    /**
     * 获取某个地区的全部子地区
     * @param $area_id
     * @return array
     */
    public function getChildAreas($area_id) {
        $allAreas = $this->getAreaMap();
        if (!isset($allareas[$area_id])) {
            return [];
        }

        $root = $allAreas[$area_id];
        $root->children = $this->buildAreaTree($allAreas, $area_id);
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
}