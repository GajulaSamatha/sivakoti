{{-- resources/views/superadmin/popups/edit.blade.php --}}

@extends('layouts.superadmin_layouts.superadmin_base') 

@section('page_title', 'Edit Popup: ' . $popup->title)

@section('content')
    <div class="container mt-4">
        <h2>Edit Popup: {{ $popup->title }}</h2>
        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <form action="{{ route('superadmin.popups.update', $popup->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') {{-- This line changes the request method to PUT/PATCH for updates --}}

                    @include('superadmin.popups.form', ['buttonText' => 'Update Popup'])
                    
                </form>
            </div>
        </div>
    </div>
@endsection