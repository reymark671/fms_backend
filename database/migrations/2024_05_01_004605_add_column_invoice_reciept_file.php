<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vendors_invoices', function (Blueprint $table) {
            //
            $table->string('reciept_file')->after('invoice_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors_invoices', function (Blueprint $table) {
            //
            $table->dropColumn('reciept_file');
        });
    }
};
