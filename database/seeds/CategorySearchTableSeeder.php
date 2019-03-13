<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySearchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = DB::table('category')
            ->select('id', 'category_name')
            ->get();

        $forms = [];
        foreach ($categories as $category) {
            array_push($forms, [
                "key_name" => $category->category_name,
                "category_id" => $category->id
            ]);
        }

        DB::table("category_search_key")->insert($forms);
    }
}
