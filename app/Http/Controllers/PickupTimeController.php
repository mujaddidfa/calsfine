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
}
