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
            ->withCount(['classes as active_classes_count' => fn ($q) => $q->where('is_active', true)])
            ->orderBy('id')
            ->take(4)
            ->get();

        return view('public.home', [
            'featuredPrograms' => $featuredPrograms,
        ]);
    }
}
