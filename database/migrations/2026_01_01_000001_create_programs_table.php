<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('education_level', ['SD', 'SMP', 'SMA', 'UTBK', 'UMUM']);
            $table->text('description');
            $table->string('duration', 100);
            $table->unsignedBigInteger('price');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['education_level', 'is_active'], 'programs_level_active_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
