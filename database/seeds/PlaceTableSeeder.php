<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Redis;
use App\Models\Place;

class PlaceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $area = json_decode(Storage::get("place.json"), true);
        foreach ($area as $code=>$info) {
            $id = DB::table("place")->insertGetId([
                "name"      => $info['name'],
                "parent_id" => 0,
                "type"      => 1,
                "code"      => $code
            ]);

            print "Insert province - ". $info['name']. PHP_EOL;
            $this->insert_place_into_db($info['_children'], $id, 2);
        }
    }

    public function insert_place_into_db($children, $parent_id, $type) {
        $types = [2=>"市", 3=>"县", 4=>"镇", 5=>"村"];
        if ($type < 5) {
            foreach ($children as $code=>$info) {
                $id = DB::table("place")->insertGetId([
                    "name"      => $info['name'],
                    "parent_id" => $parent_id,
                    "type"      => $type,
                    "code"      => $code
                ]);

                print "Insert $types[$type] - ". $info['name']. PHP_EOL;
                if (!empty($info['_children'])) {
                    $this->insert_place_into_db($info['_children'], $id, $type+1);
                }
            }
        }
        else {
            $places = [];
            foreach ($children as $code=>$info) {
                $places[] = [
                    "name"      => $info['name'],
                    "parent_id" => $parent_id,
                    "type"      => $type,
                    "code"      => $code
                ];
            }

            DB::table("place")->insert($places);
        }
    }
}
