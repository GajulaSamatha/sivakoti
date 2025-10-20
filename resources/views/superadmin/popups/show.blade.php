{{-- resources/views/superadmin/popups/form.blade.php --}}

@php
    // Determine if we are creating or editing
    $isEdit = isset($popup);
    $popup = $popup ?? new \App\Models\Popup();
@endphp

<div class="mb-3">
    <label for="title" class="form-label">Title</label>
    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $popup->title) }}" required>
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="content" class="form-label">Content (Visual Editor Area)</label>
    {{-- In a real app, you would integrate a rich text editor like TinyMCE or CKEditor here --}}
    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="5">{{ old('content', $popup->content) }}</textarea>
    @error('content')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="category_id" class="form-label">Category</label>
    <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
        <option value="">Select Category (Optional)</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" 
                {{ old('category_id', $popup->category_id) == $category->id ? 'selected' : '' }}>
                {{ $category->title }}
            </option>
        @endforeach
    </select>
    @error('category_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="image" class="form-label">Featured Image</label>
    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
    @error('image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    
    @if ($isEdit && $popup->image)
        <div class="mt-2">
            <p>Current Image:</p>
            <img src="{{ asset('storage/' . $popup->image) }}" alt="Current Popup Image" style="max-width: 150px; height: auto;">
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1">
                <label class="form-check-label" for="remove_image">
                    Remove current image
                </label>
            </div>
        </div>
    @endif
</div>

<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="is_enabled" name="is_enabled" value="1" 
        {{ old('is_enabled', $popup->is_enabled ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_enabled">
        Enable / Active Popup
    </label>
</div>

<button type="submit" class="btn btn-success mt-3">{{ $buttonText }}</button>
<a href="{{ route('superadmin.popups.index') }}" class="btn btn-secondary mt-3">Cancel</a>