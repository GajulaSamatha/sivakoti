@extends('layouts.superadmin_layouts.superadmin_base')

@section('page_title', 'Manage Categories')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Category Management</h2>
            <a href="{{ route('superadmin.categories.create') }}" class="btn btn-primary">Add New Category</a>
        </div>

        {{-- resources/views/superadmin/categories/superadmin_categories_index.blade.php --}}

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- START OF FILTER/SORT FORM --}}
<form action="{{ route('superadmin.categories.index') }}" method="GET" class="mb-4">
    <div class="row g-3">
        
        {{-- Search by Title Input --}}
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search by Category Title..." 
                   value="{{ $request->search }}" aria-label="Search">
        </div>

        {{-- Filter by Parent Category Dropdown --}}
        <div class="col-md-3">
            <select name="parent_id" class="form-select" aria-label="Filter by Parent">
                <option value="" @if(!$request->parent_id || $request->parent_id == 'all') selected @endif>
                    Top-Level Categories Only
                </option>
                <option value="all" @if($request->parent_id == 'all') selected @endif>
                    All Categories (Including Sub-levels)
                </option>
                @foreach($parentCategories as $parent)
                    <option value="{{ $parent->id }}" @if($request->parent_id == $parent->id) selected @endif>
                        {{ $parent->title }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Sort By Dropdown --}}
        <div class="col-md-3">
            <select name="sort" class="form-select" aria-label="Sort By">
                <option value="order_asc" @if($request->sort == 'order_asc' || !$request->sort) selected @endif>Order: Default</option>
                <option value="date_desc" @if($request->sort == 'date_desc') selected @endif>Date: Newest First</option>
                <option value="date_asc" @if($request->sort == 'date_asc') selected @endif>Date: Oldest First</option>
            </select>
        </div>

        {{-- Submit and Reset Buttons --}}
        <div class="col-md-2 d-flex">
            <button type="submit" class="btn btn-info w-50 me-2">Filter</button>
            <a href="{{ route('superadmin.categories.index') }}" class="btn btn-outline-secondary w-50">Reset</a>
        </div>
    </div>
</form>
{{-- END OF FILTER/SORT FORM --}}

        {{-- This section displays success or error messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Parent</th>
                    <th>Order</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>
                            @if($category->children->count() > 0)
                                <a href="{{ route('superadmin.categories.view', $category) }}">
                                    {{ $category->title }}
                                </a>
                            @else
                                {{ $category->title }}
                            @endif
                        </td>
                        <td>{{ $category->parent_id ? $category->parent->title : 'None' }}</td>
                        <td>{{ $category->order }}</td>
                        <td>
                            <a href="{{ route('superadmin.categories.edit', $category) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('superadmin.categories.destroy', $category) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection