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
            $table->text('sfl_remarks')->nullable()->after('channel');
            $table->text('underwriting_remarks')->nullable()->after('sfl_remarks');
            $table->text('approved_remarks')->nullable()->after('underwriting_remarks');
            $table->text('sanctioned_remarks')->nullable()->after('approved_remarks');
            $table->text('not_approved_remarks')->nullable()->after('sanctioned_remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
                  $table->dropColumn([
                'sfl_remarks',
                'underwriting_remarks',
                'approved_remarks',
                'sanctioned_remarks',
                'not_approved_remarks',
            ]);
        });
    }
};
