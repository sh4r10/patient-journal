<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        // Find the first registered user
        $firstUser = User::orderBy('created_at', 'asc')->first();

        // Exclude the first registered user from the list
        $users = User::where('id', '!=', $firstUser->id)->paginate(10);

        return view('assistants.index', compact('users', 'firstUser'));
    }


    public function create()
    {

        if (!Auth::user()->isAdmin()) {
            return redirect()->route('patients.index')->with('error', 'You do not have permission to access this page.');
        }
        return view('assistants.create');
    }


    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('patients.index')->with('error', 'You do not have permission to create users.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:assistant,admin',
        ]);

        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role' => $validatedData['role'],
        ]);


        return redirect()->route('assistants.index')->with('success', 'User created successfully.');
    }




    public function edit(User $user)
    {
        return view('assistants.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {

        if (!Auth::user()->isAdmin()) {
            return redirect()->route('patients.index')->with('error', 'You do not have permission to update users.');
        }

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
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('patients.index')->with('error', 'You do not have permission to delete users.');
        }
        // Find the first registered user
        $firstUser = User::orderBy('created_at', 'asc')->first();

        // Prevent deletion of the first registered user
        if ($user->id === $firstUser->id) {
            return redirect()->route('assistants.index')->with('error', 'Cannot delete the main user.');
        }
        $user->deleted_by = Auth::user()->email; // Set the deleted_by field to the current user's email
        $user->save();
        $user->delete();
        return redirect()->route('assistants.index')->with('success', 'User deleted successfully.');
    }
}
