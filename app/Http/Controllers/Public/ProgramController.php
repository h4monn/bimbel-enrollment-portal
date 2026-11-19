<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgramController extends Controller
{
    public function index(Request $request): View
    {
        $level = $request->query('level');

        $programs = Program::query()
            ->where('is_active', true)
            ->when($level && in_array($level, ['SD', 'SMP', 'SMA', 'UTBK', 'UMUM'], true), function ($query) use ($level): void {
                $query->where('education_level', $level);
            })
            ->with(['classes' => fn ($q) => $q->where('is_active', true)])
            ->orderBy('education_level')
            ->orderBy('name')
            ->get();

        return view('public.programs.index', [
            'programs' => $programs,
            'activeLevel' => $level,
        ]);
    }

    public function show(Program $program): View
    {
        abort_unless($program->is_active, 404);

        $program->load([
            'classes' => fn ($q) => $q->where('is_active', true)->orderBy('name'),
        ]);

        return view('public.programs.show', [
            'program' => $program,
        ]);
    }
}
