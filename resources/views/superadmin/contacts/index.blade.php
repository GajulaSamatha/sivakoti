{{-- resources/views/superadmin/contacts/index.blade.php --}}

@extends('layouts.superadmin_layouts.superadmin_base')

@section('page_title', 'Contact Messages')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Contact Submissions</h2>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Received At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loop through the paginated $contacts collection --}}
                    @foreach ($contacts as $contact)
                        {{-- Highlight rows that are UNREAD --}}
                        <tr class="{{ $contact->read_at ? '' : 'table-warning fw-bold' }}">
                            
                            {{-- Link Name to the Show page --}}
                            <td>
                                <a href="{{ route('superadmin.contacts.show', $contact->id) }}" class="text-decoration-none">
                                    {{ $contact->name }}
                                </a>
                            </td>
                            <td>{{ $contact->email }}</td>
                            <td>{{ $contact->phone ?? 'N/A' }}</td>
                            
                            {{-- Status Badge (Read or New) --}}
                            <td>
                                @if ($contact->read_at)
                                    <span class="badge bg-success">Read</span>
                                @else
                                    <span class="badge bg-danger">New</span>
                                @endif
                            </td>
                            
                            {{-- Timestamp of submission --}}
                            <td>{{ $contact->created_at->format('M j, Y H:i') }}</td>
                            
                            <td>
                                {{-- View full message (Show link) --}}
                                <a href="{{ route('superadmin.contacts.show', $contact->id) }}" class="btn btn-sm btn-info">View</a>

                                {{-- DELETE FORM (using method spoofing) --}}
                                <form action="{{ route('superadmin.contacts.destroy', $contact->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this message?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination Links --}}
        <div class="mt-4">
            {{ $contacts->links() }}
        </div>
    </div>
@endsection