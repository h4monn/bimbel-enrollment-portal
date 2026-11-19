<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('full_name');
            $table->date('birth_date');
            $table->string('school_name');
            $table->string('education_level', 50);
            $table->string('grade', 50);
            $table->timestamps();

            $table->index(['user_id', 'full_name'], 'students_user_name_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
