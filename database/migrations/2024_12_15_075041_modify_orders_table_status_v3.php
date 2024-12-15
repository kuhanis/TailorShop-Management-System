<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyOrdersTableStatusV3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->nullable()->change();
            DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_status_check_v3 CHECK (status IN ('paid', 'to_collect'))");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            DB::statement("ALTER TABLE orders DROP CONSTRAINT orders_status_check_v3");
            $table->string('status')->default('to_collect')->change();
        });
    }
}
