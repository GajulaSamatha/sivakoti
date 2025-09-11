<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\View\View
     */
    // ...

public function index()
{
    $categories = Category::with('children')->whereNull('parent_id')->orderBy('order')->get();

    return view('superadmin.categories.superadmin_categories_index', compact('categories'));
}

public function create()
{
    $categories = Category::all();

    return view('superadmin.categories.superadmin_categories_create', compact('categories'));
}

// ...
    // We'll add store, edit, update, and destroy methods later
}