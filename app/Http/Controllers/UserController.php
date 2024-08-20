<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use App\Models\User;

class UserController extends Controller
{
    public function logout(Request $request)
    {
        $info = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('auth.login');
    }

    public function login(Request $request)
    {
        $info = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // auth()->login($info);

        return to_route('patients.index');
    }

    public function index()
    {
        $users = User::query()->paginate(10);
        return view('assistants.index', compact('users'));
    }

    public function create()
    {
        return view('assistants.create');
    }

   
    public function store(Request $request)
    {
        \Log::info('Store method called');
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:assistant,admin',
        ]);
    
        \Log::info('Validated data:', $validatedData);
    
        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role' => $validatedData['role'],
        ]);
    
        \Log::info('User created successfully');
    
        return redirect()->route('assistants.index')->with('success', 'User created successfully.');
    }
    
    
    

    public function edit(User $user)
    {
        return view('assistants.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:admin,assistant',
        ]);

        $user->name = $request->username;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->role = $request->role;
        $user->save();

        return redirect()->route('assistants.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('assistants.index')->with('success', 'User deleted successfully.');
    }
}
