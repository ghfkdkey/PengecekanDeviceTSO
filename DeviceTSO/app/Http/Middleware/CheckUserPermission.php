<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = auth()->user();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated'
                ], 401);
            }
            return redirect('/login');
        }

        $hasPermission = false;
        
        // PERBAIKAN: Cek berbagai variasi permission method
        $methodName = 'can' . ucfirst($permission);
        if (method_exists($user, $methodName)) {
            $hasPermission = $user->$methodName();
        }
        
        // PERBAIKAN: Tambah fallback untuk device-related permissions
        if (!$hasPermission) {
            // Cek berbagai variasi method name untuk device check
            $deviceCheckMethods = [
                'canAccessDeviceCheck',
                'canDeviceCheck', 
                'canAccessDevice',
                'canPerformCheck'
            ];
            
            if (str_contains(strtolower($permission), 'device')) {
                foreach ($deviceCheckMethods as $method) {
                    if (method_exists($user, $method)) {
                        $hasPermission = $user->$method();
                        if ($hasPermission) break;
                    }
                }
            }
        }

        // DEBUGGING: Log permission check details
        \Log::info('Permission check details', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'user_regional' => $user->regional_id,
            'permission_requested' => $permission,
            'method_name' => $methodName,
            'method_exists' => method_exists($user, $methodName),
            'has_permission' => $hasPermission,
            'is_admin' => $user->isAdmin(),
            'is_ga' => $user->isGA(),
            'is_operational' => $user->isOperational(),
            'url' => $request->fullUrl(),
            'debug_permissions' => method_exists($user, 'debugPermissions') ? $user->debugPermissions() : null
        ]);

        if ($hasPermission) {
            return $next($request);
        }

        // Log unauthorized access attempt
        \Log::warning('Unauthorized access attempt', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'user_regional' => $user->regional_id,
            'permission' => $permission,
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'available_methods' => get_class_methods($user)
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengakses halaman ini'
            ], 403);
        }

        // Redirect ke halaman yang sesuai berdasarkan role
        $redirectUrl = $this->getRedirectUrlBasedOnRole($user);
        
        return redirect($redirectUrl)->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut');
    }

    private function getRedirectUrlBasedOnRole($user)
    {
        // PERBAIKAN: Support untuk berbagai format role
        $role = strtolower($user->role);
        
        if ($user->isAdmin()) {
            return '/dashboard';
        } elseif ($user->isGA()) {
            return '/dashboard';
        } elseif ($user->isOperational()) {
            return '/dashboard';
        } else {
            return '/dashboard';
        }
    }
}