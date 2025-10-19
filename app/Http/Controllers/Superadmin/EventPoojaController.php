<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventPooja;
use Illuminate\Support\Str; // Import the Str facade for slug generation
use Illuminate\Support\Facades\Storage; // Import the Storage facade for file handling\
use App\Models\Category;

class EventPoojaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eventsPoojas = EventPooja::latest()->paginate(1); 
    
        return view('superadmin.events_poojas.index', compact('eventsPoojas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
            $categories = Category::where('status', 'published')->get();

            return view('superadmin.events_poojas.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. VALIDATION
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:event_poojas,title',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date', // Ensures end date is after start date
            'location' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image rules (max 2MB)
            'status' => 'required|in:draft,published',
            'is_enabled' => 'required|boolean',
        ]);

        // 2. FILE UPLOAD (if image is present)
        if ($request->hasFile('image')) {
            // Store the file in 'public/events' and get the public path
            $path = $request->file('image')->store('public/events');

            // We only want the path relative to the storage (not the 'public/' prefix)
            $validatedData['image'] = Storage::url($path);
        }

        // 3. SLUG GENERATION
        $validatedData['slug'] = Str::slug($request->title);

        // 4. SAVE THE RECORD
        EventPooja::create($validatedData);

        return redirect()->route('superadmin.events_poojas.index')
            ->with('success', "New Event/Pooja '{$request->title}' created successfully!");
    }

    /**
     * Display the specified resource.
     */
    public function show(EventPooja $events_pooja)
    {
        return view('superadmin.events_poojas.show', compact('events_pooja'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EventPooja $events_pooja) // Using Route Model Binding
    {
        // Fetch all published categories for the dropdown
        $categories = Category::where('status', 'published')->get();

        // Pass the existing record and categories to the view
        return view('superadmin.events_poojas.edit', compact('events_pooja', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EventPooja $events_pooja)
    {
        // 1. VALIDATION (Must account for the unique slug/title)
        $validatedData = $request->validate([
            // The title/slug check must IGNORE the current record's ID
            'title' => 'required|string|max:255|unique:event_poojas,title,' . $events_pooja->id,
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'location' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // New image is optional
            'status' => 'required|in:draft,published',
            'is_enabled' => 'required|boolean',
        ]);

        // 2. SLUG GENERATION (Re-generate the slug)
        // We already handled uniqueness via the validation rule above for the title,
        // so we can use the same logic, but we can simplify here as well.
        // For simplicity and avoiding potential infinite loops in 'while' when using 'update', 
        // we'll rely on the validation's unique check above.
        $validatedData['slug'] = Str::slug($request->title);

        // 3. IMAGE HANDLING
        if ($request->hasFile('image')) {
            // A. Delete old image if it exists
            if ($events_pooja->image) {
                // We need to strip the /storage/ prefix to get the path inside the 'public' disk
                $oldPath = str_replace('/storage/', 'public/', $events_pooja->image);
                Storage::delete($oldPath);
            }

            // B. Upload new image
            $path = $request->file('image')->store('public/events');
            $validatedData['image'] = Storage::url($path);
        } 
        // C. Handle image deletion if user checks a "delete image" box (optional but good practice)
        // If you add a "delete_image" checkbox to your form, handle it here.

        // 4. UPDATE THE RECORD
        $events_pooja->update($validatedData);

        return redirect()->route('superadmin.events_poojas.index')
            ->with('success', "Event/Pooja '{$events_pooja->title}' updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventPooja $events_pooja)
    {
        // 1. Delete associated image (crucial for cleanup)
        if ($events_pooja->image) {
            // Strip /storage/ and replace with public/ to get the internal path
            $oldPath = str_replace('/storage/', 'public/', $events_pooja->image);
            Storage::delete($oldPath);
        }

        // 2. Delete the record itself
        $title = $events_pooja->title;
        $events_pooja->delete();

        return redirect()->route('superadmin.events_poojas.index')
            ->with('success', "Event/Pooja '{$title}' successfully deleted.");
    }

    
}
