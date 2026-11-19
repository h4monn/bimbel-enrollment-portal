<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $registrations = Registration::query()
            ->where('user_id', $request->user('web')->id)
            ->with(['student', 'classRoom.program', 'latestPayment'])
            ->latest()
            ->get();

        return view('public.dashboard', [
            'registrations' => $registrations,
        ]);
    }
}
