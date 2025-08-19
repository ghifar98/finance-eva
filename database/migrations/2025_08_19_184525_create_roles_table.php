<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // project_manager, finance_officer, project_director, staff
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('roles');
    }
};

