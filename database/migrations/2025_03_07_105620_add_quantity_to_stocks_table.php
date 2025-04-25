<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuantityToStocksTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('stocks', 'quantity')) {
            Schema::table('stocks', function (Blueprint $table) {
                $table->integer('quantity')->notNull()->default(0)->after('product_id');
            });
        }
    }


    public function down()
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
}
