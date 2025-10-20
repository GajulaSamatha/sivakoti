
@extends('layouts.superadmin_layouts.superadmin_base') 

@section('page_title', 'Create New Popup')

@section('content')
    <div class="container mt-4">
        <h2>Create New Popup</h2>
        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <form action="{{ route('superadmin.popups.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @include('superadmin.popups.form', ['buttonText' => 'Create Popup'])
                    
                </form>
            </div>
        </div>
    </div>
@endsection

