@props(['category', 'level' => 0, 'allCategories' => []])

@php
    $marginLeft = $level * 20;
@endphp

<div class="category-item" data-id="{{ $category->id }}" data-level="{{ $level }}" style="margin-left: {{ $marginLeft }}px;">
    <div class="card shadow-sm bg-white">
        <div class="card-body d-flex align-items-center py-3">
            <!-- Drag handle -->
            <i class="fas fa-grip-vertical text-muted me-4 handle fs-4"></i>
            <div class="flex-grow-1">
                <strong class="fs-5">{{ $category->title }}</strong>
                @php
                    $statusClass = $category->status === 'published' ? 'bg-success' : 'bg-secondary';
                @endphp
                <span class="badge ms-2 {{ $statusClass }}">{{ ucfirst($category->status) }}</span>
            </div>
            <div class="d-flex gap-2">
                <!-- Edit button dispatches event for Livewire to listen to -->
                <button wire:click="$dispatch('edit', { id: {{ $category->id }} })" class="btn btn-sm btn-primary">Edit</button>
                <!-- Delete button with confirmation -->
                <button x-on:click="if(confirm('Delete this category and all its children?')){ $wire.dispatch('delete', { id: {{ $category->id }} }) }" class="btn btn-sm btn-danger">Delete</button>
            </div>
        </div>
    </div>

    <div class="children-container" data-parent-id="{{ $category->id }}">
        @foreach ($category->children as $child)
            <x-category-item :category="$child" :level="$level + 1" :allCategories="$allCategories" />
        @endforeach
    </div>
</div>