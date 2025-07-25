<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
    public function adminIndex(Request $request)
    {
        $query = Menu::where('is_active', 1)->with('category')->orderBy('created_at', 'desc');
        
        // Filter by category if specified
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        $menus = $query->paginate(10);
        $categories = Category::where('is_active', 1)->get();
        
        // Append the category filter to pagination links
        $menus->appends($request->only('category'));
        
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('menus')->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->category_id)
                                 ->where('is_active', 1);
                })
            ],
            'description' => 'nullable|string',
            'price' => [
                'required',
                'numeric',
                'min:0',
                'regex:/^[1-9][0-9]*$|^0$/'
            ],
            'stock' => [
                'required',
                'integer',
                'min:0',
                'regex:/^[1-9][0-9]*$|^0$/'
            ],
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'name.unique' => 'Nama menu sudah ada dalam kategori yang sama. Silakan gunakan nama yang berbeda.',
            'price.regex' => 'Harga tidak boleh dimulai dengan angka 0.',
            'stock.regex' => 'Stok tidak boleh dimulai dengan angka 0.'
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
        // Load the category relationship for better performance
        $menu->load('category');
        
        return view('admin.menus.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified menu.
     */
    public function edit(Menu $menu)
    {
        $categories = Category::where('is_active', 1)->get();
        
        // Determine where user came from
        $referrer = request('from', 'show'); // default to 'show' if not specified
        
        return view('admin.menus.edit', compact('menu', 'categories', 'referrer'));
    }

    /**
     * Update the specified menu in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('menus')->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->category_id)
                                 ->where('is_active', 1);
                })->ignore($menu->id)
            ],
            'description' => 'nullable|string',
            'price' => [
                'required',
                'numeric',
                'min:0',
                'regex:/^[1-9][0-9]*$|^0$/'
            ],
            'stock' => [
                'required',
                'integer',
                'min:0',
                'regex:/^[1-9][0-9]*$|^0$/'
            ],
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'name.unique' => 'Nama menu sudah ada dalam kategori yang sama. Silakan gunakan nama yang berbeda.',
            'price.regex' => 'Harga tidak boleh dimulai dengan angka 0.',
            'stock.regex' => 'Stok tidak boleh dimulai dengan angka 0.'
        ]);

        $menuData = $request->all();
        
        // Handle image removal
        if ($request->has('remove_image') && $request->input('remove_image')) {
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
                $menuData['image'] = null;
            }
        }
        
        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            
            $imagePath = $request->file('image')->store('menu-images', 'public');
            $menuData['image'] = $imagePath;
        }

        $menu->update($menuData);

        // Redirect based on where user came from
        $from = $request->input('from', 'show');
        
        if ($from === 'index') {
            return redirect()->route('admin.menus')->with('success', 'Menu berhasil diperbarui!');
        } else {
            return redirect()->route('admin.menus.show', $menu)->with('success', 'Menu berhasil diperbarui!');
        }
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
