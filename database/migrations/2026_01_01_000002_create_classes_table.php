<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')
                ->constrained('programs')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('name');
            $table->string('teacher_name');
            $table->string('schedule_days', 100);
            $table->string('schedule_time', 100);
            $table->string('room', 100);
            $table->unsignedInteger('quota');
            $table->unsignedInteger('enrolled_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['program_id', 'is_active'], 'classes_program_active_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
