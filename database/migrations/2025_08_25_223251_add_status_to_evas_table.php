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
        Schema::table('evas', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('notes');
            $table->timestamp('status_updated_at')->nullable()->after('status');
            $table->unsignedBigInteger('status_updated_by')->nullable()->after('status_updated_at');
            
            $table->foreign('status_updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evas', function (Blueprint $table) {
            $table->dropForeign(['status_updated_by']);
            $table->dropColumn(['status', 'status_updated_at', 'status_updated_by']);
        });
    }
};