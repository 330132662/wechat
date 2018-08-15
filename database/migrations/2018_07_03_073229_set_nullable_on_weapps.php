<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetNullableOnWeapps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('weapps', function (Blueprint $table) {
            $table->string('nav')->default('["首页","产品","服务","关于我们"]')->change();
            $table->string('apptitle')->default('未命名')->change();
            $table->string('company')->default('未设置')->change();
            $table->string('tel')->default('0')->change();
            $table->string('addr')->default('未设置')->change();
            $table->longText('homevideo')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
