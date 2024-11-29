<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['city', 'comment', 'gender']);
            $table->unique('phone'); // Make phone number unique
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('city')->nullable();
            $table->text('comment')->nullable();
            $table->string('gender')->nullable();
            $table->dropUnique('customers_phone_unique');
        });
    }
}
