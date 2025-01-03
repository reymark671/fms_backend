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
        Schema::create('vendor_bank_account', function (Blueprint $table) {
            //
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->tinyInteger('account_type')->comment('1: Savings, 2: Checking');
            $table->string('bank_name', 100);
            $table->unsignedBigInteger('routing_number');
            $table->unsignedBigInteger('account_number');
            $table->boolean('paystub_copy')->default(false);
            $table->timestamps();

            $table->foreign('vendor_id')
                ->references('id')
                ->on('vendors')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_bank_account', function (Blueprint $table) {
            //
        });
    }
};
