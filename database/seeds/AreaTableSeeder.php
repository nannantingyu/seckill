<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $area = [
            "亚洲" => [
                "东亚" => ['中国', '蒙古', '朝鲜', '韩国', '日本'],
                '东南亚' => ['菲律宾', '越南', '老挝', '柬埔寨', '缅甸', '泰国', '马来西亚', '文莱', '新加坡', '印度尼西亚', '东帝汶'],
                '南亚' => ['尼泊尔', '不丹', '孟加拉国', '印度', '巴基斯坦', '斯里兰卡', '马尔代夫'],
                '中亚' => ['哈萨克斯坦', '吉尔吉斯斯坦', '塔吉克斯坦', '乌兹别克斯坦', '土库曼斯坦'],
                '西亚' => ['阿富汗', '伊拉克', '伊朗', '叙利亚', '约旦', '黎巴嫩', '以色列', '巴勒斯坦', '沙特阿拉伯', '巴林', '卡塔尔', '科威特', '阿拉伯联合酋长国', '阿曼', '也门', '格鲁吉亚', '亚美尼亚', '阿塞拜疆', '土耳其', '塞浦路斯']
            ],
            '非洲' => [
                '北非' => ['埃及', '利比亚', '突尼斯', '阿尔及利亚', '摩洛哥', '亚速尔群岛（葡）', '马德拉群岛（葡）', '加那利群岛'],
                '东非' => ['苏丹', '南苏丹', '埃塞俄比亚', '厄立特里亚', '索马里', '吉布提', '肯尼亚', '坦桑尼亚', '乌干达', '卢旺达', '布隆迪', '塞舌尔'],
                '中非' => ['乍得', '中非', '喀麦隆', '赤道几内亚', '加蓬', '刚果共和国（布）', '刚果民主共和国（金）', '圣多美和普林西比'],
                '西非' => ['毛里塔尼亚', '西撒哈拉', '塞内加尔', '冈比亚', '马里', '布基纳法索', '几内亚', '几内亚比绍', '佛得角', '塞拉利昂', '利比里亚', '科特迪瓦', '加纳', '多哥', '贝宁', '尼日尔', '尼日利亚'],
                '南非' => ['赞比亚', '安哥拉', '津巴布韦', '马拉维', '莫桑比克', '博茨瓦纳', '纳米比亚', '南非', '斯威士兰', '莱索托', '马达加斯加', '科摩罗', '毛里求斯', '留尼汪岛（法）', '圣赫勒拿岛（英）', '马约特（法）'],
            ],
            '欧洲' => [
                '北欧' => ['芬兰', '瑞典', '挪威', '冰岛', '丹麦', '法罗群岛'],
                '东欧' => ['爱沙尼亚', '拉脱维亚', '立陶宛', '白俄罗斯', '俄罗斯', '乌克兰', '摩尔多瓦'],
                '中欧' => ['波兰', '捷克', '斯洛伐克', '匈牙利', '德国', '奥地利', '瑞士', '列支敦士登'],
                '西欧' => ['英国', '爱尔兰', '荷兰', '比利时', '卢森堡', '法国', '摩纳哥'],
                '南欧' => ['罗马尼亚', '保加利亚', '塞尔维亚', '马其顿', '阿尔巴尼亚', '希腊', '斯洛文尼亚', '克罗地亚', '黑山', '马耳他', '波斯尼亚和黑塞哥维那（波黑）', '意大利', '梵蒂冈', '圣马力诺', '西班牙', '葡萄牙', '安道尔', '直布罗陀']
            ],
            '美洲' => [
                '北美' => ['加拿大', '美国', '墨西哥', '格陵兰（丹）', '圣皮埃尔和密克隆（法）', '百慕大（英）'],
                '中美' => ['危地马拉', '伯利兹', '萨尔瓦多', '洪都拉斯', '尼加拉瓜', '哥斯达黎加', '巴拿马'],
                '加勒比海' => ['巴哈马', '古巴', '牙买加', '海地', '多米尼加', '安提瓜和巴布达', '圣基茨和尼维斯', '多米尼克', '圣卢西亚', '圣文森特和格林纳丁斯', '格林纳达', '巴巴多斯', '特立尼达和多巴哥', '波多黎各（美）', '英属维尔京群岛', '美属维尔京群岛', '安圭拉（英）', '蒙特塞拉特（英）', '瓜德罗普（法）', '马提尼克（法）', '阿鲁巴（荷）', '荷属圣马丁', '法属圣马丁', '圣巴泰勒米岛（法）', '特克斯和凯科斯群岛（英）', '开曼群岛（英）', '库拉索（荷）'],
                '南美' => ['哥伦比亚', '委内瑞拉', '圭亚那', '苏里南', '法属圭亚那', '厄瓜多尔', '秘鲁', '玻利维亚', '巴西', '智利', '阿根廷', '乌拉圭', '巴拉圭', '马尔维纳斯群岛']
            ],
        ];

        $area_in_english = [
            "Asia" => [
                "East Asia" => ['China', 'Mongolia', 'Korea', 'Korea', 'Japan'],
                'Southeast Asia' => ['Philippines', 'Vietnam', 'Laos', 'Cambodia', 'Myanmar', 'Thailand', 'Malaysia', 'Brunei', 'Singapore', 'Indonesia', 'East Timor'],
                'South Asia' => ['Nepal', 'Bhutan', 'Bangladesh', 'India', 'Pakistan', 'Sri Lanka', 'Maldives'],
                'Central Asia' => ['Kazakhstan', 'Kyrgyzstan', 'Tajikistan', 'Uzbekistan', 'Turkmenistan'],
                'West Asia' => ['Afghanistan', 'Iraq', 'Iran', 'Syria', 'Jordan', 'Lebanon', 'Israel', 'Palestine', 'Saudi Arabia', 'Bahrain', 'Qatar', 'Kuwait', 'United Arab Emirates', 'Oman', 'Yemen', 'Georgia', 'Armenia', 'Azerbaijani', 'Turkey', 'Cyprus']
            ],
            'Africa' => [
                'North Africa' => ['Egypt', 'Libya', 'Tunisia', 'Algeria', 'Morocco', 'Azores (Portugal)', 'Madeira Islands (Portugal)', 'Canary Islands'],
                'East Africa' => ['Sultan', 'South Sultan', 'Ethiopia', 'Eritrea', 'Somalia', 'Djibouti', 'Kenya', 'Tanzania', 'Uganda', 'Rwanda', 'Burundi', 'Seychelles'],
                'Central Africa' => ['Chad ', ' Central Africa ', ' Cameroon ', ' Equatorial Guinea ', ' Gabon ', ' Congo Republic (cloth)', 'Congo Democratic Republic (gold)', 'Sao Tome and Principe'],
                'West Africa' => ['Mauritania', 'Western Sahara', 'Senegal', 'Gambia', 'Mali', 'Burkina Faso', 'Guinea', 'Guinea-Bissau', 'Cape Verde', 'Sierra Leone', 'Liberia', 'Ivory Coast', 'Ghana', 'Togo', 'Benin', 'Niger', 'Nigeria'],
                'South Africa' => ['Zambia', 'Angola', 'Zimbabwe', 'Malawi', 'Mozambique', 'Botswana', 'Namibia', 'South Africa', 'Swaziland', 'Lesotho', 'Madagascar', 'Comoros', ' Mauritius', 'Reunion Island (Law)', 'St. Helena Island(English)', 'ma(Law)'],
            ],
            'Europe' => [
                'Nordic' => ['Finland', 'Sweden', 'Norway', 'Iceland', 'Denmark', 'Faroe Islands'],
                'Eastern Europe' => ['Estonia', 'Latvia', 'Lithuania', 'Belarus', 'Russia', 'Ukraine', 'Moldova'],
                'Central Europe' => ['Poland', 'Czech', 'Slovakia', 'Hungary', 'Germany', 'Austria', 'Switzerland', 'Liechtenstein'],
                'Western Europe' => ['Britain', 'Ireland', 'Netherlands', 'Belgium', 'Luxembourg', 'France', 'Monaco'],
                'Southern Europe' => ['Romania', 'Bulgaria', 'Serbia', 'Macedonia', 'Albania', 'Greece', 'Slovenia', 'Croatia', 'Montenegro', 'Malta', 'Bosnia and Herzegovina', 'Italy', 'Vatican', 'San Marino', 'Spain', 'Portugal', 'Andorra', 'Gibraltar']
            ],
            'Americas' => [
                'North America' => ['Canada', 'America', 'Mexico', 'Greenland (Dan)', 'St. Pierre and Miquelon (France)', 'Bermuda (UK)'],
                'Central America' => ['Guatemala', 'Belize', 'El Salvador', 'Honduras', 'Nicaragua', 'Costa Rica', 'Panama'],
                'Caribbean Sea' => ['Bahamas', 'Cuba', 'Jamaica', 'Haiti', 'Dominica', 'Antigua and Barbuda', 'Saint Kitts and Nevis', 'Dominica', 'Saint Lucia', 'Saint Vincent and the Grenadines', 'Grenada', 'Barbados', 'Trinidad and Tobago', 'Puerto Rico (United States)', 'British Virgin Islands', 'American Virgin Islands', 'Anguilla', ' )', 'Montserrat (UK)', 'Guadeloupe (France)', 'Martinique (France)', 'Aruba (Holland)', 'Dutch St Martin', 'French St Martin', 'St. Batteremi (France)', 'Turks and Caicos Islands (UK)', 'Cayman Islands (UK)', 'Curaso (Holland)'],
                'South America' => ['Colombia', 'Venezuela', 'Guyana', 'Suriname', 'French Guyana', 'Ecuador', 'Peru', 'Bolivia', 'Brazil', 'Chile', 'Argentina', 'Uruguay', 'Paraguay', 'Malvinas Islands']
            ]
        ];
        
        foreach ($area as $country_name=>$children) {
            $id = DB::table("area")->insertGetId([
                "area_name" => $country_name,
                "parent_id" => 0
            ]);

            $this->insert_area_into_db($children, $id);
        }
    }

    public function insert_area_into_db($children, $parent_id) {
        foreach ($children as $country_name=>$sub_country) {
            if (!is_array($sub_country)) {
                $country_name = $sub_country;
            }

            $id = DB::table("area")->insertGetId([
                "area_name" => $country_name,
                "parent_id" => $parent_id
            ]);

            if (is_array($sub_country)) {
                $this->insert_area_into_db($sub_country, $id);
            }
        }
    }
}
