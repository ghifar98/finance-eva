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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');          // Nama vendor
            $table->string('attn')->nullable();   // Attention (orang yang dituju)
            $table->text('address')->nullable();  // Alamat vendor
            $table->string('email')->nullable();  // Email vendor
            $table->string('gsm_no')->nullable(); // Nomor HP
            $table->string('quote_ref')->nullable(); // Referensi Penawaran
            $table->string('subject')->nullable();   // Subjek penawaran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
