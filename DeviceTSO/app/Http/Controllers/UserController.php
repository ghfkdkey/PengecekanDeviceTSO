<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Pastikan hanya ada SATU import User
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('full_name')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'full_name' => 'required|string|max:100',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|max:20',
        ]);

        User::create([
            'username' => $validated['username'],
            'full_name' => $validated['full_name'],
            'password_hash' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'full_name' => 'required|string|max:100',
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|string|max:20',
        ]);

        $updateData = [
            'username' => $validated['username'],
            'full_name' => $validated['full_name'],
            'role' => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password_hash'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }
    
    /**
     * Tampilkan form login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Custom authentication karena menggunakan password_hash
        $user = User::where('username', $credentials['username'])->first();
        
        if ($user && Hash::check($credentials['password'], $user->password_hash)) {
            auth()->login($user);
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput($request->except('password'));
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }

    /**
     * Dashboard page
     */
    public function dashboard()
    {
        return view('dashboard');
    }

    // API Methods for AJAX requests
    
    /**
     * API: Get all users (for AJAX)
     */
    public function apiIndex()
    {
        try {
            $users = User::orderBy('full_name')->get();
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load users',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get specific user
     */
    public function apiShow($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'User not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function apiStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:50|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'full_name' => 'required|string|max:100',
                'password' => 'required|string|min:6',
                'role' => 'required|in:admin,PIC General Affair (GA),PIC Operasional',
            ]);

            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'full_name' => $validated['full_name'],
                'password_hash' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to create user',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function apiUpdate(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            $validated = $request->validate([
                'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($id)],
                'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
                'full_name' => 'required|string|max:100',
                'password' => 'nullable|string|min:6',
                'role' => 'required|in:admin,PIC General Affair (GA),PIC Operasional',
            ]);

            $updateData = [
                'username' => $validated['username'],
                'email' => $validated['email'],
                'full_name' => $validated['full_name'],
                'role' => $validated['role'],
            ];

            if (!empty($validated['password'])) {
                $updateData['password_hash'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to update user',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function apiDestroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete user',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}