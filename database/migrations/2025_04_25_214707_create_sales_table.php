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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id')->nullable(); // Facultatif, si vous associez des utilisateurs
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2);
            $table->enum('payment_mode', ['cash', 'credit_card', 'paypal', 'bank_transfer']);
            $table->timestamps();

            // Clé étrangère vers la table des produits
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            // Clé étrangère vers la table des utilisateurs
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
