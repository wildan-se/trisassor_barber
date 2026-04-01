<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BarberController extends Controller
{
    public function index()
    {
        $barbers = Barber::withCount('bookings')->latest()->paginate(15);
        return view('admin.barbers.index', compact('barbers'));
    }

    public function create()
    {
        return view('admin.barbers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'specialty' => 'nullable|string|max:100',
            'bio'       => 'nullable|string|max:1000',
            'instagram' => 'nullable|string|max:100',
            'photo'     => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('barbers', 'public');
        }

        Barber::create($validated);

        return redirect()->route('admin.barbers.index')
            ->with('success', 'Barber berhasil ditambahkan.');
    }

    public function edit(Barber $barber)
    {
        return view('admin.barbers.edit', compact('barber'));
    }

    public function update(Request $request, Barber $barber)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'specialty' => 'nullable|string|max:100',
            'bio'       => 'nullable|string|max:1000',
            'instagram' => 'nullable|string|max:100',
            'photo'     => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            if ($barber->photo) Storage::disk('public')->delete($barber->photo);
            $validated['photo'] = $request->file('photo')->store('barbers', 'public');
        }

        $barber->update($validated);

        return redirect()->route('admin.barbers.index')
            ->with('success', 'Data barber berhasil diperbarui.');
    }

    public function destroy(Barber $barber)
    {
        if ($barber->bookings()->whereIn('status', ['pending', 'confirmed'])->exists()) {
            return back()->withErrors(['error' => 'Barber memiliki booking aktif dan tidak dapat dihapus.']);
        }

        if ($barber->photo) Storage::disk('public')->delete($barber->photo);
        $barber->delete();

        return redirect()->route('admin.barbers.index')
            ->with('success', 'Barber berhasil dihapus.');
    }
}
