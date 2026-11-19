<?php

namespace App\Jobs;

use App\Mail\RegistrationConfirmedMail;
use App\Models\Registration;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendRegistrationConfirmedEmail implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(
        public int $registrationId,
    ) {}

    public function handle(): void
    {
        $registration = Registration::with(['user', 'student', 'classRoom.program'])
            ->find($this->registrationId);

        if (! $registration || ! $registration->user?->email) {
            return;
        }

        Mail::to($registration->user->email)
            ->send(new RegistrationConfirmedMail($registration));
    }
}
