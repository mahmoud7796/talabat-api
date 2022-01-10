<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->float('price');
            $table->string('fill_attribute_en');
            $table->string('fill_attribute_ar');
            $table->integer('category_id');
            $table->integer('vendor_id');
            $table->integer('section_id');
            $table->integer('user_id');
            $table->integer('stock_quantity');
            $table->float('discount');
            $table->float('price_after_discount');
            $table->boolean('is_available');
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
