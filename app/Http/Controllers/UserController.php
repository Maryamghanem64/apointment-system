<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin'])->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role');
        
        $roles = Role::all();
        
        $users = User::with('roles')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($roleFilter, function ($query) use ($roleFilter) {
                $query->whereHas('roles', function ($q) use ($roleFilter) {
                    $q->where('roles.id', $roleFilter);
                });
            })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $search, 'role' => $roleFilter]);
            
        return view('users.index', compact('users', 'roles', 'search', 'roleFilter'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->numbers()
            ],
            'role' => 'required|exists:roles,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        // Remove role from validated data (we'll attach it separately)
        $roleId = $validated['role'];
        unset($validated['role']);

        $user = User::create($validated);

        // Assign role
        $user->roles()->attach($roleId);

        return redirect()->route('users.index')
            ->with('success','User created successfully');
    }

    public function show(User $user)
    {
        $user->load('roles');
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('roles');
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)->letters()->numbers()
            ],
            'role' => 'required|exists:roles,id',
        ]);

        if(!empty($validated['password'])){
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Update role
        $roleId = $validated['role'];
        unset($validated['role']);
        
        $user->update($validated);
        $user->roles()->sync([$roleId]);

        return redirect()->route('users.index')
            ->with('success','User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success','User deleted successfully');
    }
}
