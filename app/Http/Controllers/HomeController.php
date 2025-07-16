<?php

namespace App\Http\Controllers;

use App\Models\Menu;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 4 menu populer yang aktif
        $featuredMenus = Menu::where('is_active', 1)
            ->with('category')
            ->take(4)
            ->get();

        return view('home', compact('featuredMenus'));
    }
}
