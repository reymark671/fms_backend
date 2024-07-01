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
        Schema::create('service_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('service_code_description');
            $table->unsignedBigInteger('service_code_category_id');
            $table->timestamps();
            $table->softDeletes();
        
            $table->foreign('service_code_category_id')
                  ->references('id')
                  ->on('service_code_categories')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_codes');
    }
};
