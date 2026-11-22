<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredPrograms = Program::query()
            ->where('is_active', true)
            ->with(['classes' => fn ($q) => $q->where('is_active', true)])
            ->orderByRaw("CASE slug WHEN 'matematika-sd' THEN 1 WHEN 'matematika-smp' THEN 2 WHEN 'inggris-sma' THEN 3 WHEN 'intensif-utbk' THEN 4 ELSE 99 END")
            ->orderBy('id')
            ->take(4)
            ->get();

        return view('public.home', [
            'featuredPrograms' => $featuredPrograms,
        ]);
    }
}
