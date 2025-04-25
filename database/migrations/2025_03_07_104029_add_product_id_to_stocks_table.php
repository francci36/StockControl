<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductIdToStocksTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('stocks', 'product_id')) {
            Schema::table('stocks', function (Blueprint $table) {
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
            });
        }
    }


    public function down()
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
        });
    }
}
