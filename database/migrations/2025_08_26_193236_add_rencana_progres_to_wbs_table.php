<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wbs', function (Blueprint $table) {
            $table->decimal('rencana_progres', 5, 2)->default(0)->after('kode')->comment('Rencana persentase progres (0-100)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wbs', function (Blueprint $table) {
            $table->dropColumn('rencana_progres');
        });
    }
};