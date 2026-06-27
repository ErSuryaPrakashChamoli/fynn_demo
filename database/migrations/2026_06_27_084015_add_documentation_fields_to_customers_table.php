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
            $table->string('documentation_status')->nullable()->after('journey_status');
            $table->text('pending_document')->nullable()->after('documentation_status');
            
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
                'documentation_status',
                'pending_document',
            ]);
        });
    }
};
