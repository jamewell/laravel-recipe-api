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
        Schema::table('unit_of_measurements', function (Blueprint $table) {
            $table->string('system')->nullable();
            $table->string('type');
            $table->decimal('base_equivalent', 10, 6)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unit_of_measurements', function (Blueprint $table) {
            $table->drop('system');
            $table->drop('type');
            $table->drop('base_equivalent');
        });
    }
};
