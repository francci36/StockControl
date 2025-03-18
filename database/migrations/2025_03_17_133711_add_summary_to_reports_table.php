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
        Schema::table('reports', function (Blueprint $table) {
            $table->text('summary')->nullable()->after('description'); // Ajoutez la colonne `summary`
        });
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('summary'); // Supprimez la colonne `summary` en cas de rollback
        });
    }
};
