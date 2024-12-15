<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetentionsTableV2 extends Migration
{
    public function up()
    {
        Schema::create('retentions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->timestamp('link_expire')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('retentions');
    }
} 