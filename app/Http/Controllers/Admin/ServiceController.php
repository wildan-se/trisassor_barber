<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('sort_order')->paginate(20);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:100',
            'description'      => 'nullable|string|max:1000',
            'price'            => 'required|integer|min:0',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'image'            => 'nullable|url',
            'is_featured'      => 'boolean',
            'is_active'        => 'boolean',
            'sort_order'       => 'integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Service::create($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:100',
            'description'      => 'nullable|string|max:1000',
            'price'            => 'required|integer|min:0',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'image'            => 'nullable|url',
            'is_featured'      => 'boolean',
            'is_active'        => 'boolean',
            'sort_order'       => 'integer|min:0',
        ]);

        $service->update($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service)
    {
        if ($service->bookings()->whereIn('status', ['pending', 'confirmed'])->exists()) {
            return back()->withErrors(['error' => 'Layanan memiliki booking aktif dan tidak dapat dihapus.']);
        }

        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }
}
