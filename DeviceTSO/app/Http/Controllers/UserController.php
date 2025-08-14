<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password_hash)) {
            throw ValidationException::withMessages([
                'username' => ['Username atau password salah.'],
            ]);
        }

        Auth::login($user);
        
        return redirect()->intended('/dashboard')->with('success', 'Login berhasil!');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Logout berhasil!');
    }

    // Dashboard (setelah login)
    public function dashboard()
    {
        return view('dashboard');
    }

    // CRUD Operations untuk user management
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6',
            'full_name' => 'required|string|max:100',
            'role' => 'nullable|string|max:20'
        ]);

        User::create([
            'username' => $request->username,
            'password_hash' => Hash::make($request->password),
            'full_name' => $request->full_name,
            'role' => $request->role ?? 'user'
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat!');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $id,
            'full_name' => 'required|string|max:100',
            'role' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6'
        ]);

        $data = [
            'username' => $request->username,
            'full_name' => $request->full_name,
            'role' => $request->role ?? 'user'
        ];

        if (!empty($request->password)) {
            $data['password_hash'] = Hash::make($request->password);
        }

        $user->update($data);
        
        return redirect()->route('users.index')->with('success', 'User berhasil diupdate!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}