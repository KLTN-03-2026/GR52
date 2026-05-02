<?php

namespace App\Http\Middleware;

use App\Models\NhanVien;
use App\Support\Rbac;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user instanceof NhanVien || !Rbac::hasRole($user, $roles)) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn không có quyền truy cập chức năng này.',
            ], 403);
        }

        return $next($request);
    }
}
