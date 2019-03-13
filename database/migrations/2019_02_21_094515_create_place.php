<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlace extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('place', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 64)->nullable(false);
            $table->integer('parent_id')->nullable(false)->default(0);
            $table->string('code', 16)->nullable(false);
            $table->tinyInteger('type')->default(0);
            $table->timestamp('created_at')->nullable(false)->default(\Illuminate\Support\Facades\DB::raw("CURRENT_TIMESTAMP"));
            $table->timestamp('updated_at')->nullable(false)->default(\Illuminate\Support\Facades\DB::raw("CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('place_migration');
    }
}
