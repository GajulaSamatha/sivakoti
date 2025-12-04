{{-- This uses the standard Bootstrap 5 Modal structure --}}
<div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title">{{ $editId === 'new' ? 'Add New Category' : 'Edit Category' }}</h5>
                <button type="button" class="btn-close" wire:click="$set('editId', null)"></button>
            </div>

            <div class="modal-body">
                <form wire:submit="{{ $editId === 'new' ? 'create' : 'save' }}" id="categoryForm">
                    
                    {{-- Title Field --}}
                    <div class="mb-3">
                        <label for="title" class="form-label">Category Title *</label>
                        <input type="text" wire:model.live="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="Category Title">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Parent Category (Optional)</label>
                        <select wire:model.defer="parent_id" class="form-control" id="parent_id">
                            {{-- Option to select no parent --}}
                            <option value="">-- No Parent (Top Level) --</option> 
                            
                            {{-- Loop through the categories fetched from the component --}}
                            @foreach($parentOptions as $id => $title)
                                <option value="{{ $id }}">{{ $title }}</option>
                            @endforeach
                        </select>
                        @error('parent_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    {{-- Existing Title Field --}}
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" wire:model.defer="title" class="form-control" id="title" required>
                        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    {{-- Description Field --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Description (optional)</label>
                        <textarea wire:model.live="description" id="description" rows="3" class="form-control"></textarea>
                    </div>
                    
                    {{-- Start Date Field --}}
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" wire:model.live="start_date" id="start_date" class="form-control">
                    </div>

                    {{-- End Date Field --}}
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" wire:model.live="end_date" id="end_date" class="form-control">
                    </div>

                    {{-- Status Dropdown --}}
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select wire:model.live="status" id="status" class="form-select">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>

                </form>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="$set('editId', null)">Cancel</button>
                
                @if($editId === 'new')
                    <button type="submit" form="categoryForm" class="btn btn-success">
                        Create Category
                    </button>
                @else
                    <button type="submit" form="categoryForm" class="btn btn-primary">
                        Save Changes
                    </button>
                @endif
            </div>

        </div>
    </div>
</div>