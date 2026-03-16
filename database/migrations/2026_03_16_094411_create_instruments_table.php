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
        Schema::create('instruments', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number');
            $table->string('sku')->nullable();

            $table->foreignId('instrument_spec_id')
                ->nullable()
                ->constrained('instrument_specs')
                ->onDelete('set null');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->decimal('price', 10, 2);
            $table->string('condition');
            $table->string('stock_status');
            $table->date('year_made')->nullable();
            $table->integer('quantity');
            $table->boolean('featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('stock_status');
            $table->index('price');
            $table->index('featured');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instruments');
    }
};
