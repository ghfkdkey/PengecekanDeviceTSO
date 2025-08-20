<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }

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

    // CRUD Operations untuk user management
    public function index()
    {
        try {
            if (request()->expectsJson()) {
                $users = User::select('user_id', 'username', 'full_name', 'role', 'created_at', 'updated_at')
                             ->orderBy('created_at', 'desc')
                             ->get();
                
                Log::info('Users fetched successfully', ['count' => $users->count()]);
                return response()->json($users);
            }

            return view('users.index');
        } catch (\Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching users: ' . $e->getMessage()
                ], 500);
            }
            
            return view('users.index')->withErrors(['error' => 'Error loading users']);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:50|unique:users,username',
                'password' => 'required|string|min:6',
                'full_name' => 'required|string|max:100',
                'role' => 'required|string|in:admin,supervisor,user'
            ]);

            $user = User::create([
                'username' => $validated['username'],
                'password_hash' => Hash::make($validated['password']),
                'full_name' => $validated['full_name'],
                'role' => $validated['role']
            ]);

            Log::info('User created successfully', ['user_id' => $user->user_id]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User created successfully',
                    'data' => $user->fresh()
                ], 201);
            }

            return redirect()->route('users.index')->with('success', 'User berhasil dibuat!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed for user creation', ['errors' => $e->errors()]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating user: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        try {
            $user = User::where('user_id', $id)->firstOrFail();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $user
                ]);
            }
            
            return view('users.show', compact('user'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('User not found', ['user_id' => $id]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }
            
            return back()->withErrors(['error' => 'User not found']);
        } catch (\Exception $e) {
            Log::error('Error fetching user: ' . $e->getMessage());
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching user'
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Error fetching user']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::where('user_id', $id)->firstOrFail();

            $validated = $request->validate([
                'username' => 'required|string|max:50|unique:users,username,' . $user->user_id . ',user_id',
                'full_name' => 'required|string|max:100',
                'role' => 'required|string|in:admin,supervisor,user',
                'password' => 'nullable|string|min:6'
            ]);

            $data = [
                'username' => $validated['username'],
                'full_name' => $validated['full_name'],
                'role' => $validated['role']
            ];

            if (!empty($validated['password'])) {
                $data['password_hash'] = Hash::make($validated['password']);
            }

            $user->update($data);
            
            Log::info('User updated successfully', ['user_id' => $user->user_id]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User updated successfully',
                    'data' => $user->fresh()
                ]);
            }
            
            return redirect()->route('users.index')->with('success', 'User berhasil diupdate!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('User not found for update', ['user_id' => $id]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }
            
            return back()->withErrors(['error' => 'User not found']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed for user update', ['errors' => $e->errors()]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating user: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::where('user_id', $id)->firstOrFail();
            
            // Prevent deleting current user
            if (Auth::id() == $user->user_id) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete current user'
                    ], 400);
                }
                return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus user yang sedang login!');
            }
            
            $user->delete();
            
            Log::info('User deleted successfully', ['user_id' => $id]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User deleted successfully'
                ]);
            }
            
            return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('User not found for deletion', ['user_id' => $id]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }
            
            return back()->withErrors(['error' => 'User not found']);
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting user: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}