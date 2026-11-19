<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number', 32)->unique();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('class_id')
                ->constrained('classes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->unsignedBigInteger('total_amount');
            $table->enum('status', [
                'pending_payment',
                'paid',
                'confirmed',
                'cancelled',
                'refunded',
            ])->default('pending_payment');
            $table->timestamp('agreed_terms_at');
            $table->timestamp('expired_at');
            $table->timestamps();

            $table->index(['status', 'expired_at'], 'registrations_status_expired_idx');
            $table->index(['user_id', 'created_at'], 'registrations_user_created_idx');
            $table->index(['class_id', 'status'], 'registrations_class_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
