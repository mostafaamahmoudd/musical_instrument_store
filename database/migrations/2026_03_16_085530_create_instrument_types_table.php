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
        Schema::create('instrument_types', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('instrument_family_id')
                ->constrained('instrument_families')
                ->onDelete('cascade');
            
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();

            $table->index(['instrument_family_id', 'name']);
            $table->unique(['instrument_family_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrument_types');
    }
};
