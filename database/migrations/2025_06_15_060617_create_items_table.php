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
        Schema::create('items', function (Blueprint $table) {

            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->onDelete('cascade'); // Foreign key to purchases table
            $table->string('item_description'); // Deskripsi item
            $table->string('unit'); // Satuan (pcs, kg, dll)
            $table->integer('qty'); // Jumlah
            $table->float('weight')->nullable(); // Total berat
            $table->float('kg_per_item')->nullable(); // Berat per item
            $table->decimal('u_price', 15, 2); // Harga per unit
            $table->decimal('amount', 18, 2); // Total amount = qty * u_price
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
