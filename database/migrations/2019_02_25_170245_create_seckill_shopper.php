<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeckillShopper extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seckill_shopper', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_name', 32)->nullable(false);
            $table->string('password', 64)->nullable(false);
            $table->string('nick_name')->nullable(false);
            $table->text('scope');
            $table->string('email', 128)->nullable(false)->unique();
            $table->string('avatar', 255)->nullable(false);
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
        Schema::dropIfExists('seckill_shopper');
    }
}
