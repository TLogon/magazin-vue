<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_product', function (Blueprint $table) {
            $table->id();
            $table->Integer('status_id');
            $table->timestamps();
            $table->string('name', 50);
            $table->Integer('countrycode_id');
            $table->string('phone', 10);
            $table->string('address', 300)->nullable();;
            $table->bigInteger('product_id');
            $table->Integer('quantity');
            $table->Integer('size_id')->nullable();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buy_product');
    }
};
