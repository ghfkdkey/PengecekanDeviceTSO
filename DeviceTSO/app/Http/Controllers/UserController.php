<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Regional;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        // Data yang bisa diakses berdasarkan role  
        $data = [
            'user' => $user,
            'accessible_regionals' => $user->getAccessibleRegionals(),
            'accessible_buildings' => $user->getAccessibleBuildings(),
        ];

        // Role-specific dashboard data
        switch ($user->role) {
            case User::ROLE_ADMIN:
                $data['total_users'] = User::count();
                $data['total_regionals'] = Regional::count();
                break;
                
            case User::ROLE_PIC_GA:
                $data['total_pic_operational'] = User::where('role', User::ROLE_PIC_OPERATIONAL)
                    ->where('regional_id', $user->regional_id)
                    ->count();
                $data['regional_buildings'] = $user->getAccessibleBuildings()->count();
                break;
                
            case User::ROLE_PIC_OPERATIONAL:
                $data['assigned_devices'] = $this->getAssignedDevicesCount($user);
                $data['pending_checks'] = $this->getPendingChecksCount($user);
                break;
        }

        return view('dashboard', $data);
    }

    public function index()
    {
        $user = auth()->user();
        $regionals = Regional::all(); // Untuk dropdown
        
        if ($user->isAdmin()) {
            // Admin bisa lihat semua user
            $users = User::with('regional')->orderBy('full_name')->get();
        } elseif ($user->isGA()) {
            // PIC GA hanya bisa lihat PIC Operational di regional yang sama + dirinya sendiri
            $users = User::where(function($query) use ($user) {
                $query->where('regional_id', $user->regional_id)
                      ->where('role', User::ROLE_PIC_OPERATIONAL);
            })->orWhere('id', $user->id)->with('regional')->orderBy('full_name')->get();
        } else {
            // PIC Operational hanya bisa lihat dirinya sendiri
            $users = User::where('id', $user->id)->with('regional')->get();
        }

        return view('users.index', compact('users', 'regionals'));
    }

    public function create()
    {
        $user = auth()->user();
        $regionals = Regional::all();
        
        // Tentukan role yang bisa dibuat berdasarkan user yang login
        $availableRoles = $this->getAvailableRoles($user);
        
        return view('users.create', compact('regionals', 'availableRoles'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'full_name' => 'required|string|max:100',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,' . User::ROLE_PIC_GA . ',' . User::ROLE_PIC_OPERATIONAL,
            'regional_id' => 'required_unless:role,admin|exists:regionals,id'
        ]);

        // Cek permission
        if (!$user->canCreateRole($request->role, $request->regional_id)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk membuat user dengan role tersebut');
        }

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'full_name' => $request->full_name,
            'password_hash' => Hash::make($request->password),
            'role' => $request->role,
            'regional_id' => $request->role === 'admin' ? null : $request->regional_id
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function show(User $targetUser)
    {
        $user = auth()->user();
        
        if (!$user->canManageTargetUser($targetUser) && $targetUser->id !== $user->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak memiliki izin untuk melihat user tersebut');
        }

        return view('users.show', compact('targetUser'));
    }

    public function edit(User $targetUser)
    {
        $user = auth()->user();
        
        if (!$user->canManageTargetUser($targetUser) && $targetUser->id !== $user->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak memiliki izin untuk mengedit user tersebut');
        }

        $regionals = Regional::all();
        $availableRoles = $this->getAvailableRoles($user);
        
        return view('users.edit', compact('targetUser', 'regionals', 'availableRoles'));
    }

    public function update(Request $request, User $targetUser)
    {
        $user = auth()->user();
        
        if (!$user->canManageTargetUser($targetUser) && $targetUser->id !== $user->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak memiliki izin untuk mengupdate user tersebut');
        }

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($targetUser->id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($targetUser->id)],
            'full_name' => 'required|string|max:100',
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,' . User::ROLE_PIC_GA . ',' . User::ROLE_PIC_OPERATIONAL,
            'regional_id' => 'required_unless:role,admin|exists:regionals,regional_id'
        ]);

        $updateData = [
            'username' => $validated['username'],
            'email' => $validated['email'],
            'full_name' => $validated['full_name'],
            'role' => $validated['role'],
            'regional_id' => $validated['role'] === 'admin' ? null : $validated['regional_id']
        ];

        if (!empty($validated['password'])) {
            $updateData['password_hash'] = Hash::make($validated['password']);
        }

        $targetUser->update($updateData);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $targetUser)
    {
        $user = auth()->user();
        
        if (!$user->canManageTargetUser($targetUser)) {
            return redirect()->route('users.index')->with('error', 'Anda tidak memiliki izin untuk menghapus user tersebut');
        }

        if ($targetUser->id === $user->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri');
        }

        $targetUser->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }
    
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'g-recaptcha-response' => 'required|captcha',
        ],[
            'g-recaptcha-response.required' => 'Mohon centang "I\'m not a robot"',
            'g-recaptcha-response.captcha' => 'Verifikasi captcha gagal, coba lagi.',
        ]);

        $user = User::where('username', $credentials['username'])->first();
        
        if ($user && Hash::check($credentials['password'], $user->password_hash)) {
            auth()->login($user);
            
            // Redirect berdasarkan role
            if ($user->isOperational()) {
                return redirect()->intended('/dashboard');
            }
            
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }

    // API Methods
    public function apiIndex()
    {
        $user = auth()->user();
        $users = [];
        
        if ($user->isAdmin()) {
            // Admin bisa lihat semua user
            $users = User::with('regional')->orderBy('full_name')->get();
        } elseif ($user->isGA()) {
            // PIC GA hanya bisa lihat PIC Operational di regional yang sama + dirinya sendiri
            $users = User::where(function($query) use ($user) {
                $query->where('id', $user->id)
                    ->orWhere(function($q) use ($user) {
                        $q->where('regional_id', $user->regional_id)
                        ->where('role', User::ROLE_PIC_OPERATIONAL);
                    });
            })->with('regional')->orderBy('full_name')->get();
        } else {
            // PIC Operational hanya bisa lihat dirinya sendiri
            $users = User::where('id', $user->id)->with('regional')->get();
        }

        return response()->json([
            'success' => true,
            'users' => $users,
            'currentUserId' => $user->id,
            'currentUserRole' => $user->role,
            'currentUserRegional' => $user->regional_id
        ]);
    }

    public function apiShow($id)
    {
        try {
            $user = auth()->user();
            $targetUser = User::with('regional')->findOrFail($id);
            
            if (!$user->canManageTargetUser($targetUser) && $targetUser->id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => $targetUser
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    }

    public function apiStore(Request $request)
    {
        try {
            $user = auth()->user();
            
            $validated = $request->validate([
                'username' => 'required|string|max:50|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'full_name' => 'required|string|max:100',
                'password' => 'required|string|min:6',
                'role' => 'required|in:admin,' . User::ROLE_PIC_GA . ',' . User::ROLE_PIC_OPERATIONAL,
                'regional_id' => 'required_unless:role,admin|exists:regionals,regional_id'
            ]);

            if (!$user->canCreateRole($validated['role'], $validated['regional_id'] ?? null)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk membuat user dengan role tersebut'
                ], 403);
            }

            $newUser = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'full_name' => $validated['full_name'],
                'password_hash' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'regional_id' => $validated['role'] === 'admin' ? null : ($validated['regional_id'] ?? null)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $newUser->load('regional')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function apiUpdate(Request $request, $id)
    {
        try {
            $user = auth()->user();
            $targetUser = User::findOrFail($id);
            
            if (!$user->canManageTargetUser($targetUser) && $targetUser->id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }
            
            $validated = $request->validate([
                'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($id)],
                'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
                'full_name' => 'required|string|max:100',
                'password' => 'nullable|string|min:6',
                'role' => 'required|in:admin,' . User::ROLE_PIC_GA . ',' . User::ROLE_PIC_OPERATIONAL,
                'regional_id' => 'required_unless:role,admin|exists:regionals,regional_id'
            ]);

            $updateData = [
                'username' => $validated['username'],
                'email' => $validated['email'],
                'full_name' => $validated['full_name'],
                'role' => $validated['role'],
                'regional_id' => $validated['role'] === 'admin' ? null : ($validated['regional_id'] ?? null)
            ];

            if (!empty($validated['password'])) {
                $updateData['password_hash'] = Hash::make($validated['password']);
            }

            $targetUser->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $targetUser->fresh()->load('regional')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
    }

    
    public function apiDestroy($id)
    {
        try {
            $user = auth()->user();
            $targetUser = User::findOrFail($id);
            
            if (!$user->canManageTargetUser($targetUser)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            if ($targetUser->id === $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete your own account'
                ], 400);
            }

            $targetUser->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper methods
    private function getAvailableRoles($user)
    {
        $roles = [];
        
        if ($user->isAdmin()) {
            $roles = [
                'admin' => 'Admin',
                User::ROLE_PIC_GA => 'PIC General Affair (GA)',
                User::ROLE_PIC_OPERATIONAL => 'PIC Operasional'
            ];
        } elseif ($user->isGA()) {
            $roles = [
                User::ROLE_PIC_OPERATIONAL => 'PIC Operasional'
            ];
        }
        
        return $roles;
    }

    private function getAssignedDevicesCount($user)
    {
        return $user->getAccessibleDevices()->count();
    }

    private function getPendingChecksCount($user)
    {
        return 0;
    }
}