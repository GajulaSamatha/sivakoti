<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Popup;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PopupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all popups with their categories and paginate them
        $popups = Popup::with('category')->latest()->paginate(15);
        
        return view('superadmin.popups.index', compact('popups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get categories to populate the dropdown
        $categories = Category::orderBy('title')->get();
        return view('superadmin.popups.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'category_id' => 'nullable|exists:categories,id',
            // Correct rule for checkbox: accepts 'on', '1', or 'true' if checked, otherwise it's missing (nullable)
            'is_enabled' => 'nullable|in:on,1,true', 
        ]);

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('uploads/popups', 'public');
        }
        
        // Create the Database Record
        Popup::create([
            'title' => $validatedData['title'],
            'content' => $validatedData['content'] ?? null,
            // Passes the path if set by upload, otherwise null
            'image' => $validatedData['image'] ?? null, 
            'category_id' => $validatedData['category_id'] ?? null,
            // **ROBUST BOOLEAN**: Directly converts input to true/false (0/1) for database
            'is_enabled' => $request->boolean('is_enabled'), 
        ]);

        return redirect()->route('superadmin.popups.index')
                         ->with('success', 'Popup created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Popup $popup)
    {
        // Typically a simple read-only view, but you might redirect to edit
        return view('superadmin.popups.show', compact('popup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Popup $popup)
    {
        // Get categories to populate the dropdown
        $categories = Category::orderBy('title')->get();
        return view('superadmin.popups.edit', compact('popup', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Popup $popup)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'nullable|exists:categories,id',
            // Correct rule for checkbox input
            'is_enabled' => 'nullable|in:on,1,true', 
            // Optional field from the edit form to remove image
            'remove_image' => 'nullable|boolean', 
        ]);
        
        // Handle Image Update
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($popup->image) {
                Storage::disk('public')->delete($popup->image);
            }
            // Store new image and set path
            $validatedData['image'] = $request->file('image')->store('uploads/popups', 'public');
        } elseif ($request->boolean('remove_image')) {
            // Handle case where user checks a box to explicitly remove the image
            if ($popup->image) {
                Storage::disk('public')->delete($popup->image);
            }
            $validatedData['image'] = null;
        } else {
            // No new file and no remove request: keep the existing image path
            $validatedData['image'] = $popup->image;
        }

        // Update the record
        $popup->update([
            'title' => $validatedData['title'],
            'content' => $validatedData['content'] ?? null,
            'category_id' => $validatedData['category_id'] ?? null,
            'image' => $validatedData['image'], 
            // **ROBUST BOOLEAN**: Direct conversion
            'is_enabled' => $request->boolean('is_enabled'), 
        ]);

        return redirect()->route('superadmin.popups.index')
                         ->with('success', 'Popup updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Popup $popup)
    {
        // Delete associated image before deleting the record
        if ($popup->image) {
            Storage::disk('public')->delete($popup->image);
        }
        
        $popup->delete();

        return redirect()->route('superadmin.popups.index')
                         ->with('success', 'Popup deleted successfully.');
    }
}