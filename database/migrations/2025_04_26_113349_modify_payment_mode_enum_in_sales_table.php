<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('sales', function (Blueprint $table) {
        DB::statement("ALTER TABLE sales MODIFY payment_mode ENUM('cash', 'credit_card', 'paypal', 'bank_transfer', 'stripe') NOT NULL");
    });
}

public function down()
{
    Schema::table('sales', function (Blueprint $table) {
        DB::statement("ALTER TABLE sales MODIFY payment_mode ENUM('cash', 'credit_card', 'paypal', 'bank_transfer') NOT NULL");
    });
}

};
