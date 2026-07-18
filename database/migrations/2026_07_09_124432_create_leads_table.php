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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('set null');
    
            // Core Prospect Info
            $table->string('customer_name');
            $table->string('mobile_no');
            $table->string('pan_number', 10)->nullable();
            $table->string('current_location')->nullable();
            $table->string('job_location')->nullable();
            $table->decimal('salary', 15, 2)->nullable();
            
            // Transactional Status tracking
            $table->date('follow_up_date');
            $table->string('follow_up_type');
            $table->string('status')->default('Pending'); // Pending, Interested, etc.
            $table->date('next_follow_up_date')->nullable();
            $table->text('remarks');
            
            // Track Conversion Lifecycle
            $table->boolean('is_converted')->default(false);
            $table->foreignId('converted_customer_id')->nullable()->constrained('customers')->onDelete('set null'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
