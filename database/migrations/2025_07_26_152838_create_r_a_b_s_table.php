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
    Schema::create('rabs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('project_id')->constrained('master_projects')->onDelete('cascade');
        $table->string('desc');
        $table->string('unit')->nullable();
        $table->double('qty')->default(0);
        $table->string('mat_supply')->nullable();
        $table->double('unit_price')->default(0);
        $table->double('amount')->default(0);
        $table->double('total_bef_tax')->default(0);
        $table->double('total_bef_ppn')->default(0);
        $table->double('ppn')->default(0);
        $table->double('total_after_tax')->default(0);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r_a_b_s');
    }
};
