<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PickupTime;
use App\Models\Location;

class PickupTimeController extends Controller
{
    /**
     * Display pickup times for a specific location (API endpoint).
     */
    public function getByLocation($locationId)
    {
        $pickupTimes = PickupTime::where('location_id', $locationId)
                                ->where('is_active', true)
                                ->orderBy('pickup_time')
                                ->get();

        return response()->json([
            'status' => 'success',
            'data' => $pickupTimes->map(function($time) {
                return [
                    'id' => $time->id,
                    'time' => $time->formatted_time,
                    'time_24h' => $time->pickup_time->format('H:i:s'),
                ];
            })
        ]);
    }

    /**
     * Display pickup times management for admin.
     */
    public function adminIndex()
    {
        $locations = Location::with(['pickupTimes' => function($query) {
            $query->orderBy('pickup_time');
        }])->where('is_active', true)->get();

        return view('admin.pickup-times.index', compact('locations'));
    }

    /**
     * Store a new pickup time.
     */
    public function store(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'pickup_time' => 'required|date_format:H:i',
        ]);

        // Cek apakah jam pickup sudah ada untuk lokasi ini
        $existingTime = PickupTime::where('location_id', $request->location_id)
                                 ->where('pickup_time', $request->pickup_time . ':00')
                                 ->first();

        if ($existingTime) {
            return back()->with('error', 'Jam pickup sudah ada untuk lokasi ini!');
        }

        PickupTime::create([
            'location_id' => $request->location_id,
            'pickup_time' => $request->pickup_time . ':00',
            'is_active' => true,
        ]);

        return back()->with('success', 'Jam pickup berhasil ditambahkan!');
    }

    /**
     * Toggle pickup time status.
     */
    public function toggleStatus(PickupTime $pickupTime)
    {
        $pickupTime->update([
            'is_active' => !$pickupTime->is_active
        ]);

        $status = $pickupTime->is_active ? 'aktif' : 'non-aktif';

        return back()->with('success', "Jam pickup {$pickupTime->formatted_time} sekarang {$status}!");
    }

    /**
     * Remove pickup time.
     */
    public function destroy(PickupTime $pickupTime)
    {
        $pickupTime->delete();

        return back()->with('success', 'Jam pickup berhasil dihapus!');
    }
}
