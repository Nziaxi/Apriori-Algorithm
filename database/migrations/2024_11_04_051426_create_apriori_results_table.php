<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('apriori_results', function (Blueprint $table) {
            $table->id();
            $table->json('items');
            $table->json('pick_items')->nullable();
            $table->string('recommendation')->nullable();
            $table->decimal('support', 8, 2);
            $table->decimal('confidence', 8, 2)->nullable();
            $table->decimal('lift', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apriori_results');
    }
};
