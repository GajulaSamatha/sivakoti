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
                            {{-- Action buttons will go here (Edit/Delete) --}}
                            <a href="#" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- This is where pagination will go later if needed --}}

    </div>
@endsection