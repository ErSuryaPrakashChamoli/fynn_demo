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
        Schema::create('employee_reporting_history', function (Blueprint $table) {
            $table->id();
              $table->foreignId('employee_id')
                ->constrained('employees')
                ->cascadeOnDelete();

            // Previous hierarchy
            $table->foreignId('old_superviser_id')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            $table->foreignId('old_manager_id')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            $table->foreignId('old_cluster_id')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            // New hierarchy
            $table->foreignId('new_superviser_id')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            $table->foreignId('new_manager_id')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            $table->foreignId('new_cluster_id')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            // Effective date of reporting change
            $table->date('effective_date');

            // Reason for change
            $table->enum('change_type', [
                'joining',
                'reporting_change',
                'promotion',
                'transfer',
                'exit',
            ])->default('reporting_change');

            // Who performed the change
            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index(['employee_id', 'effective_date']);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_reporting_history');
    }
};
