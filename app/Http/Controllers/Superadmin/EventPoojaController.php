<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventPooja;
use Illuminate\Support\Str; // Import the Str facade for slug generation
use Illuminate\Support\Facades\Storage; // Import the Storage facade for file handling

class EventPoojaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $eventsPoojas = EventPooja::latest()->get();

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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    
}
