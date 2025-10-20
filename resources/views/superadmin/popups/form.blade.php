<div>
    {{-- TITLE --}}
    <div class="form-group mb-3">
        <label for="title">Popup Title</label>
        <input type="text" 
               class="form-control @error('title') is-invalid @enderror" 
               id="title" 
               name="title" 
               value="{{ old('title', $popup->title ?? '') }}" 
               required>
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- CONTENT --}}
    <div class="form-group mb-3">
        <label for="content">Content</label>
        <textarea class="form-control @error('content') is-invalid @enderror" 
                  id="content" 
                  name="content" 
                  rows="5" 
                  required>{{ old('content', $popup->content ?? '') }}</textarea>
        @error('content')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- CATEGORY ID --}}
    <div class="form-group mb-3">
        <label for="category_id">Category</label>
        {{-- The $categories variable is passed from the PopupController@create method --}}
        <select class="form-control @error('category_id') is-invalid @enderror" 
                id="category_id" 
                name="category_id" 
                required>
            <option value="">Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" 
                        {{ old('category_id', $popup->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->title }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- IMAGE --}}
    <div class="form-group mb-3">
        <label for="image">Image (Optional)</label>
        <input type="file" 
               class="form-control @error('image') is-invalid @enderror" 
               id="image" 
               name="image">
        @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- IS ENABLED (Checkbox) --}}
    <div class="form-check mb-4">
        <input class="form-check-input" 
               type="checkbox" 
               name="is_enabled" 
               id="is_enabled" 
               value="1" 
               {{ old('is_enabled', $popup->is_enabled ?? 1) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_enabled">
            Enable Popup on Website
        </label>
    </div>

    {{-- SUBMIT BUTTON (The $buttonText variable comes from create.blade.php) --}}
    <button type="submit" class="btn btn-primary">{{ $buttonText }}</button>
    <a href="{{ route('superadmin.popups.index') }}" class="btn btn-secondary">Cancel</a>
</div>