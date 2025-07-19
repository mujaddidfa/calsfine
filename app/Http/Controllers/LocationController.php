<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
                        'pickup_time' => $time
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
            'url' => 'required|url',
            'pickup_times' => 'nullable|array',
            'pickup_times.*' => 'nullable|date_format:H:i',
            'pickup_times_existing' => 'nullable|array',
            'pickup_times_existing.*' => 'nullable|date_format:H:i',
            'pickup_times_delete' => 'nullable|array',
            'pickup_times_delete.*' => 'nullable|integer',
        ]);

        $location->update([
            'name' => $request->input('name'),
            'url' => $request->input('url'),
        ]);

        // Debug: log the request payload to storage/logs/laravel.log
        Log::info('Location update request', $request->all());

        // Hapus pickup time yang diminta
        if ($request->has('pickup_times_delete')) {
            foreach ($request->input('pickup_times_delete') as $id) {
                $pickupTime = $location->pickupTimes()->find($id);
                if ($pickupTime) {
                    $pickupTime->delete();
                }
            }
        }

        // Update existing pickup times
        if ($request->has('pickup_times_existing')) {
            foreach ($request->input('pickup_times_existing') as $id => $time) {
                $pickupTime = $location->pickupTimes()->find($id);
                if ($pickupTime && !empty($time)) {
                    $pickupTime->update(['pickup_time' => $time]);
                }
            }
        }

        // Tambah pickup time baru
        if ($request->has('pickup_times')) {
            foreach ($request->input('pickup_times') as $time) {
                if (!empty($time)) {
                    // Format ke H:i:s
                    $pickupTimeFormatted = strlen($time) === 5 ? $time . ':00' : $time;
                    // Cek duplikat (jangan insert jika sudah ada di DB untuk lokasi ini)
                    $exists = $location->pickupTimes()->where('pickup_time', $pickupTimeFormatted)->exists();
                    if (!$exists) {
                        $location->pickupTimes()->create([
                            'pickup_time' => $pickupTimeFormatted
                        ]);
                    }
                }
            }
        }

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

        // Hapus semua pickup time terkait lokasi ini
        $location->pickupTimes()->delete();

        // Soft delete by setting is_active to false
        $location->update(['is_active' => false]);

        return redirect()->route('admin.locations')->with('success', 'Lokasi dan semua jam pickup terkait berhasil dihapus!');
    }
}

