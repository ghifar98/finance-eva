<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('evas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('master_projects')->onDelete('cascade');
            $table->integer('week_number');
            $table->date('report_date');
            $table->decimal('progress', 5, 2);
            $table->decimal('bac', 15, 2);
            $table->decimal('ac', 15, 2);
            $table->decimal('ev', 15, 2);
            $table->decimal('spi', 8, 4);
            $table->decimal('cpi', 8, 4);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evas');
    }
};
