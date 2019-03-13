<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            "娱乐" => [
                "明星" => [
                    "歌星",
                    "电影明星",
                    "主持人",
                    "笑星",
                    "相声",
                    "小品",
                    "综艺",
                    "戏曲" => ["京剧", "豫剧", "黄梅戏", "评剧", "越剧"],
                    "话剧",
                    "演奏家" => ["钢琴", "小提琴", "萨克斯", "古筝", "二胡", "马头琴", "大提琴", "电音", "唢呐"]
                ],
                "电影" => [
                    "喜剧", "警匪", "烧脑", "科幻", "动画", "剧情", "家庭", "悲剧"
                ],
                "电视剧",
                "微博",
                "音乐",
                "综艺",
                "戏曲",
                "红人",
                "短视频",
            ],
            "体育" => [
                "篮球" => [
                    "NBA", "CBA", "WNBA", "业余"
                ],
                "足球" => [
                    "中超", "西甲", "意甲", "英超", "德甲", "世界杯", "欧冠", "亚冠"
                ],
                "乒乓球",
                "羽毛球",
                "网球",
                "高尔夫",
                "斯诺克",
                "田径" => [
                    "赛跑", "投掷", "跳高", "跳远"
                ],
                "举重",
                "游泳",
                "冰球",
                "滑雪"
            ],
            "财经" => [
                "股票" => [
                    "A股", "港股", "美股"
                ],
                "投资" => [
                    "基金", "债券", "P2P", "银行"
                ],
                "数字货币",
                "期货",
                "外汇",
                "保险"
            ],
            "科技" => [
                "互联网",
                "电子",
                "电信",
                "创业",
                "大厂" => [
                    "阿里巴巴", "腾讯", "华为", "京东", "小米", "今日头条", "Facebook", "Google", "Apple", "Twitter", "联想", "中兴", "惠普", "三星"
                ]
            ],
            "数码" => [
                "手机",
                "电脑" => ["笔记本", "台式机", "一体机"],
                "家电" => ["电视机", "电冰箱", "空调", "智能家居" => ["扫地机器人"]],
                "相机"
            ],
            "游戏" => [
                "网游" => ["Dota2", "英雄联盟", "梦幻西游", "魔兽世界", "QQ飞车", "穿越火线", "诛仙"],
                "手游" => ["王者荣耀", "梦幻西游", "阴阳师", "炉石传说", "荒野行动", "斗地主"],
                "页游" => ["大天使之剑", "传奇OL"],
                "单机游戏" => ["植物大战僵尸", "使命召唤", "生化危机", "大富翁"]
            ],
            "旅游" => [
                "攻略",
                "摄影",
                "美食"
            ],
            "民生" => [
                "彩票",
                "文化",
                "交通" => ["公路", "铁路", "航空"],
                "就业",
                "社保",
                "养老",
                "利民政策"
            ],
            "法治" => [],
            "军事" => [
                "国内",
                "国际",
            ],
            "历史" => [
                "中国" => [
                    "三皇五帝",
                    "夏商西周",
                    "春秋战国",
                    "汉朝",
                    "三国",
                    "两晋南北朝",
                    "隋唐",
                    "五代十国",
                    "宋朝",
                    "元朝",
                    "明朝",
                    "清朝",
                    "民国",
                    "抗日战争及国共内战",
                    "建国初期",
                ],
                "国际",
                "史前文明",
                "考古"
            ],
            "汽车" => [],
            "读书" => [
                "散文",
                "小说" => [
                    "长篇",
                    "中篇",
                    "短篇"
                ],
                "青春",
                "科幻",
                "诗歌",
                "童话",
                "漫画",
                "哲学",
                "励志",
                "教育",
                "健康",
                "职场",
            ],
            "房产" => [
                "新房", "二手房", "租房"
            ],
            "教育" => [
                "学前教育",
                "幼儿园",
                "培训班",
                "小升初",
                "中考",
                "高考",
                "大学",
                "考研",
                "博士",
                "留学"
            ],
            "时尚" => [
                "服装",
                "美容",
                "珠宝",
                "收藏",
            ]
        ];

        $sequence = 0;
        foreach ($categories as $category_name=>$children) {
            $id = DB::table("category")->insertGetId([
                "category_name" => $category_name,
                "parent_id" => 0,
                "sequence" => $sequence
            ]);

            $sequence += 1000;
            $this->insert_category_into_db($children, $id);
        }
    }

    public function insert_category_into_db($children, $parent_id) {
        $sequence = 1000;
        foreach ($children as $category_name=>$sub_category) {
            if (!is_array($sub_category)) {
                $category_name = $sub_category;
            }

            $id = DB::table("category")->insertGetId([
                "category_name" => $category_name,
                "parent_id" => $parent_id,
                "sequence" => $sequence
            ]);

            $sequence += 1000;
            if (is_array($sub_category)) {
                $this->insert_category_into_db($sub_category, $id);
            }
        }
    }
}
