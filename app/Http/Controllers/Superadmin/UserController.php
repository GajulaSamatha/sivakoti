<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; 
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the Users.
     */
    public function index()
    {
        // Fetch users, excluding the currently logged-in user, and paginate
        $users = User::with('roles')->where('id', '!=', auth()->id())->latest()->paginate(15);
        return view('superadmin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::pluck('name', 'id');
        $user = new User();
        // Use a common form file
        return view('superadmin.users.create', compact('roles', 'user'));
    }

    /**
     * Store a newly created user.
     */
public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|confirmed|min:8',
        'roles' => 'required|array', // e.g., ['1']
        'roles.*' => 'exists:roles,id', // Ensure the IDs exist in the roles table
    ]);

    // 1. Create the user
    $user = User::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'password' => Hash::make($validatedData['password']),
    ]);

    // 2. Retrieve Role NAMES based on the submitted Role IDs
    // The Spatie 'syncRoles' method requires role names or role objects.
    $roleIds = $validatedData['roles'];
    
    // Convert IDs to Role Names (strings)
    $roleNames = Role::whereIn('id', $roleIds)->pluck('name')->toArray();

    // 3. Sync roles using the retrieved NAMES (strings)
    $user->syncRoles($roleNames);

    return redirect()->route('superadmin.users.index')->with('success', 'User created successfully.');
}

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'id');
        // Get the IDs of the roles the user currently has
        $userRoles = $user->roles->pluck('id')->toArray();
        
        return view('superadmin.users.edit', compact('roles', 'user', 'userRoles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed', // Password is optional for update
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $updateData = [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
        ];

        // Only update password if a new one was provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validatedData['password']);
        }

        $user->update($updateData);

        $user->syncRoles($validatedData['roles']);

        return redirect()->route('superadmin.users.index')
                         ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent accidental deletion of the main admin user, if needed
        // if ($user->id === 1) { return back()->with('error', 'Cannot delete primary user.'); }

        $user->delete();

        return redirect()->route('superadmin.users.index')
                         ->with('success', 'User deleted successfully.');
    }
}