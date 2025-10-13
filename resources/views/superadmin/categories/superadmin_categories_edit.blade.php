@extends('layouts.superadmin_layouts.superadmin_base')

@section('page_title', 'Edit Category')

@section('content')
    <div class="container mt-4">
        <h2>Edit Category: {{ $category->title }}</h2>
        <form action="{{ route('superadmin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $category->title) }}" required>
                @error('title')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            {{-- NEW: DESCRIPTION FIELD --}}
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                {{-- NEW: STATUS (Draft/Published) DROPDOWN --}}
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Publication Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="published" @if(old('status', $category->status) == 'published') selected @endif>Published</option>
                        <option value="draft" @if(old('status', $category->status) == 'draft') selected @endif>Draft</option>
                    </select>
                    @error('status')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- In edit.blade.php, inside the is_enabled radio group --}}

                <div class="col-md-6 mb-3">
                    <label class="form-label d-block">Enabled Status</label> {{-- Label changed to Enabled Status --}}
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="is_enabled" id="enabledActive" value="1" 
                            @if(old('is_enabled', $category->is_enabled) == 1) checked @endif>
                        <label class="form-check-label" for="enabledActive">Enabled</label> {{-- Label changed to Enabled --}}
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="is_enabled" id="enabledInactive" value="0" 
                            @if(old('is_enabled', $category->is_enabled) == 0) checked @endif>
                        <label class="form-check-label" for="enabledInactive">Disabled</label> {{-- Label changed to Disabled --}}
                    </div>
                    @error('is_enabled')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- PARENT CATEGORY DROPDOWN (Existing code, check its location) --}}
            <div class="mb-3">
                <label for="parent_id" class="form-label">Parent Category</label>
                <select class="form-control" id="parent_id" name="parent_id">
                    <option value="">None</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @if($cat->id == old('parent_id', $category->parent_id)) selected @endif>
                            {{ $cat->title }}
                        </option>
                    @endforeach
                </select>
                @error('parent_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="order" class="form-label">Order</label>
                <input type="number" class="form-control" id="order" name="order" value="{{ old('order', $category->order) }}">
                @error('order')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-warning">Update Category</button>
            <a href="{{ route('superadmin.categories.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection