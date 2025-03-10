<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceToTransactionsTable extends Migration
{
    public function up()
{
    Schema::table('transactions', function (Blueprint $table) {
        $table->decimal('price', 8, 2)->default(0)->after('quantity'); // Ajoutez ->default(0)
    });
}

public function down()
{
    Schema::table('transactions', function (Blueprint $table) {
        $table->dropColumn('price');
    });
}
}