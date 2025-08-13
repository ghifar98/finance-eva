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
        Schema::create('incomestatements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->onDelete('cascade'); // Foreign key to purchases table
                $table->string('item_description'); // Deskripsi item
                $table->enum ('type', ['debit', 'credit']);
                $table->integer('nominal'); // Nominal amount
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade'); // Foreign key to accounts table

            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomestatements');
    }
};
