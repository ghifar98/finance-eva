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
    Schema::table('incomestatements', function (Blueprint $table) {
        $table->foreignId('credit_account_id')->nullable()->constrained('accounts')->onDelete('set null');
        $table->foreignId('debit_account_id')->nullable()->constrained('accounts')->onDelete('set null');
        $table->foreignId('project_id')->nullable()->constrained('master_projects')->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('incomestatements', function (Blueprint $table) {
        $table->dropForeign(['credit_account_id']);
        $table->dropColumn('credit_account_id');

        $table->dropForeign(['debit_account_id']);
        $table->dropColumn('debit_account_id');

        $table->dropForeign(['project_id']);
        $table->dropColumn('project_id');
    });
}
};

