<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->json('first_name'); // Translatable field
            $table->json('last_name'); // Translatable field
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->integer('age'); // Random between 18-40
            $table->enum('position', ['goalkeeper', 'defender', 'midfielder', 'attacker']);
            $table->decimal('market_value', 15, 2)->default(1000000); // $1,000,000 initial value
            $table->boolean('is_on_transfer_list')->default(false);
            $table->decimal('asking_price', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
