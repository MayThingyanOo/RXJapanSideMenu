<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_menus', function (Blueprint $table) {
            $table->increments('sub_menu_id');
            $table->integer('main_menu_id');
            $table->string('label', 255)->nullable();
            $table->string('link', 255)->nullable();
            $table->string('image_name', 255)->nullable();
            $table->boolean('language_ja_flag');
            $table->smallInteger('orderBy');
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->integer('deleted_by')->nullable();

            $table->foreign('main_menu_id')
                ->references('main_menu_id')
                ->on('main_menus')
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_menus');
    }
}
