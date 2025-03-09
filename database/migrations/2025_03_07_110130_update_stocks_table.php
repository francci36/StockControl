<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStocksTable extends Migration
{
    public function up()
    {
        Schema::table('stocks', function (Blueprint $table) {
            // Ajoutez les colonnes si elles n'existent pas
            if (!Schema::hasColumn('stocks', 'product_id')) {
                $table->unsignedBigInteger('product_id')->after('id');
            }
            if (!Schema::hasColumn('stocks', 'quantity')) {
                $table->integer('quantity')->after('product_id')->default(0);
            }
            // Supprimez la colonne `quantite` si elle existe et n'est pas nécessaire
            if (Schema::hasColumn('stocks', 'quantite')) {
                $table->dropColumn('quantite');
            }
        });
    }

    public function down()
    {
        Schema::table('stocks', function (Blueprint $table) {
            // Annulez les modifications si nécessaire
            if (!Schema::hasColumn('stocks', 'product_id')) {
                $table->dropColumn('product_id');
            }
            if (!Schema::hasColumn('stocks', 'quantity')) {
                $table->dropColumn('quantity');
            }
            if (!Schema::hasColumn('stocks', 'quantite')) {
                $table->integer('quantite')->nullable();
            }
        });
    }
}
