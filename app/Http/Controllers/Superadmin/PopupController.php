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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB max
            'category_id' => 'nullable|exists:categories,id',
            'is_enabled' => 'boolean',
        ]);

        // Handle Image Upload (Similar to Events)
        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('uploads/popups', 'public');
        }

        // Set the default values for booleans if not present in request
        $validatedData['is_enabled'] = $request->has('is_enabled');
        
        Popup::create($validatedData);

        return redirect()->route('superadmin.popups.index')
                         ->with('success', 'Popup created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Popup $popup)
    {
        // For popups, show is usually just a detailed view or not used. 
        // We'll redirect to edit for simplicity, or you can create a show view later.
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
            'is_enabled' => 'boolean',
        ]);
        
        // Handle Image Update and Deletion of Old Image
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($popup->image) {
                Storage::disk('public')->delete($popup->image);
            }
            $validatedData['image'] = $request->file('image')->store('uploads/popups', 'public');
        }

        // Handle case where admin checks the box to remove the image (optional feature)
        if ($request->input('remove_image')) {
            if ($popup->image) {
                Storage::disk('public')->delete($popup->image);
            }
            $validatedData['image'] = null;
        }

        // Set the boolean status
        $validatedData['is_enabled'] = $request->has('is_enabled');

        $popup->update($validatedData);

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