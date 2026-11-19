<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassRoomController extends Controller
{
    public function index(): View
    {
        $classes = ClassRoom::with('program')
            ->orderByDesc('is_active')
            ->orderBy('program_id')
            ->orderBy('name')
            ->get();

        return view('admin.classes.index', compact('classes'));
    }

    public function create(): View
    {
        $programs = Program::orderBy('name')->get();

        return view('admin.classes.create', compact('programs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'program_id' => ['required', 'integer', 'exists:programs,id'],
            'name' => ['required', 'string', 'max:255'],
            'teacher_name' => ['required', 'string', 'max:255'],
            'schedule_days' => ['required', 'string', 'max:100'],
            'schedule_time' => ['required', 'string', 'max:100'],
            'room' => ['required', 'string', 'max:100'],
            'quota' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        ClassRoom::create($validated);

        return redirect()
            ->route('admin.classes.index')
            ->with('status', 'Kelas berhasil ditambahkan.');
    }

    public function edit(ClassRoom $classRoom): View
    {
        $programs = Program::orderBy('name')->get();

        return view('admin.classes.edit', compact('classRoom', 'programs'));
    }

    public function update(Request $request, ClassRoom $classRoom): RedirectResponse
    {
        $validated = $request->validate([
            'program_id' => ['required', 'integer', 'exists:programs,id'],
            'name' => ['required', 'string', 'max:255'],
            'teacher_name' => ['required', 'string', 'max:255'],
            'schedule_days' => ['required', 'string', 'max:100'],
            'schedule_time' => ['required', 'string', 'max:100'],
            'room' => ['required', 'string', 'max:100'],
            'quota' => ['required', 'integer', "min:{$classRoom->enrolled_count}"],
            'is_active' => ['boolean'],
        ], [
            'quota.min' => "Kuota tidak boleh lebih kecil dari jumlah siswa terdaftar saat ini ({$classRoom->enrolled_count}).",
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $classRoom->update($validated);

        return redirect()
            ->route('admin.classes.index')
            ->with('status', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(ClassRoom $classRoom): RedirectResponse
    {
        if ($classRoom->registrations()->exists()) {
            return back()->withErrors([
                'class' => 'Kelas tidak dapat dihapus karena sudah memiliki data pendaftaran. Nonaktifkan kelas sebagai gantinya.',
            ]);
        }

        $classRoom->delete();

        return redirect()
            ->route('admin.classes.index')
            ->with('status', 'Kelas berhasil dihapus.');
    }
}
