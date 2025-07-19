<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories for admin.
     */
    public function index()
    {
        $categories = Category::where('is_active', 1)
                            ->withCount('menus')
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
        
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string'
        ]);

        $categoryData = $request->all();
        $categoryData['is_active'] = true; // Always set new categories as active

        Category::create($categoryData);

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        $category->load('menus');
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string'
        ]);

        $categoryData = $request->only(['name', 'description']);
        // Don't update is_active field - it's managed by soft delete only

        $category->update($categoryData);

        // Dynamic navigation based on referrer
        if ($request->input('referrer') === 'show') {
            return redirect()->route('admin.categories.show', $category)
                           ->with('success', 'Kategori berhasil diperbarui!');
        }

        return redirect()->route('admin.categories')
                       ->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has active menus
        if ($category->menus()->where('is_active', 1)->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki menu aktif!');
        }

        // Soft delete by setting is_active to false
        $category->update(['is_active' => false]);

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil dihapus!');
    }

    /**
     * Toggle category status.
     */
    public function toggleStatus(Category $category)
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);

        $status = $category->is_active ? 'aktif' : 'non-aktif';

        return back()->with('success', "Kategori {$category->name} sekarang {$status}!");
    }
}
