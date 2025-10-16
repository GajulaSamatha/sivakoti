@extends('layouts.superadmin_layouts.superadmin_base')

@section('page_title', 'Create New Event/Pooja')

@section('content')
    <div class="container mt-4">
        <h2>Create New Event or Pooja</h2>
        
        {{-- IMPORTANT: Add enctype for file uploads --}}
        <form action="{{ route('superadmin.events_poojas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            {{-- 1. TITLE & CATEGORY (Row) --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Category (Type)</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">-- Select Event/Pooja Type --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @if(old('category_id') == $category->id) selected @endif>
                                {{ $category->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- 2. DESCRIPTION --}}
            <div class="mb-3">
                <label for="description" class="form-label">Description (Details)</label>
                <textarea class="form-control" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                @error('description') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            {{-- 3. DATE/TIME & LOCATION (Row) --}}
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="start_date" class="form-label">Start Date/Time</label>
                    {{-- Note: Use type="datetime-local" for a modern browser picker --}}
                    <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                    @error('start_date') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="end_date" class="form-label">End Date/Time (Optional)</label>
                    <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}">
                    @error('end_date') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="location" class="form-label">Location/Venue</label>
                    <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" placeholder="e.g., Temple main hall, Online link">
                    @error('location') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>
            
            {{-- 4. PRICE & IMAGE (Row) --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Price/Donation Amount (e.g., 50.00)</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', 0) }}">
                    @error('price') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="image" class="form-label">Event/Pooja Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                    @error('image') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- 5. STATUS & ENABLED (Row) --}}
            <div class="row">
                {{-- Status (Draft/Published) --}}
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Publication Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="draft" @if(old('status') == 'draft') selected @endif>Draft</option>
                        <option value="published" @if(old('status') == 'published') selected @endif>Published</option>
                    </select>
                    @error('status') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                {{-- Enabled/Disabled --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label d-block">Public Visibility</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="is_enabled" id="enabledYes" value="1" 
                               @if(old('is_enabled', 1) == 1) checked @endif>
                        <label class="form-check-label" for="enabledYes">Enabled (Live)</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="is_enabled" id="enabledNo" value="0" 
                               @if(old('is_enabled') == 0) checked @endif>
                        <label class="form-check-label" for="enabledNo">Disabled (Hidden)</label>
                    </div>
                    @error('is_enabled') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Save and Create</button>
            <a href="{{ route('superadmin.events_poojas.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection