@extends('layouts.superadmin_layouts.superadmin_base')

@section('page_title', 'Dashboard Overview')

@section('content')

    <div class="dashboard-grid">
        <a href="#" class="card">
            <i class="fas fa-sitemap"></i>
            <h3>Categories</h3>
        </a>
        <a href="#" class="card">
            <i class="fas fa-calendar-alt"></i>
            <h3>Events / Pujas</h3>
        </a>
        <a href="#" class="card">
            <i class="fas fa-file-alt"></i>
            <h3>Pages</h3>
        </a>
        <a href="#" class="card">
            <i class="fas fa-images"></i>
            <h3>Galleries</h3>
        </a>
        <a href="#" class="card">
            <i class="fas fa-users"></i>
            <h3>Users</h3>
        </a>
        <a href="#" class="card">
            <i class="fas fa-users-cog"></i>
            <h3>Admins</h3>
        </a>
    </div>

@endsection