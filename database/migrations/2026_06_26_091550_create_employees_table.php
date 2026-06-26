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
        Schema::create('employees', function (Blueprint $table) {
                $table->id();
                $table->string('emp_id')->unique();
                $table->string('emp_name');

                $table->string('email')->unique();

                $table->string('designation');

                $table->date('doj')->nullable();

                $table->date('reporting_date')->nullable();

                $table->foreignId('superviser_id')
                    ->nullable()
                    ->constrained('employees')
                    ->nullOnDelete();

                $table->foreignId('manager_id')
                    ->nullable()
                    ->constrained('employees')
                    ->nullOnDelete();

                $table->string('cost_center')->nullable();

                $table->string('unit_name')->nullable();

                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
