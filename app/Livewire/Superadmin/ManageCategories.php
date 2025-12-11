<?php

namespace App\Livewire\Superadmin;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ManageCategories extends Component
{
    use WithFileUploads;

    // Data Properties
    public $categories = []; // The recursive collection for the tree view
    public $allCategories = []; // The flat collection for the parent dropdown

    // Form Properties
    public $editId = 'new'; // ID of the category being edited, or 'new'
    public $title_form = '';
    public $description = '';
    public $parent_id = null;
    public $status = 'published';

    protected function rules()
    {
        return [
            'title_form' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => [
                'nullable', 
                'integer', 
                Rule::exists('categories', 'id')->whereNull('deleted_at'),
                // Prevent selecting the category itself as its parent
                Rule::notIn([$this->editId === 'new' ? null : $this->editId]),
            ],
            'status' => 'required|in:published,draft',
        ];
    }

    // Listens for events dispatched from the view (e.g., the delete button onclick)
    protected $listeners = [
        'edit', 
        'delete', 
    ];

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        // 1. Get top-level categories, including their children recursively
        $this->categories = Category::whereNull('parent_id')
            ->with('children')
            ->orderBy('order_column')
            ->get();
        
        // 2. Custom logic to flatten the recursive tree for the Parent Category dropdown
        $this->allCategories = $this->flattenTree($this->categories);
    }

    /**
     * Recursively flattens the nested collection and assigns a 'depth' attribute.
     * * @param \Illuminate\Support\Collection $categories
     * @param int $depth
     * @return \Illuminate\Support\Collection
     */
    protected function flattenTree($categories, $depth = 0)
    {
        $flat = collect();

        foreach ($categories as $category) {
            // Clone the category object to safely add a 'depth' attribute without mutating the original nested object
            $item = clone $category;
            $item->depth = $depth;
            
            // Add the item to the flat collection
            $flat->push($item);
            
            // Recursively flatten children
            if ($category->children->isNotEmpty()) {
                $flat = $flat->merge($this->flattenTree($category->children, $depth + 1));
            }
        }

        return $flat;
    }

    public function openCreateModal()
    {
        $this->reset(['editId', 'title_form', 'description', 'parent_id', 'status']);
        $this->editId = 'new';
        $this->dispatch('open-category-modal');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->editId = $category->id;
        $this->title_form = $category->title;
        $this->description = $category->description;
        $this->parent_id = $category->parent_id;
        $this->status = $category->status;

        $this->dispatch('open-category-modal');
    }

    public function closeModal()
    {
        $this->resetValidation();
        $this->dispatch('close-category-modal');
    }

    public function save()
    {
        $this->validate();
        
        // Use a transaction to ensure atomic operation
        DB::transaction(function () {
            $category = ($this->editId === 'new')
                ? new Category()
                : Category::findOrFail($this->editId);

            $category->title = $this->title_form;
            $category->description = $this->description;
            $category->status = $this->status;
            $category->parent_id = $this->parent_id; // Let nested-set handle the rest

            $category->save();
        });

        session()->flash('success', $this->editId === 'new' ? 'Category created successfully!' : 'Category updated successfully!');
        $this->closeModal();
        $this->loadCategories(); // Reload data to update the tree view
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);
        // Cascading delete is recommended in the model or database migration for safety
        $category->delete(); 
        session()->flash('success', 'Category and all its nested children deleted successfully.');
        $this->loadCategories();
    }

    // Method called by the SortableJS front-end
    public function updateHierarchy($data)
    {
        DB::transaction(function () use ($data) {
            foreach ($data as $item) {
                Category::where('id', $item['id'])->update([
                    'parent_id' => $item['parent_id'],
                    'order_column' => $item['order'],
                ]);
            }
        });
        
        $this->loadCategories();
        session()->flash('success', 'Category order and hierarchy updated!');
    }

    public function render()
    {
        // Use the layout() method to specify the layout and pass data to it.
        // This correctly populates the @yield('page_title') in the base layout.
        return view('livewire.superadmin.manage-categories')
            ->layout('layouts.superadmin_layouts.superadmin_base', ['page_title' => 'Manage Categories']);
    }
}