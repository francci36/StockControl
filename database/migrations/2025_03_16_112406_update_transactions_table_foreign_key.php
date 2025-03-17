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
            // Vérifie si la clé étrangère existe avant de tenter de la supprimer
            if (Schema::hasColumn('transactions', 'product_id')) {
                $table->dropForeign('fk_transactions_product_id'); // Utilise le bon nom
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
