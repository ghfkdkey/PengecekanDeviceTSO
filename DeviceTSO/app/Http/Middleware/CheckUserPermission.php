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

        $methodName = 'can' . ucfirst($permission);
        
        if (method_exists($user, $methodName) && $user->$methodName()) {
            return $next($request);
        }

        // Log unauthorized access attempt
        \Log::warning('Unauthorized access attempt', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'permission' => $permission,
            'url' => $request->fullUrl(),
            'method' => $request->method()
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
        switch ($user->role) {
            case 'admin':
                return '/dashboard';
            case 'pic_ga':
                return '/dashboard';
            case 'pic_operational':
                return '/dashboard';
            default:
                return '/dashboard';
        }
    }
}