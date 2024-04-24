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
        Schema::create('vendors_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->date('date_purchased')->nullable();
            $table->string('client_name')->nullable();
            $table->string('invoice_price')->nullable();
            $table->string('invoice_file')->nullable();
            $table->string('is_complete')->default('0');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors_invoices');
    }
};
