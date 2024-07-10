<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\User;
 
class UserController extends Controller
{



    public function logout(Request $request)
    {   
       $info= $request->validate([
            'username'=>['required','string'],
            'password'=> ['required','string']

       ]);
        auth()->logout();
        // Invalidate the session.
    $request->session()->invalidate();

    // Regenerate the session token.
    $request->session()->regenerateToken();
        return to_route('auth.login');
       
    }


    public function login(Request $request)
    {   
       $info= $request->validate([
            'username'=>['required','string'],
            'password'=> ['required','string']

       ]);


        
        // auth()->login($info);
        return to_route('patients.index');
       
    }
    /** 
     * Display a listing of the resource.
     */
     public function index()
    {
        $users = User::all();
        return view('assistance.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
  

    public function create()
    {
        return view('assistance.createUser');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,assistant',
        ]);

        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('assistance.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('assistance.editUser', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
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

        return redirect()->route('assistance.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('assistance.index')->with('success', 'User deleted successfully.');
    }

}
