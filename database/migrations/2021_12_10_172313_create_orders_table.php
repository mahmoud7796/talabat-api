<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Type\Integer;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->text('product_attributes');
            $table->integer('user_id');
            $table->string('shipping_address_ar');
            $table->string('shipping_address_en');
            $table->string('shipping_google_address');
            $table->enum('shipping_status',['pending','wait','active']);
            $table->date('shipping_date');
            $table->enum('payment_method',['credit','debit','paypal']);
            $table->enum('order_status',['pending','wait','active']);
            $table->float('subtotal');
            $table->float('shipping_cost');
            $table->float('taxes');
            $table->float('final_total');
            $table->boolean('is_notified');
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
        Schema::dropIfExists('orders');
    }
}
