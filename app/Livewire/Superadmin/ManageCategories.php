<?php

namespace App\Livewire\Superadmin;

use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Str;
use Carbon\Carbon; // Ensure Carbon is imported for date handling

class ManageCategories extends Component
{
    public $parent_id = null;
    public $parentOptions = [];
    public $categories;
    public $editId = null;
    public $title = '';
    public $description = '';
    public $start_date = '';
    public $end_date = '';
    public $status = 'draft';

    public function mount()
    {
        $this->loadCategories();
        // Fetch a flat list of categories for the dropdown options
        $this->parentOptions = Category::pluck('title', 'id')->toArray(); 
    }
    

    public function loadCategories()
    {
        $this->categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('order_column')
            ->get();
    }

    public function create()
    {
        $this->validate([
            'title' => 'required|string|max:255',
        ]);

        $slug = Str::slug($this->title);

        Category::create([
            'title'        => $this->title,
            'slug'         => $slug,
            'description'  => $this->description,
            'start_date'   => $this->start_date,
            'end_date'     => $this->end_date,
            'status'       => $this->status,
            'order_column' => Category::max('order_column') + 1,
            'is_active'    => true,
            'parent_id'    => $this->parent_id ?: null, // Use $parent_id from form
        ]);

        $this->resetForm();
        $this->loadCategories();
    }

    public function edit($id)
    {
        $cat = Category::findOrFail($id);
        $this->editId = $id;
        $this->title = $cat->title;
        $this->description = $cat->description;
        $this->start_date = $cat->start_date?->format('Y-m-d');
        $this->end_date = $cat->end_date?->format('Y-m-d');
        $this->status = $cat->status;
        $this->parent_id = $cat->parent_id; 
        $this->editId = $id;
        $this->dispatch('open-category-modal');
    }

    public function save()
    {
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
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        $this->loadCategories();
    }

    public function updateOrder($items)
    {
        // The $items array is passed from the JavaScript, which includes nesting.
        
        foreach ($items as $index => $item) {
            // 1. Update the current (parent) item's order and set parent_id to NULL (as it's top-level)
            Category::where('id', $item['value'])->update([
                'parent_id' => null, // Explicitly set top-level items to NULL
                'order_column' => $index + 1 // Use the array index + 1 for sequence
            ]);

            // 2. Check for and process children
            if (isset($item['children']) && is_array($item['children'])) {
                
                // Loop through all children of the current item
                foreach ($item['children'] as $childIndex => $childItem) {
                    
                    // Update the child item
                    Category::where('id', $childItem['value'])->update([
                        'parent_id' => $item['value'], // This sets the parent_id to the parent's ID
                        'order_column' => $childIndex + 1
                    ]);
                }
            }
        }
        // After updating, refresh the categories to re-render the list correctly
        $this->loadCategories(); 
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
        ->section('content');;
    }
}