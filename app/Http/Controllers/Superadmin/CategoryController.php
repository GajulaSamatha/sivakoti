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

    // app/Http/Controllers/Superadmin/CategoryController.php

// app/Http/Controllers/Superadmin/CategoryController.php

// app/Http/Controllers/Superadmin/CategoryController.php

public function index(Request $request)
{
    // Fetch all top-level categories to populate the filter dropdown
    $parentCategories = Category::whereNull('parent_id')->orderBy('order')->get();

    // Start building the query
    $query = Category::with('parent');

    // --- 1. APPLY SEARCH FILTER (by Title) ---
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }
    
    // --- 2. APPLY CATEGORY FILTER (THE FINAL FIX) ---
    $parentId = $request->input('parent_id'); 

    // FIX: Check if parentId is empty string OR null (the default/reset state)
    if ($parentId === '' || $parentId === null) {
        // CASE 1: Top-Level Categories Only (Filter for NULL parent_id)
        $query->whereNull('parent_id');
    } elseif (is_numeric($parentId)) {
        // CASE 2: Specific Parent Category (Filter by the actual ID)
        $query->where('parent_id', $parentId);
    } 

    //filtering with draft and published
    if ($request->filled('status') && $request->status != 'all') {
        $query->where('status', $request->status);
    }

    // CASE 3: If $parentId is 'all', we skip this block entirely, showing all categories.

    // --- 3. APPLY SORTING ---
    $sort = $request->input('sort', 'order_asc'); 

    if ($sort == 'date_asc') {
        $query->orderBy('created_at', 'asc');
    } elseif ($sort == 'date_desc') {
        $query->orderBy('created_at', 'desc');
    } else {
        $query->orderBy('order', 'asc');
    }

    $categories = $query->get();

    return view('superadmin.categories.superadmin_categories_index', compact('categories', 'parentCategories', 'request'));
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
        $status = 'published';
        //adding draft if draft button clicked
            if($request->has('draft')){
                $status = 'draft';
            }

        // Create the new category
        Category::create([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => $slug,
            'parent_id' => $request->parent_id,
            'status'=>$status
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

// app/Http/Controllers/Superadmin/CategoryController.php

    public function update(Request $request, Category $category)
    {
        // 1. UPDATED VALIDATION RULES
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            // New: description is nullable
            'description' => 'nullable|string', 
            
            'parent_id' => [
                'nullable', 
                'exists:categories,id', 
                // Crucial: A category cannot be its own parent.
                'not_in:' . $category->id
            ],
            'order' => 'nullable|integer',
            
            // New: status must be either draft or published
            'status' => 'required|in:draft,published', 
            
            // New: is_enabled must be 1 or 0 (a boolean value)
            'is_enabled' => 'required|boolean', 
        ]);
        
        // 2. SLUG REGENERATION
        // Check if the title has been changed
        if ($request->title != $category->title) {
            $validatedData['slug'] = Str::slug($request->title);
        }
        // Note: If the title is the same, we simply don't overwrite the existing slug.

        // 3. FINAL UPDATE CALL
        $category->update($validatedData);

        return redirect()->route('superadmin.categories.index')->with('success', "Category '{$category->title}' updated successfully!");
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

    //toggle enable/disable
    public function toggleEnabled(Category $category){
        $category->is_enabled = !$category->is_enabled;
        $category->save();

        // Prepare a dynamic status message
    $status = $category->is_enabled ? 'Enabled' : 'Disabled';

    return redirect()->back()->with('success', "Category '{$category->title}' has been {$status} successfully.");
    }
    
}