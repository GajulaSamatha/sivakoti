@extends('layouts.superadmin_layouts.superadmin_base')

@section('page_title', 'Sub-categories of ' . $category->title)

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Sub-categories of {{ $category->title }}</h2>
            <a href="{{ route('superadmin.categories.create', ['parent_id' => $category->id]) }}" class="btn btn-primary">Add New Sub-category</a>
        </div>
        
        {{-- This section displays success or error messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Add the back link here --}}
        @if ($category->parent_id)
            <a href="{{ route('superadmin.categories.view', $category->parent_id) }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Back to {{ $category->parent->title }}
            </a>
        @else
            <a href="{{ route('superadmin.categories.index') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Back to Main Categories
            </a>
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
                @foreach ($children as $child)
                    <tr>
                        <td>
                            @if($child->children->count() > 0)
                                <a href="{{ route('superadmin.categories.view', $child) }}">
                                    {{ $child->title }}
                                </a>
                            @else
                                {{ $child->title }}
                            @endif
                        </td>
                        <td>{{ $child->parent->title ?? 'None' }}</td>
                        <td>{{ $child->order }}</td>
                        <td>
                            <a href="{{ route('superadmin.categories.edit', $child) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('superadmin.categories.destroy', $child) }}" method="POST" style="display:inline-block;">
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