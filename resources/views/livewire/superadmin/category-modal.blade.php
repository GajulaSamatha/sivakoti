{{-- resources/views/livewire/superadmin/category-modal.blade.php --}}

{{-- CRITICAL FIX: wire:ignore.self prevents Livewire from re-rendering the modal's DOM element while it's open, which can cause flicker or prevent it from closing properly. --}}
<div wire:ignore.self class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            {{-- CRITICAL FIX: The form should call a method that also closes the modal on success (via $dispatch). --}}
            <form wire:submit.prevent="save"> 
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $editId === 'new' ? 'Add New Category' : 'Edit Category' }}
                    </h5>
                    {{-- CRITICAL FIX: Added data-bs-dismiss to close the visual element --}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="closeModal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    {{-- Title --}}
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        {{-- Changed to .defer for less network traffic while typing --}}
                        <input type="text" wire:model.defer="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Morning Pooja">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label class="form-label">Description <small class="text-muted">(optional)</small></label>
                        <textarea wire:model.defer="description" rows="3" class="form-control" placeholder="Brief description..."></textarea>
                    </div>
                    
                    {{-- CRITICAL ADDITION: Parent Category Field for creation/re-parenting in the form --}}
                    <div class="mb-3">
                        <label class="form-label">Parent Category</label>
                        {{-- The 'allCategories' property must be populated in the PHP component --}}
                        <select wire:model.defer="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                            <option value="">-- No Parent (Top Level) --</option>
                            @foreach($allCategories as $categoryOption)
                                {{-- We must prevent an item from being its own parent, which is handled in the PHP component's render() method filter. --}}
                                <option value="{{ $categoryOption->id }}">{{ $categoryOption->title }}</option>
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
                    {{-- CRITICAL FIX: Added data-bs-dismiss and call closeModal for clean state reset --}}
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="closeModal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ $editId === 'new' ? 'Create Category' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>