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
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['product_id']); // Supprime la contrainte
            $table->dropColumn('product_id');   // Supprime la colonne
        });
    }
    
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }
    

    
};
