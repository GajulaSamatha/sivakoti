@extends('layouts.superadmin_layouts.superadmin_base')

@section('page_title', 'Sub-categories of ' . $category->title)

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Sub-categories of {{ $category->title }}</h2>
            <a href="{{ route('superadmin.categories.create', ['parent_id' => $category->id]) }}" class="btn btn-primary">Add New Sub-category</a>
        </div>

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
                            <a href="#" class="btn btn-sm btn-warning">Edit</a>
                            <a href="#" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection