@extends('layouts.superadmin_layouts.superadmin_base')

@section('page_title', 'Manage Events & Pujas')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Events & Pujas Management</h2>
            {{-- Link to the create page --}}
            <a href="{{ route('superadmin.events_poojas.create') }}" class="btn btn-primary">Add New Event/Pooja</a>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th>Enabled</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($eventsPoojas as $item)
                    <tr>
                        {{-- Link Title to the Show page --}}
                        <td>
                            <a href="{{ route('superadmin.events_poojas.show', $item->id) }}">
                                {{ $item->title }}
                            </a>
                        </td>
                        {{-- Relationship: Requires the 'category' relationship in the EventPooja model --}}
                        <td>{{ $item->category->title ?? 'Uncategorized' }}</td>
                        {{-- Display Start Date (Carbon instance) --}}
                        <td>{{ $item->start_date->format('M j, Y H:i') }}</td>
                        <td>
                            <span class="badge bg-{{ $item->status == 'published' ? 'success' : 'warning' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $item->is_enabled ? 'primary' : 'secondary' }}">
                                {{ $item->is_enabled ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        
                        {{-- **CORRECTED IMAGE DISPLAY LOGIC** --}}
                        <td>
                            @if ($item->image)
                                <img src="{{ asset($item->image) }}" alt="{{ $item->title }}" style="max-width: 50px; height: auto;">
                            @else
                                <small>No Image</small>
                            @endif
                        </td>
                        
                        <td>
                            {{-- Edit Link --}}
                            <a href="{{ route('superadmin.events_poojas.edit', $item->id) }}" class="btn btn-sm btn-info">Edit</a>

                            {{-- DELETE FORM --}}
                            <form action="{{ route('superadmin.events_poojas.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this event?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- PAGINATION LINKS --}}
        <div class="mt-4">
            {{ $eventsPoojas->links() }}
        </div>
    </div>
@endsection