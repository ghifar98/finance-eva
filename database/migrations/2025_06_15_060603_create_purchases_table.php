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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->date('date'); // Tanggal pembelian
            $table->string('po_no'); // Nomor PO
            $table->string('company'); // Nama perusahaan
            $table->foreignId('project_id') // ID proyek (foreign key)
                ->constrained('master_projects') // Pastikan ada tabel masterprojects
                ->onDelete('cascade'); // Hapus pembelian jika proyek dihapus
            $table->foreignId('vendor_id') // ID vendor (foreign key)
                ->constrained('vendors') // Pastikan ada tabel vendors
                ->onDelete('cascade'); // Hapus pembelian jika vendor dihapus
            $table->string('package')->nullable(); // Nama paket (opsional)
            $table->string('rep_name')->nullable(); // Nama perwakilan (opsional)
            $table->string('phone')->nullable(); // Nomor HP perwakilan (opsional)
            $table->integer('total_amount')->nullable(); // Total jumlah pembelian
            $table->integer('qty')->nullable(); // Jumlah yang sudah dibayar
            $table->integer('balance')->nullable(); // Sisa pembayaran
            $table->integer('total_ppn')->nullable(); // Total PPN
            // make account_id nullable
            $table->foreignId('account_id')->nullable() // ID akun (foreign key)
                ->constrained('accounts') // Pastikan ada tabel accounts
                ->onDelete('set null'); // Set akun ke null jika akun dihapus

            $table->timestamps();

            // Foreign key constraint ke masterprojects (pastikan ada tabel ini)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
