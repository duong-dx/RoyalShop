<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTabel extends Migration
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
            $table->string('name')->nullable();
            $table->string('slug')->unique();
            $table->integer('code')->unsigned()->unique();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('category_id')->unsigned()->nullable();
            $table->integer('brand_id')->unsigned()->nullable();
            $table->string('warranty_time')->nullable(); //thời gian bảo hành
            $table->string('ram')->nullable(); //ram
            $table->string('weight')->nullable(); //cân nặng
            $table->string('screen_size')->nullable(); //kích thước màn hình
            $table->string('pin')->nullable(); // dung lượng pin
            $table->string('front_camera')->nullable(); //camera trước
            $table->string('rear_camera')->nullable(); //camera sau
            $table->string('operating_system')->nullable(); //hệ điều hành
            $table->integer('view_count')->default(0);
            $table->timestamps();
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
