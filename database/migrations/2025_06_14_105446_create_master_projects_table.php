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
        Schema::create('master_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('project_name')->nullable();
            $table->string('project_description')->nullable();
            $table->string('tahun')->nullable();
            $table->integer('nilai')->nullable();
            $table->string('kontrak')->nullable();
            $table->string('vendor')->nullable();
            $table->date('start_project')->nullable();
            $table->date('end_project')->nullable();
            $table->string('rab')->nullable();
            $table->string('data_proyek')->nullable();  


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_projects');
    }
};
