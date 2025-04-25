<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailAndTelephoneToSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            if (!Schema::hasColumn('suppliers', 'email')) {
                $table->string('email')->default('example@example.com')->after('name');
            }

            if (!Schema::hasColumn('suppliers', 'telephone')) {
                $table->string('telephone')->nullable()->after('email');
            }
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('telephone');
        });
    }
}
