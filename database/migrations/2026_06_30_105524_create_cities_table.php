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
        Schema::create('cities', function (Blueprint $table) {
             $table->id();

            $table->string('country')->default('India');
            $table->string('state');
            $table->string('city');

            $table->string('state_code')->nullable();
            $table->string('city_code')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['state', 'city']);
            $table->index('state');
            $table->index('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
