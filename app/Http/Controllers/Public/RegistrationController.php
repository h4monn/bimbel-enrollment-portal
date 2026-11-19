<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StoreRegistrationRequest;
use App\Models\ClassRoom;
use App\Models\Registration;
use App\Services\RegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function __construct(
        protected RegistrationService $registrationService,
    ) {}

    public function create(ClassRoom $classRoom): View
    {
        $classRoom->load('program');

        abort_if(! $classRoom->is_active || ! $classRoom->program?->is_active, 404);
        abort_if($classRoom->remaining_quota <= 0, 422, 'Kuota kelas ini sudah penuh.');

        return view('public.registrations.create', [
            'classRoom' => $classRoom,
            'user' => auth('web')->user(),
        ]);
    }

    public function store(StoreRegistrationRequest $request): RedirectResponse
    {
        $result = $this->registrationService->registerAndCreatePayment(
            $request->user('web'),
            $request->validated()
        );

        return redirect()
            ->route('registrations.checkout', $result['registration'])
            ->with('snap_token', $result['snap_token'])
            ->with('redirect_url', $result['redirect_url']);
    }

    public function checkout(Request $request, Registration $registration): View
    {
        abort_if($registration->user_id !== $request->user('web')->id, 403);

        $registration->load(['student', 'classRoom.program', 'latestPayment']);

        return view('public.registrations.checkout', [
            'registration' => $registration,
            'snapToken' => session('snap_token'),
            'redirectUrl' => session('redirect_url'),
        ]);
    }

    public function success(Request $request, Registration $registration): View
    {
        abort_if($registration->user_id !== $request->user('web')->id, 403);

        $registration->load(['student', 'classRoom.program', 'latestPayment']);

        return view('public.registrations.success', [
            'registration' => $registration,
        ]);
    }
}
