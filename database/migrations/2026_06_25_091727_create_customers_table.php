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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('mobile_no', 20);
            $table->string('email')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('job_location')->nullable();
            $table->string('residence_location')->nullable();
            $table->decimal('salary', 12, 2)->nullable();
            $table->string('current_location')->nullable();
            $table->string('company_category')->nullable();
            $table->string('bank_eligible_for')->nullable();
            $table->string('loan_applied')->nullable();

            // Eligibility
            $table->enum('eligibility_status', ['eligible', 'not_eligible'])->default('eligible');
            $table->string('eligibility_reason')->nullable();

            // Journey
            $table->enum('journey_status', [
                'sfl',
                'underwriting',
                'approved',
                'not_approved',
                'sanctioned',
            ])->default('sfl');

            $table->string('journey_not_approved_reason')->nullable();

            // Sanctioned details
            $table->string('sanctioned_bank')->nullable();
            $table->decimal('sanctioned_loan_amount', 12, 2)->nullable();
            $table->decimal('cashback', 12, 2)->nullable();
            $table->decimal('subvention', 12, 2)->nullable();
            $table->decimal('payout_rate', 8, 2)->nullable();
            $table->text('bank_condition')->nullable();
            $table->enum('attachment_required', ['yes', 'no'])->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
