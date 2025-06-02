<?php

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
        Schema::create('instructions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Recipe::class)->constrained()->onDelete('cascade');
            $table->integer('step_number');
            $table->text('description');
            $table->string('img_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructions', function (Blueprint $table) {
            $table->dropForeign(['recipe_id']);
        });

        Schema::dropIfExists('instructions');
    }
};
