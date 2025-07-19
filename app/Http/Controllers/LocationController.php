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
        $locations = Location::where('is_active', 1)
                           ->withCount('transactions')
                           ->with(['pickupTimes' => function($query) {
                               $query->orderBy('pickup_time');
                           }])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
        
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
            'url' => 'required|url',
            'pickup_times' => 'nullable|array',
            'pickup_times.*' => 'nullable|date_format:H:i'
        ]);

        // Create location with only the fields that exist in the table
        $location = Location::create([
            'name' => $request->input('name'),
            'url' => $request->input('url'),
            'is_active' => true
        ]);

        // Add pickup times if provided
        if ($request->input('pickup_times')) {
            foreach ($request->input('pickup_times') as $time) {
                if (!empty($time)) {
                    $location->pickupTimes()->create([
                        'pickup_time' => $time,
                        'is_active' => true
                    ]);
                }
            }
        }

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
            'url' => 'nullable|url'
        ]);

        $locationData = $request->only(['name', 'address', 'contact_person', 'contact_phone', 'operating_hours', 'url']);
        // Don't update is_active field - it's managed by soft delete only

        $location->update($locationData);

        // Dynamic navigation based on referrer
        if ($request->input('referrer') === 'show') {
            return redirect()->route('admin.locations.show', $location)
                           ->with('success', 'Lokasi berhasil diperbarui!');
        }

        return redirect()->route('admin.locations')
                       ->with('success', 'Lokasi berhasil diperbarui!');
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

    /**
     * Store a new pickup time for a location.
     */
    public function storePickupTime(Request $request, Location $location)
    {
        $request->validate([
            'pickup_time' => 'required|date_format:H:i',
        ]);

        // Cek apakah jam pickup sudah ada untuk lokasi ini
        $existingTime = $location->pickupTimes()
                                ->where('pickup_time', $request->pickup_time . ':00')
                                ->first();

        if ($existingTime) {
            return back()->with('error', 'Jam pickup sudah ada untuk lokasi ini!');
        }

        $location->pickupTimes()->create([
            'pickup_time' => $request->pickup_time . ':00',
            'is_active' => true,
        ]);

        return back()->with('success', 'Jam pickup berhasil ditambahkan!');
    }

    /**
     * Remove pickup time.
     */
    public function destroyPickupTime(Location $location, $pickupTime)
    {
        $pickupTimeModel = $location->pickupTimes()->findOrFail($pickupTime);

        $pickupTimeModel->delete();

        return back()->with('success', 'Jam pickup berhasil dihapus!');
    }
}

