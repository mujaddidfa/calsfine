<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of the menus (API endpoint).
     */
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

    /**
     * Display a listing of the menus for admin dashboard.
     */
    public function adminIndex()
    {
        $menus = Menu::where('is_active', 1)->with('category')->paginate(10);
        $categories = Category::where('is_active', 1)->get();
        
        return view('admin.menus.index', compact('menus', 'categories'));
    }

    /**
     * Show the form for creating a new menu.
     */
    public function create()
    {
        $categories = Category::where('is_active', 1)->get();
        return view('admin.menus.create', compact('categories'));
    }

    /**
     * Store a newly created menu in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $menuData = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu-images', 'public');
            $menuData['image'] = $imagePath;
        }

        $menuData['is_active'] = 1; // Set as active by default

        Menu::create($menuData);

        return redirect()->route('admin.menus')->with('success', 'Menu berhasil ditambahkan!');
    }

    /**
     * Display the specified menu.
     */
    public function show(Menu $menu)
    {
        return view('admin.menus.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified menu.
     */
    public function edit(Menu $menu)
    {
        $categories = Category::where('is_active', 1)->get();
        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    /**
     * Update the specified menu in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $menuData = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            
            $imagePath = $request->file('image')->store('menu-images', 'public');
            $menuData['image'] = $imagePath;
        }

        $menu->update($menuData);

        return redirect()->route('admin.menus')->with('success', 'Menu berhasil diperbarui!');
    }

    /**
     * Remove the specified menu from storage (soft delete).
     */
    public function destroy(Menu $menu)
    {
        // Soft delete by setting is_active to false
        $menu->update(['is_active' => false]);

        return redirect()->route('admin.menus')->with('success', 'Menu berhasil dihapus!');
    }
}
