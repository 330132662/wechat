<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->comment = '商品表';
            $table->integer('weid')->comment('所属小程序');
            $table->string('name')->comment("商品名");
            $table->float('price')->comment("现价");
            $table->float('ori_price')->default(0)->comment("原价");
            $table->longText("desc")->comment('商品描述');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
