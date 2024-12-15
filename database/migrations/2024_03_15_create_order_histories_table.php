<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->text('description')->nullable();
            $table->date('received_on');
            $table->string('amount_charged');
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_histories');
    }
}; 