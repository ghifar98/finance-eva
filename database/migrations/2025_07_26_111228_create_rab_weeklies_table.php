<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('rab_weeklies', function (Blueprint $table) {
        $table->id();
        $table->foreignId('project_id')->constrained('master_projects')->onDelete('cascade');
        $table->string('minggu');
        $table->string('kategori');
        $table->bigInteger('jumlah');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rab_weeklies');
    }
};
