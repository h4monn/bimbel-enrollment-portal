<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProgramController extends Controller
{
    public function index(): View
    {
        $programs = Program::withCount('classes')->latest()->get();

        return view('admin.programs.index', compact('programs'));
    }

    public function create(): View
    {
        return view('admin.programs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:programs,slug'],
            'education_level' => ['required', Rule::in(['SD', 'SMP', 'SMA', 'UTBK', 'UMUM'])],
            'description' => ['required', 'string'],
            'duration' => ['required', 'string', 'max:100'],
            'price' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['slug'] = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']).'-'.Str::lower(Str::random(4));
        $validated['is_active'] = $request->boolean('is_active', true);

        Program::create($validated);

        return redirect()
            ->route('admin.programs.index')
            ->with('status', 'Program berhasil ditambahkan.');
    }

    public function edit(Program $program): View
    {
        return view('admin.programs.edit', compact('program'));
    }

    public function update(Request $request, Program $program): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('programs', 'slug')->ignore($program->id)],
            'education_level' => ['required', Rule::in(['SD', 'SMP', 'SMA', 'UTBK', 'UMUM'])],
            'description' => ['required', 'string'],
            'duration' => ['required', 'string', 'max:100'],
            'price' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $validated['is_active'] = $request->boolean('is_active');

        $program->update($validated);

        return redirect()
            ->route('admin.programs.index')
            ->with('status', 'Program berhasil diperbarui.');
    }

    public function destroy(Program $program): RedirectResponse
    {
        if ($program->classes()->exists()) {
            return back()->withErrors([
                'program' => 'Program tidak dapat dihapus karena masih memiliki kelas. Nonaktifkan program sebagai gantinya.',
            ]);
        }

        $program->delete();

        return redirect()
            ->route('admin.programs.index')
            ->with('status', 'Program berhasil dihapus.');
    }
}
