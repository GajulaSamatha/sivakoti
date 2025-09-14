@extends('layouts.superadmin_layouts.superadmin_base')

@section('page_title', 'Create Category')

@section('content')
    <div class="container mt-4">
        <h2>Create New Category</h2>
        <form action="{{ route('superadmin.categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Category Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>
            <div class="mb-3">
                <label for="parent_id" class="form-label">Parent Category</label>
                <select class="form-select" id="parent_id" name="parent_id">
                    <option value="">-- Select a Parent Category --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                    @endforeach
                </select>
            </div>
            <button style="background-color:#8b0000;color:white" type="submit" class="btn">Create</button>
        </form>
    </div>
@endsection