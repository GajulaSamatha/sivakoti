<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\View\View
     */
    // ...

    public function index()
    {
        $categories = Category::with('children')->whereNull('parent_id')->orderBy('order')->get();

        return view('superadmin.categories.superadmin_categories_index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('superadmin.categories.superadmin_categories_create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validation to ensure data is correct before saving
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Create a unique slug from the title
        $slug = Str::slug($request->title);

        // Create the new category
        Category::create([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => $slug,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('superadmin.categories.index')->with('success', 'Category created successfully!');
    }
    
    //seperate for each category and its children
    public function show(Category $category)
    {
        // This line fetches all child categories for the given parent category
        $children = $category->children()->orderBy('order')->get();

        // The 'compact()' function now has access to both 'category' and 'children'
        return view('superadmin.categories.superadmin_categories_view', compact('category', 'children'));
    }

    public function edit(Category $category)
    {
        $categories = Category::orderBy('order')->get();
        return view('superadmin.categories.superadmin_categories_edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'title' => 'required|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'order' => 'nullable|integer',
        ]);

        $category->update($request->all());

        return redirect()->route('superadmin.categories.index')->with('success', 'Category updated successfully.');
    }

public function destroy(Category $category)
{
    // Prevent deletion if the category has children
    if ($category->children()->count() > 0) {
        return redirect()->back()->with('error', 'Cannot delete a category that has sub-categories. Please delete the sub-categories first.');
    }

    $category->delete();

    return redirect()->route('superadmin.categories.index')->with('success', 'Category deleted successfully.');
}

    
}