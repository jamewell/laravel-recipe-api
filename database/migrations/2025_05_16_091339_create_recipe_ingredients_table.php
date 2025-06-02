<?php

use App\Models\Ingredient;
use App\Models\Recipe;
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
        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Recipe::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Ingredient::class)->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('unit_of_measurement')->onDelete('cascade');
            $table->decimal('quantity', 8, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_ingredients', function (Blueprint $table) {
            $table->dropForeign(['recipe_id']);
            $table->dropForeign(['ingredient_id']);
            $table->dropForeign(['unit_id']);
        });

        Schema::dropIfExists('recipe_ingredients');
    }
};
