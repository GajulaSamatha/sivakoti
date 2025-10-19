{{-- resources/views/superadmin/popups/index.blade.php --}}

@extends('layouts.superadmin_layouts.superadmin_base') 

@section('page_title', 'Manage Popups')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Popups List</h2>
            <a href="{{ route('superadmin.popups.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Popup
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($popups as $popup)
                        <tr>
                            <td>{{ $popup->id }}</td>
                            <td>{{ $popup->title }}</td>
                            <td>{{ $popup->category->title ?? 'N/A' }}</td>
                            <td>
                                @if ($popup->image)
                                    <img src="{{ asset('storage/' . $popup->image) }}" alt="{{ $popup->title }}" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    No Image
                                @endif
                            </td>
                            <td>
                                @if ($popup->is_enabled)
                                    <span class="badge bg-success">Enabled</span>
                                @else
                                    <span class="badge bg-danger">Disabled</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('superadmin.popups.edit', $popup->id) }}" class="btn btn-sm btn-warning me-2">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                
                                <form action="{{ route('superadmin.popups.destroy', $popup->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this popup?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination Links --}}
        <div class="mt-4">
            {{ $popups->links() }}
        </div>
    </div>
@endsection