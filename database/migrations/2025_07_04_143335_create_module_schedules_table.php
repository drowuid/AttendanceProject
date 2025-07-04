<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('module_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->date('scheduled_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_schedules');
    }
};
