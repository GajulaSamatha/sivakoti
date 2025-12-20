<li class="border-l-2 border-gray-200 pl-4">
    <div class="flex justify-between items-center py-2 bg-gray-50 hover:bg-gray-100 rounded px-2 transition">
        <div class="flex items-center">
            <span class="font-medium text-gray-800">{{ $category->title }}</span>
            <span class="ml-2 text-xs px-2 py-0.5 rounded-full {{ $category->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                {{ ucfirst($category->status) }}
            </span>
        </div>
        <div class="flex space-x-2">
            <button wire:click="edit({{ $category->id }})" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Edit</button>
            <button wire:click="delete({{ $category->id }})" 
                    onclick="return confirm('Are you sure you want to delete this category?')"
                    class="text-sm text-red-600 hover:text-red-800 font-medium">Delete</button>
        </div>
    </div>
    @if($category->children->isNotEmpty())
        <ul class="ml-4 mt-2 space-y-2">
            @foreach($category->children as $child)
                @include('livewire.category-item', ['category' => $child])
            @endforeach
        </ul>
    @endif
</li>
