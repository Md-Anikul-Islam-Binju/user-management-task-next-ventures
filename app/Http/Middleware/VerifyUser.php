<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyUser
{
    public function handle(Request $request, Closure $next)
    {
        // Example: Only allow verified users
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized. User not verified.'], 403);
        }
        return $next($request);
    }
}
