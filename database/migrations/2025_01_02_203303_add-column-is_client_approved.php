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
            $table->enum('is_client_approved', ['1', '0', '-1'])->default('0')->comment('1 = Approved, 0 = Pending, -1 = Declined');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors_invoices', function (Blueprint $table) {
            //
            $table->dropColumn('is_client_approved');
        });
    }
};
