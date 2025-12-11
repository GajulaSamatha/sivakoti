{{-- The @extends and @section('page_title') are REMOVED here and handled in the PHP class --}}

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <style>
        /* STYLES FOR VISUAL HIERARCHY AND DRAG & DROP */
        .category-item {
            --level-0-color: #4e73df;
            --level-1-color: #1cc88a;
            --level-2-color: #36b9cc;
            --level-3-color: #f6c23e;
            --level-4-color: #e74a3b;
        }
        .category-item > .card {
            border-radius: .35rem;
            border: 1px solid #e3e6f0;
            transition: all 0.1s ease-in-out;
            margin-top: 5px;
            margin-bottom: 5px;
        }
        /* Color coding the left border based on hierarchy level */
        .category-item[data-level='0'] > .card { border-left: 5px solid var(--level-0-color) !important; }
        .category-item[data-level='1'] > .card { border-left: 5px solid var(--level-1-color) !important; }
        .category-item[data-level='2'] > .card { border-left: 5px solid var(--level-2-color) !important; }
        .category-item[data-level='3'] > .card { border-left: 5px solid var(--level-3-color) !important; }
        .category-item[data-level='4'] > .card { border-left: 5px solid var(--level-4-color) !important; }

        .handle { cursor: grab; opacity: 0.6; }
        .handle:hover { opacity: 1; }
        .sortable-ghost { 
            opacity: 0.2; 
            background: #f8f9fc;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            border: 1px dashed #d1d3e2;
        }

        trix-editor {
            min-height: 150px;
            border-radius: 0.375rem;
            border: 1px solid #d1d3e2;
        }
    </style>
@endpush

{{-- 🔑 THE SINGLE ROOT ELEMENT --}}
<div>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Categories – Drag & Drop 🏗️</h1>
            <button wire:click="openCreateModal" class="btn btn-success shadow-sm">
                <i class="fas fa-plus"></i> Add New Category
            </button>
        </div>

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Main Tree container (data-parent-id="null" indicates top level) --}}
        <div id="tree" class="children-container" data-parent-id="null">
            @forelse($categories as $category)
                <x-category-item 
                    :category="$category" 
                    :level="0" 
                    :allCategories="$allCategories" 
                    wire:key="category-{{ $category->id }}"
                />
            @empty
                <div class="alert alert-info text-center p-5 rounded">
                    No categories yet. Click "Add New Category" to start.
                </div>
            @endforelse
        </div>
    </div>


    {{-- MODAL EMBEDDED DIRECTLY --}}
    {{-- FIX: Removed aria-hidden="true" to resolve accessibility conflict with focus. --}}
    <div wire:ignore.self class="modal fade d-none" id="categoryModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form wire:submit.prevent="save">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $editId === 'new' ? 'Add New Category' : 'Edit Category' }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="closeModal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Title (using title_form to avoid conflict with $this->title layout property) --}}
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" wire:model.defer="title_form" class="form-control @error('title_form') is-invalid @enderror" placeholder="e.g. Morning Pooja">
                            @error('title_form') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Description - TRIX EDITOR (uses wire:ignore and JS event listener) --}}
                        <div class="mb-3">
                            <label class="form-label">Description <small class="text-muted">(Rich Text Editor)</small></label>
                            <div wire:ignore>
                                <input id="trix-desc" type="hidden" wire:model="description">
                                <trix-editor input="trix-desc"></trix-editor>
                            </div>
                            @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        
                        {{-- Parent Category Field (Select dropdown) --}}
                        <div class="mb-3">
                            <label class="form-label">Parent Category</label>
                            <select wire:model.defer="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                                <option value="">-- No Parent (Top Level) --</option>
                                {{-- $allCategories is the flat tree from the component --}}
                                @foreach($allCategories as $categoryOption)
                                    <option value="{{ $categoryOption->id }}"
                                        {{ $editId == $categoryOption->id ? 'disabled' : '' }}>
                                        {{ str_repeat('— ', $categoryOption->depth) }}{{ $categoryOption->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select wire:model.defer="status" class="form-select">
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>

                        @if($editId !== 'new')
                            <div class="alert alert-info small">
                                <i class="fas fa-info-circle"></i>
                                <strong>Parent & Order:</strong> Use drag & drop on the list to change nesting and order.
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="closeModal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            {{ $editId === 'new' ? 'Create Category' : 'Save Changes' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</div> 
{{-- 🔑 END OF THE SINGLE ROOT ELEMENT --}}

@push('scripts')
    <script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            const livewire = @this;
            const categoryModalElement = document.getElementById('categoryModal');
            
            // --- Modal & Trix Handling (Most Robust Version) ---
            
            Livewire.on('open-category-modal', () => {
                // 1. Trix Content Load
                const trixEditor = document.querySelector('trix-editor');
                if (trixEditor && trixEditor.editor) {
                    trixEditor.editor.loadHTML(livewire.get('description') || '');
                }
                
                // 2. Remove d-none class for immediate display
                categoryModalElement.classList.remove('d-none');
                
                // 3. Manually call the Bootstrap modal function via element
                const modal = bootstrap.Modal.getOrCreateInstance(categoryModalElement);
                modal.show();
            });

            Livewire.on('close-category-modal', () => {
                const modal = bootstrap.Modal.getInstance(categoryModalElement);
                if (modal) {
                    // Bootstrap hide handles focus and aria-hidden cleanup
                    modal.hide();
                }
            });

            // This ensures the d-none class is added back and the Livewire method is called
            categoryModalElement.addEventListener('hidden.bs.modal', function () {
                categoryModalElement.classList.add('d-none');
                livewire.call('closeModal');
            });

            // Manually sync Trix content back to Livewire on change from the specific editor
            const trixEditor = document.querySelector('trix-editor');
            trixEditor.addEventListener('trix-change', e => {
                livewire.set('description', e.target.value);
            });


            // --- SortableJS / Drag & Drop Handling ---

            const updateHierarchy = () => {
                const data = [];
                const collect = (container) => {
                    container.querySelectorAll(':scope > div.category-item').forEach((el, i) => {
                        const parentContainer = el.closest('.children-container');
                        data.push({
                            id: parseInt(el.dataset.id),
                            parent_id: parentContainer.dataset.parentId === 'null' ? null : parseInt(parentContainer.dataset.parentId),
                            order: i + 1
                        });
                        const childCont = el.querySelector('.children-container');
                        if (childCont) {
                            collect(childCont);
                        }
                    });
                };
                collect(document.getElementById('tree'));
                livewire.call('updateHierarchy', data);
            };


            const initSortable = () => {
                const containers = document.querySelectorAll('.children-container');
                containers.forEach(container => {
                    if (container.sortable) {
                         container.sortable.destroy();
                    }
                    container.sortable = new Sortable(container, {
                        group: 'nested', 
                        animation: 180,
                        handle: '.handle', 
                        draggable: '.category-item', 
                        ghostClass: 'sortable-ghost',
                        fallbackTolerance: 3,
                        swapThreshold: 0.65, 
                        onEnd: (evt) => {
                            updateHierarchy();
                            initSortable(); 
                        }
                    });
                });
            };

            // Initialize on component load
            initSortable();

            // Hook into Livewire updates (for new categories/edits)
            livewire.on('categories-updated', () => {
                initSortable();
            });
        });
    </script>
@endpush