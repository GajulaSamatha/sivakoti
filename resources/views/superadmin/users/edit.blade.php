@extends('layouts.superadmin_layouts.superadmin_base') 

@section('page_title', 'Edit User: ' . $user->name)

@section('content')
    <div class="container mt-4">
        <h2>Edit User: {{ $user->name }}</h2>
        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <form action="{{ route('superadmin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('superadmin.users.form', ['buttonText' => 'Update User'])
                </form>
            </div>
        </div>
    </div>
@endsection