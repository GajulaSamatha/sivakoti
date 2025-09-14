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