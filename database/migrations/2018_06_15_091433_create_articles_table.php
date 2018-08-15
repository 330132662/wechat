<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->comment = '发布到小程序的文章';
            $table->string("name")->default("未命名")->comment();
            $table->longText("content")->comment("内容");
            $table->integer("author")->dafault("佚名")->comment("作者");
            $table->integer("weid")->dafault(0)->comment("小程序ID");
            $table->longText("thumb")->comment("封面图URL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
