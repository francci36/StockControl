<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock_threshold')->default(5)->change(); // Définit 5 comme valeur par défaut
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock_threshold')->default(null)->change(); // Supprime la valeur par défaut
        });
    }

};
