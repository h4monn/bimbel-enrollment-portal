<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Registration;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalRegistrations = Registration::count();
        $confirmedCount = Registration::whereIn('status', ['paid', 'confirmed'])->count();
        $pendingCount = Registration::where('status', 'pending_payment')->count();
        $totalRevenue = (int) Registration::whereIn('status', ['paid', 'confirmed'])->sum('total_amount');

        $classes = ClassRoom::with('program')
            ->orderByDesc('is_active')
            ->orderBy('program_id')
            ->orderBy('name')
            ->get();

        $recentRegistrations = Registration::with(['student', 'user', 'classRoom.program'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'totalRegistrations' => $totalRegistrations,
            'confirmedCount' => $confirmedCount,
            'pendingCount' => $pendingCount,
            'totalRevenue' => $totalRevenue,
            'classes' => $classes,
            'recentRegistrations' => $recentRegistrations,
        ]);
    }
}
