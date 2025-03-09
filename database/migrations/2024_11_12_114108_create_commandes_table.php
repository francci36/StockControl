<?php

// database/migrations/2024_11_12_114108_create_commandes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandesTable extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('statut')->default('En cours');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('product_id'); // Ajoutez cette ligne
            $table->integer('quantity');
            $table->string('status');
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade'); // Ajoutez cette ligne
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
}
