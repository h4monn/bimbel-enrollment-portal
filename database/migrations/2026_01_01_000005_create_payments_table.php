<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')
                ->constrained('registrations')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('order_id', 64)->unique();
            $table->string('payment_method', 50)->nullable();
            $table->string('gateway_reference')->nullable()->index();
            $table->unsignedBigInteger('amount');
            $table->enum('status', [
                'pending',
                'paid',
                'expired',
                'failed',
                'refunded',
            ])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->text('refund_notes')->nullable();
            $table->json('raw_payload')->nullable();
            $table->timestamps();

            $table->index(['registration_id', 'status'], 'payments_reg_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
