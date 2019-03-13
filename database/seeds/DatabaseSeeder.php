<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AreaTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(CategorySearchTableSeeder::class);
        $this->call(ArticleTableSeeder::class);
    }
}
