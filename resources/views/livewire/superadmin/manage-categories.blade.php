<div class="p-6 max-w-7xl mx-auto">

    <!-- Header + Add Button -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Categories – Drag & Drop</h1>
        <button wire:click="$set('editId', 'new')"
                class="ml-auto bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-3 rounded-lg shadow">
            + Add New Category
        </button>
    </div>

    <!-- Tree -->
    <div wire:ignore>
        <div id="tree" class="space-y-4">
            @forelse($categories as $cat)
                <div class="bg-white rounded-lg shadow-md border p-6 {{ $cat->end_date && $cat->end_date < now() ? 'opacity-60 border-red-300' : 'border-gray-200' }}"
                     data-id="{{ $cat->id }}">

                    <div class="flex justify-between items-center">
                        <div class="text-lg font-semibold">
                            {{ $cat->title }}

                            @if($cat->end_date && $cat->end_date < now())
                                <span class="ml-3 text-red-600 font-bold text-sm">[EXPIRED]</span>
                            @endif

                            <span class="ml-3 inline-block px-3 py-1 text-xs font-medium rounded-full
                                {{ $cat->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                {{ ucfirst($cat->status) }}
                            </span>
                        </div>

                        <div class="flex gap-2">
                            <button wire:click="edit({{ $cat->id }})"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                                Edit
                            </button>
                            <button wire:click="delete({{ $cat->id }})"
                                    onclick="return confirm('Delete this category and all its children?')"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">
                                Delete
                            </button>
                        </div>
                    </div>

                    <!-- Children (sub-categories) -->
                    @if($cat->children->count())
                        <div class="ml-12 mt-5 space-y-3">
                            @foreach($cat->children as $child)
                                <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-blue-500"
                                     data-id="{{ $child->id }}">
                                    {{ $child->title }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    No categories yet. Click "+ Add New Category" to create one.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Create / Edit Modal -->
    @if($editId)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-2xl p-8 w-full max-w-2xl">

                <h2 class="text-2xl font-bold mb-6">
                    {{ $editId === 'new' ? 'Add New Category' : 'Edit Category' }}
                </h2>

                <div class="space-y-5">
                    <input type="text"
                           wire:model.live="title"
                           placeholder="Category Title *"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">

                    <input type="date"
                           wire:model.live="start_date"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3">

                    <input type="date"
                           wire:model.live="end_date"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3">

                    <select wire:model.live="status"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>

                    <textarea wire:model.live="description"
                              rows="5"
                              placeholder="Description (optional)"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3"></textarea>
                </div>

                <div class="flex justify-end gap-4 mt-8">
                    @if($editId === 'new')
                        <button wire:click="create"
                                class="bg-green-600 hover:bg-green-700 text-white font-medium px-8 py-3 rounded-lg">
                            Create Category
                        </button>
                    @else
                        <button wire:click="save"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-8 py-3 rounded-lg">
                            Save Changes
                        </button>
                    @endif

                    <button wire:click="$set('editId', null)"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-medium px-8 py-3 rounded-lg">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            Sortable.create(document.getElementById('tree'), {
                animation: 200,
                handle: '.bg-white',
                onEnd: function () {
                    let items = [];
                    document.querySelectorAll('#tree > div[data-id]').forEach((el, index) => {
                        let obj = {
                            value: el.dataset.id,
                            order: index + 1
                        };

                        let children = el.querySelectorAll(':scope > div > div[data-id]');
                        if (children.length) {
                            obj.children = [];
                            children.forEach((child, i) => {
                                obj.children.push({
                                    value: child.dataset.id,
                                    order: i + 1
                                });
                            });
                        }
                        items.push(obj);
                    });

                    @this.call('updateOrder', items);
                }
            });
        });
    </script>
</div>