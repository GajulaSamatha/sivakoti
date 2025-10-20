@extends('layouts.superadmin_layouts.superadmin_base') 

@section('page_title', 'Create New User')

@section('content')
    <div class="container mt-4">
        <h2>Create New User</h2>
        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <form action="{{ route('superadmin.users.store') }}" method="POST">
                    @csrf
                    @include('superadmin.users.form', ['buttonText' => 'Create User'])
                </form>
            </div>
        </div>
    </div>
@endsection