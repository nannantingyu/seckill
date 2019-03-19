<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeckillGoods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seckill_goods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('goods_id', 36)->nullable(false);
            $table->integer('merchant_id')->nullable(false);
            $table->string('goods_name', 256)->nullable(false);
            $table->string('place', 256);
            $table->string('brand', 128);
            $table->float('weight')->default(0);
            $table->string('packing', 256);
            $table->text('detail_pictures');
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
        Schema::dropIfExists('seckill_goods');
    }
}
