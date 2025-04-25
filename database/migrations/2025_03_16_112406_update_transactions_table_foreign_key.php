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
        Schema::table('transactions', function (Blueprint $table) {
            // Vérifie si la colonne existe avant de tenter de supprimer la clé étrangère
            if (Schema::hasColumn('transactions', 'product_id')) {
                // Utilise le nom correct de la clé étrangère
                $table->dropForeign(['product_id']);
            }
        });
    }


    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Vérifie si la colonne 'product_id' existe avant de recréer la clé étrangère
            if (Schema::hasColumn('transactions', 'product_id')) {
                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade');
            }
        });
    }


};
