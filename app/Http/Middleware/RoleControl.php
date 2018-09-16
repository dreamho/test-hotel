<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\JsonResponse;

/**
 * Class RoleControl
 * @package App\Http\Middleware
 */
class RoleControl
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = JWTAuth::authenticate();
        if (!$user) {
            return new JsonResponse(["error" => "Permission denied!"], 403);
        }
        $actions = $request->route()->getAction();
        $roles = isset($actions['roles']) ? $actions['roles'] : null;
        if ($user->hasAnyRole($roles) || !$roles) {
            return $next($request);
        }
        return new JsonResponse(["error" => "Permission denied!"], 403);
    }
}
