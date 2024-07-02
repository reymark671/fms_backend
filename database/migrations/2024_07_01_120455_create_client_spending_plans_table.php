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
        Schema::create('client_spending_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->float('total_budget');
            $table->date('from');
            $table->date('to');
            $table->timestamps();
        
            $table->foreign('client_id')
                  ->references('id')
                  ->on('clients')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_spending_plans');
    }
};
