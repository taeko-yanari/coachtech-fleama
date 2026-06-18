<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')
            ->constrained()
            ->onDelete('cascade');
            $table->foreignId('item_id')
            ->constrained()
            ->onDelete('cascade');

            $table->unique('item_id');

            $table->string('payment_method', 50);
            $table->string('stripe_payment_intent_id', 255)->nullable()->unique();
            $table->string('shipping_postal_code', 10);
            $table->text('shipping_address');
            $table->string('shipping_building', 255)->nullable();
            $table->unsignedInteger('price');
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
        Schema::dropIfExists('purchases');
    }
}
