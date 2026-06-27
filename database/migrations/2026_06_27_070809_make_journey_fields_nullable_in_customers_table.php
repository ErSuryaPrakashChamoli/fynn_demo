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
        Schema::table('customers', function (Blueprint $table) {
            //
            $table->string('company_category')->nullable()->change();
            $table->string('loan_applied')->nullable()->change();
            $table->string('bank_eligible_for')->nullable()->change();
            $table->string('journey_status')->nullable()->change();
            $table->string('journey_not_approved_reason')->nullable()->change();
            $table->string('other_loan_applied')->nullable()->change();
            $table->string('other_bank_eligible_for')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
              $table->string('company_category')->nullable(false)->change();
            $table->string('loan_applied')->nullable(false)->change();
            $table->string('bank_eligible_for')->nullable(false)->change();
            $table->string('journey_status')->nullable(false)->change();
            $table->string('journey_not_approved_reason')->nullable(false)->change();
            $table->string('other_loan_applied')->nullable(false)->change();
            $table->string('other_bank_eligible_for')->nullable(false)->change();
        });
    }
};
