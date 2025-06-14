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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('pos_laporan')->nullable();
            $table->string('pos_saldo')->nullable();
            $table->string('deskripsi')->nullable();
            $table->integer('credit')->nullable();
            $table->integer('debit')->nullable();
            $table->timestamps();
     
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
