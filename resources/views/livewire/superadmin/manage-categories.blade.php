@section('page_title', 'Manage Categories')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Categories – Drag & Drop</h1>
        
        {{-- FIX: Remove Bootstrap toggle attributes. Dispatch event to open modal after setting editId. --}}
        <button wire:click="$set('editId', 'new'); $dispatch('open-category-modal')"
                class="btn btn-success shadow-sm">
            <i class="fas fa-plus"></i> Add New Category
        </button>
    </div>

    {{-- FIX: Re-add wire:ignore to the D&D container. Stops Livewire from destroying SortableJS instances. --}}
    <div wire:ignore> 
        <div id="tree" class="list-group mt-4">
            @forelse($categories as $cat)
                {{-- Main Category Card --}}
                <div class="card mb-3 shadow-sm {{ $cat->end_date && $cat->end_date < now() ? 'opacity-75 border-danger' : 'border-primary' }}"
                     data-id="{{ $cat->id }}">

                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-grip-vertical me-3 text-secondary"></i>
                            
                            <h5 class="card-title mb-0">
                                {{ $cat->title }}

                                @if($cat->end_date && $cat->end_date < now())
                                    <span class="badge bg-danger ms-2">EXPIRED</span>
                                @endif

                                <span class="badge ms-2 
                                    {{ $cat->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($cat->status) }}
                                </span>
                            </h5>
                        </div>

                        <div class="d-flex gap-2">
                            {{-- FIX: Rely only on wire:click --}}
                            <button wire:click="edit({{ $cat->id }})"
                                    class="btn btn-sm btn-primary">
                                Edit
                            </button>
                            <button wire:click="delete({{ $cat->id }})"
                                    onclick="return confirm('Are you sure you want to delete this category and all its children?')"
                                    class="btn btn-sm btn-danger">
                                Delete
                            </button>
                        </div>
                    </div>

                    {{-- Children (sub-categories) --}}
                    {{-- The container must always be present for drop targets --}}
                    <div class="list-group list-group-flush ms-5 mb-2 children-list" 
                         data-parent-id="{{ $cat->id }}"> 
                        
                        @foreach($cat->children as $child)
                            <div class="list-group-item bg-light border-start border-4 border-info d-flex justify-content-between align-items-center"
                                 data-id="{{ $child->id }}">
                                
                                <div class="d-flex align-items-center child-handle">
                                    <i class="fas fa-grip-lines-vertical me-2 text-muted"></i> 
                                    <i class="fas fa-angle-right me-2 text-info"></i>
                                    {{ $child->title }}
                                </div>
                                
                                <div class="d-flex gap-2">
                                    {{-- FIX: Rely only on wire:click --}}
                                    <button wire:click="edit({{ $child->id }})"
                                            class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </button>
                                    <button wire:click="delete({{ $child->id }})"
                                            onclick="return confirm('Are you sure you want to delete this category?')"
                                            class="btn btn-sm btn-outline-danger">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center" role="alert">
                    No categories yet. Click "+ Add New Category" to create one.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Include the modal structure (must be outside wire:ignore) --}}
    @if($editId || $editId === 'new')
        @include('livewire.superadmin.category-modal')
    @endif
    
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        // 1. Define the Initialization Function
        function initializeSortable() {
            const tree = document.getElementById('tree');
            if (!tree) return;
            
            // 1. Initialize PARENT LIST (#tree)
            if (tree.sortable) {
                tree.sortable.destroy(); // Destroy previous instance
            }
            tree.sortable = Sortable.create(tree, {
                animation: 200,
                handle: '.card-body',
                group: 'nested',
                onEnd: sendUpdatedOrder
            });

            // 2. Initialize NESTED CHILD LISTS (.children-list)
            document.querySelectorAll('.children-list').forEach(list => {
                if (list.sortable) {
                    list.sortable.destroy(); // Destroy previous instance
                }
                list.sortable = Sortable.create(list, {
                    animation: 150,
                    handle: '.child-handle',
                    group: {
                        name: 'nested',
                        pull: true,
                        put: true
                    },
                    onEnd: sendUpdatedOrder
                });
            });
        }

        // 3. CREATE THE DATA SENDER FUNCTION
        function sendUpdatedOrder() {
            let items = [];
            document.querySelectorAll('#tree > div[data-id]').forEach((el, index) => {
                let obj = {
                    value: el.dataset.id,
                    order: index + 1
                };

                let children = el.querySelectorAll(':scope > .children-list > div[data-id]'); 
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
        
        // 4. LIVEWIRE HOOKS: Ensure initialization and modal opening happen correctly.
        
        document.addEventListener('livewire:initialized', () => {
            initializeSortable();
            
            // 🔑 FIX: Manual listener to open the Bootstrap modal after Livewire updates properties.
            Livewire.on('open-category-modal', () => {
                const modalElement = document.getElementById('categoryModal');
                if (modalElement) {
                    // Check for existing instance to prevent errors
                    let modal = bootstrap.Modal.getInstance(modalElement);
                    if (!modal) {
                         modal = new bootstrap.Modal(modalElement);
                    }
                    modal.show();
                }
            });
        });
        
        // Re-initialize Sortable after Livewire component updates the DOM.
        document.addEventListener('livewire:navigated', () => {
            setTimeout(initializeSortable, 50); 
        });

        // Use custom event for creation/update success
        Livewire.on('category-updated', () => {
             // Hide the modal after save/update
             const modalElement = document.getElementById('categoryModal');
             if (modalElement) {
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                }
             }
             // Re-initialize Sortable after the list updates
             setTimeout(initializeSortable, 50); 
        });
    </script>
</div>