<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 64)->nullable(false);
            $table->string('description', 200);
            $table->string('keywords', 64);
            $table->string('author', 32);
            $table->string('image', 200)->comment('封面图')->nullable(false)->default('[]');
            $table->integer('hits')->comment('点击量');
            $table->timestamp('publish_time')->comment('发布时间');
            $table->tinyInteger('recommend')->default(0)->comment('是否置顶');
            $table->string('source_site', 32)->comment('来源站点');
            $table->string('source_url', 256)->comment('源地址');
            $table->integer('favor')->default(0);
            $table->integer('disfavor')->default(0);
            $table->tinyInteger('state')->nullable(false)->default(1)->after('recommend');
            $table->tinyInteger('source_type')->default(0)->comment('是否原创');
            $table->timestamp('created_at')->nullable(false)->default(\Illuminate\Support\Facades\DB::raw("CURRENT_TIMESTAMP"));
            $table->timestamp('updated_at')->nullable(false)->default(\Illuminate\Support\Facades\DB::raw("CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"));
            $table->index('publish_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article');
    }
}
