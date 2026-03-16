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
        Schema::create('instrument_specs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('instrument_family_id')->constrained('instrument_families')->onDelete('cascade');
            $table->foreignId('builder_id')->constrained('builders')->onDelete('cascade');
            $table->foreignId('instrument_type_id')->constrained('instrument_types')->onDelete('cascade');
            $table->foreignId('back_wood_id')->nullable()->constrained('wood')->onDelete('set null');
            $table->foreignId('top_wood_id')->nullable()->constrained('wood')->onDelete('set null');

            $table->string('model')->nullable();
            $table->integer('num_strings')->nullable();
            $table->string('style')->nullable();
            $table->string('finish')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('builder_id');
            $table->index('instrument_family_id');
            $table->index('instrument_type_id');
            $table->index('model');
            $table->index(
                ['instrument_family_id', 'builder_id', 'instrument_type_id'],
                'instrument_specs_family_builder_type_idx'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrument_specs');
    }
};
