<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProgressToMasterProjectsTable extends Migration
{
    public function up()
    {
        Schema::table('master_projects', function (Blueprint $table) {
            $table->decimal('progress', 5, 2)->default(0)->after('data_proyek');
        });
    }

    public function down()
    {
        Schema::table('master_projects', function (Blueprint $table) {
            $table->dropColumn('progress');
        });
    }
}