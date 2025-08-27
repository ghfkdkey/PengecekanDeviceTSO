<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function checkPermission($permission)
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        $methodName = 'can' . ucfirst($permission);
        return method_exists($user, $methodName) && $user->$methodName();
    }

    protected function unauthorized($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk akses ini');
    }
}