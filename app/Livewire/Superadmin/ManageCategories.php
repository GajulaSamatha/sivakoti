<?php

namespace App\Livewire\Superadmin;

use App\Models\Category;
use Livewire\Component;

class ManageCategories extends Component
{
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

        Category::create([
            'title'        => $this->title,
            'description'  => $this->description,
            'start_date'   => $this->start_date,
            'end_date'     => $this->end_date,
            'status'       => $this->status,
            'order_column' => Category::max('order_column') + 1,
            'is_active'    => true,
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
    }

    public function save()
    {
        Category::findOrFail($this->editId)->update([
            'title'       => $this->title,
            'description' => $this->description,
            'start_date'  => $this->start_date,
            'end_date'    => $this->end_date,
            'status'      => $this->status,
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
        foreach ($items as $item) {
            Category::where('id', $item['value'])->update([
                'parent_id'    => null,
                'order_column' => $item['order']
            ]);

            if (isset($item['children'])) {
                foreach ($item['children'] as $child) {
                    Category::where('id', $child['value'])->update([
                        'parent_id'    => $item['value'],
                        'order_column' => $child['order']
                    ]);
                }
            }
        }
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
    }

    public function render()
    {
        return view('livewire.superadmin.manage-categories');
    }
}