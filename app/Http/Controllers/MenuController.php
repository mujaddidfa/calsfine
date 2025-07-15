<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::where('is_active', 1)
            ->with('category')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $menus
        ]);
    }
}
