// resources/views/superadmin/contacts/show.blade.php

@extends('layouts.superadmin_layouts.superadmin_base')

@section('page_title', 'Contact Message Details')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Message from: {{ $contact->name }}</h2>
            <a href="{{ route('superadmin.contacts.index') }}" class="btn btn-secondary">
                Back to Inbox
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-envelope-open"></i> Message Details
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th width="20%">Name</th>
                            <td>{{ $contact->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $contact->phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Received At</th>
                            <td>{{ $contact->created_at->format('F d, Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Read Status</th>
                            <td>
                                @if ($contact->read_at)
                                    <span class="badge bg-success">Read on {{ $contact->read_at->format('M j, Y') }}</span>
                                @else
                                    <span class="badge bg-danger">NEW / Unread</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <h5 class="mt-4">Message Content</h5>
                <div class="border p-3 bg-light rounded">
                    {{ $contact->message }}
                </div>
            </div>
            <div class="card-footer text-end">
                {{-- Link to delete is crucial --}}
                <form action="{{ route('superadmin.contacts.destroy', $contact->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Permanently delete this message?');">
                        <i class="fas fa-trash"></i> Delete Message
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection