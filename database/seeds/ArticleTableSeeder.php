<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category_ids = DB::table("category")->pluck("id")->toArray();

        for($index = 0; $index < 500; $index ++) {
            $id = DB::table('article')->insertGetId([
                'title'         => random_chinese(mt_rand(10, 32)),
                'description'   => random_chinese(mt_rand(20, 64)),
                'author'        => random_chinese(mt_rand(2, 10)),
                'keywords'      => random_chinese(mt_rand(2, 12)),
                'hits'          => mt_rand(0, 100),
                'publish_time'  => date('Y-m-d H:i:s', time() - mt_rand(0, 24*30) * 3600),
                'recommend'     => mt_rand(0, 1),
                'source_site'   => '微信',
                'source_url'    => 'https://www.yjshare.cn/blog_'.mt_rand(1, 100000),
                'source_type'   => 0
            ]);

            DB::table('article_body')->insert([
                'id'    => $id,
                'body'  => random_body()
            ]);

            $category_nums = mt_rand(1, 4);
            $category_id = [];
            $category_index = 0;
            while($category_index < $category_nums) {
                $cid = array_rand($category_ids);
                while (!in_array($cid, $category_id)) {
                    array_push($category_id, $cid);
                    $category_index ++;
                }
            }

            $article_category_maps = [];
            foreach($category_id as $cid) {
                array_push($article_category_maps, [
                    'article_id' => $id,
                    'category_id' => $cid
                ]);
            }

            DB::table('article_category_map')->insert($article_category_maps);
        }
    }
}
