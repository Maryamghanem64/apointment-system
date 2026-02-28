<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => ['required','confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        // Assign default role client
        $role = Role::where('name', 'client')->first();
        if ($role) {
            $user->roles()->attach($role);
        }

        auth()->login($user);

        // Redirect حسب Role (هنا client مباشرة)
        return redirect()->route('client.dashboard');
    }
}