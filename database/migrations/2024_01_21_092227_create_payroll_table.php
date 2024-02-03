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
        Schema::create('payroll', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->date('payroll_start');
            $table->date('payroll_end');
            $table->text('payroll_file')->nullable();
            $table->text('time_sheet_file')->nullable();
            $table->enum('status', ['-1', '0', '1'])->default('0')->comment('-1 cancelled, 0 pending, 1 completed');
            $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll');
    }
};
