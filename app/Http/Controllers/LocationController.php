<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::all();

        return response()->json([
            'status' => 'success',
            'data' => $locations
        ]);
    }
}
