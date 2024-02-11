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
        Schema::table('employees', function (Blueprint $table) {
            //
            $table->string('SP_number')->after('last_name'); 
            $table->string('Username');
            $table->string('password');
            $table->enum('Status', ['-1', '0', '1'])->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            //
            $table->string('SP_number'); 
            $table->string('Username');
            $table->string('password');
            $table->enum('Status');
        });
    }
};
