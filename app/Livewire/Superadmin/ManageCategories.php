<?php

namespace App\Livewire\Superadmin;

use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // <-- CRITICAL: Import DB facade
use Illuminate\Support\Facades\Log; // <-- CRITICAL: Import Log facade for error handling

class ManageCategories extends Component
{
    // Public properties mapped to form fields
    public $parent_id = null;
    public $parentOptions = [];
    public $categories;
    public $editId = null;
    public $title = '';
    public $description = '';
    public $start_date = '';
    public $end_date = '';
    public $status = 'draft';

    // Validation rules
    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'status' => 'required|in:draft,published',
        'parent_id' => 'nullable|exists:categories,id',
    ];

    public function mount()
    {
        $this->loadCategories();
        // Fetch a flat list of categories for the dropdown options
        $this->parentOptions = Category::pluck('title', 'id')->toArray(); 
    }
    
    // -----------------------------------------------------------
    // CORE FUNCTIONS
    // -----------------------------------------------------------

    public function loadCategories()
    {
        // 🔑 FIX: Ensure only one with('children') call and whereNull is correct
        $this->categories = Category::whereNull('parent_id')
            ->with('children')
            ->orderBy('order_column')
            ->get();
    }

    public function create()
    {
        $this->validate(); // Uses the protected $rules

        $slug = Str::slug($this->title);

        Category::create([
            'title'        => $this->title,
            'slug'         => $slug,
            'description'  => $this->description,
            'start_date'   => $this->start_date,
            'end_date'     => $this->end_date,
            'status'       => $this->status,
            'order_column' => Category::max('order_column') + 1,
            'is_active'    => is_null($this->end_date) || $this->end_date >= now()->format('Y-m-d'),
            'parent_id'    => $this->parent_id ?: null,
        ]);

        $this->resetForm();
        $this->loadCategories();
        $this->dispatch('category-updated'); // Event to close modal & refresh SortableJS
    }

    public function edit($id)
    {
        $cat = Category::findOrFail($id);
        $this->editId = $id;
        $this->title = $cat->title;
        $this->description = $cat->description;
        // Format date objects for HTML input (YYYY-MM-DD)
        $this->start_date = $cat->start_date?->format('Y-m-d'); 
        $this->end_date = $cat->end_date?->format('Y-m-d');
        $this->status = $cat->status;
        $this->parent_id = $cat->parent_id; 
        
        $this->dispatch('open-category-modal');
    }

    public function save()
    {
        $this->validate(); // Uses the protected $rules

        Category::findOrFail($this->editId)->update([
            'title'       => $this->title,
            'description' => $this->description,
            'start_date'  => $this->start_date,
            'end_date'    => $this->end_date,
            'status'      => $this->status,
            'parent_id'   => $this->parent_id ?: null, // Update parent_id from form
            'is_active'   => is_null($this->end_date) || $this->end_date >= now()->format('Y-m-d'),
        ]);

        $this->resetForm();
        $this->loadCategories();
        $this->dispatch('category-updated'); // Event to close modal & refresh SortableJS
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        $this->loadCategories();
        $this->dispatch('category-order-updated'); // Refresh D&D tree after delete
    }

    /**
     * Handles the drag-and-drop hierarchy and order update.
     * This is the function that resolves the UI refresh issue.
     */
    public function updateOrder($items)
    {
        DB::beginTransaction();
        try {
            // The $items array is passed from the JavaScript, which includes nesting.
            
            foreach ($items as $index => $item) {
                // 1. Update the current (parent) item's order and set parent_id to NULL
                Category::where('id', $item['value'])->update([
                    'parent_id' => null, // 🔑 FIX: Explicitly set top-level items to NULL
                    'order_column' => $index + 1
                ]);

                // 2. Check for and process children
                if (isset($item['children']) && is_array($item['children'])) {
                    
                    // Loop through all children of the current item
                    foreach ($item['children'] as $childIndex => $childItem) {
                        
                        Category::where('id', $childItem['value'])->update([
                            'parent_id' => $item['value'], // Sets parent_id to the new parent
                            'order_column' => $childIndex + 1
                        ]);
                    }
                }
            }
            
            DB::commit();

            // 1. RELOAD CATEGORIES: Refresh the Livewire data model with the new hierarchy.
            $this->loadCategories(); 
            
            // 2. DISPATCH: Signal the browser to re-initialize SortableJS on the new HTML.
            $this->dispatch('category-order-updated');
            
            $this->dispatch('success-alert', message: 'Category order updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Category Order Update Failed: ' . $e->getMessage());
            $this->dispatch('error-alert', message: 'Failed to update category order.');
        }
    }

    public function resetForm()
    {
        $this->editId = null;
        $this->title = '';
        $this->description = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->status = 'draft';
        $this->parent_id = null; // Reset parent_id
    }

    public function render()
    {
        return view('livewire.superadmin.manage-categories')
        ->extends('layouts.superadmin_layouts.superadmin_base')
        ->section('content');
    }
}