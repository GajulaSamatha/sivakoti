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
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th>Enabled</th>
                    <th>Images</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($eventsPoojas as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        {{-- Use the relationship defined in the model --}}
                        <td>{{ $item->category->title ?? 'Uncategorized' }}</td>
                        <td>{{ $item->start_date->format('M j, Y H:i') }}</td>
                        <td>{{ $item->status }}</td>
                        <td>{{ $item->is_enabled ? 'Yes' : 'No' }}</td>
                        <td><img src="{{ $item->image }}"></td>
                        <td>
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

        {{-- This is where pagination will go later if needed --}}

    </div>
@endsection