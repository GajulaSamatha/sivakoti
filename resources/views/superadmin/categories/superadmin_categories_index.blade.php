@extends('layouts.superadmin_layouts.superadmin_base')

@section('page_title', 'Manage Categories')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Category Management</h2>
            <a href="{{ route('superadmin.categories.create') }}" class="btn btn-primary">Add New Category</a>
        </div>

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