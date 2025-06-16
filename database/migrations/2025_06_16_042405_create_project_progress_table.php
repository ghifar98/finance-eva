<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectProgressTable extends Migration
{
    public function up()
    {
        Schema::create('project_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('master_projects')->onDelete('cascade');
            $table->date('progress_date');
            $table->enum('type', ['daily', 'weekly', 'milestone']);
            $table->decimal('progress_value', 5, 2); // 0.00 to 100.00
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index('progress_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_progress');
    }
}