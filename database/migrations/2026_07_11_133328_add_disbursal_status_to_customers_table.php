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
        $table->enum('disbursal_status', [
                'disbursed',
                'dropped',
                'carry_forward',
            ])->nullable()->after('journey_status'); // Change 'journey_status' if needed
            $table->date('carry_forward_date')->nullable()->after('disbursal_status');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
                 $table->dropColumn('disbursal_status');
                 $table->dropColumn('carry_forward_date');
        });
    }
};
