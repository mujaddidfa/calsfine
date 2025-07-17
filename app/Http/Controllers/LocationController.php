<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    /**
     * Display a listing of the locations (API endpoint).
     */
    public function index()
    {
        $locations = Location::all();

        return response()->json([
            'status' => 'success',
            'data' => $locations
        ]);
    }

    /**
     * Display a listing of the locations for admin dashboard.
     */
    public function adminIndex()
    {
        $locations = Location::where('is_active', 1)->withCount('transactions')->paginate(10);
        
        return view('admin.locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new location.
     */
    public function create()
    {
        return view('admin.locations.create');
    }

    /**
     * Store a newly created location in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'operating_hours' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $locationData = $request->all();
        $locationData['is_active'] = $request->has('is_active');

        Location::create($locationData);

        return redirect()->route('admin.locations')->with('success', 'Lokasi berhasil ditambahkan!');
    }

    /**
     * Display the specified location.
     */
    public function show(Location $location)
    {
        $location->load('transactions');
        return view('admin.locations.show', compact('location'));
    }

    /**
     * Show the form for editing the specified location.
     */
    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    /**
     * Update the specified location in storage.
     */
    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'operating_hours' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $locationData = $request->all();
        $locationData['is_active'] = $request->has('is_active');

        $location->update($locationData);

        return redirect()->route('admin.locations')->with('success', 'Lokasi berhasil diperbarui!');
    }

    /**
     * Remove the specified location from storage.
     */
    public function destroy(Location $location)
    {
        // Check if location has transactions
        if ($location->transactions()->count() > 0) {
            return back()->with('error', 'Lokasi tidak dapat dihapus karena masih memiliki transaksi!');
        }

        // Soft delete by setting is_active to false
        $location->update(['is_active' => false]);

        return redirect()->route('admin.locations')->with('success', 'Lokasi berhasil dihapus!');
    }

    /**
     * Toggle location status.
     */
    public function toggleStatus(Location $location)
    {
        $location->update([
            'is_active' => !$location->is_active
        ]);

        $status = $location->is_active ? 'aktif' : 'non-aktif';

        return back()->with('success', "Lokasi {$location->name} sekarang {$status}!");
    }
}
