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
        Schema::create('client_spending_plan_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_spending_plan_id');
            $table->unsignedBigInteger('service_code_id');
            $table->decimal('allocated_budget', 15, 2);
            $table->timestamps();

            $table->foreign('client_spending_plan_id')
                ->references('id')
                ->on('client_spending_plans')
                ->onDelete('cascade');

            $table->foreign('service_code_id')
                ->references('id')
                ->on('service_codes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_spending_plan_items');
    }
};
