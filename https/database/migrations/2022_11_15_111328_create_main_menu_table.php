<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_menus', function (Blueprint $table) {
            $table->increments('main_menu_id');
            $table->integer('exhibition_id');
            $table->string('name', 255);
            $table->string('color', 20);
            $table->boolean('language_ja_flag');
            $table->smallInteger('orderBy');
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->integer('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('main_menus');
    }
}
