<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeckill extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seckill', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('goods_id')->nullable(false);
            $table->string('title', 128)->nullable(false);
            $table->text('pictures')->nullable(false);
            $table->string('description', 256);
            $table->decimal('ori_price', 10, 2)->nullable(false);
            $table->decimal('kill_price', 10, 2)->nullable(false);
            $table->integer('merchant_id')->nullable(false);
            $table->dateTime('check_at')->nullable(true);
            $table->dateTime('begin_time')->nullable(false);
            $table->dateTime('end_time')->nullable(false);
            $table->tinyInteger('state')->nullable(false)->default(0);
            $table->smallInteger('quantity')->nullable(false)->default(0);
            $table->smallInteger('stock')->nullable(false)->default(0);
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
        Schema::dropIfExists('seckill');
    }
}
