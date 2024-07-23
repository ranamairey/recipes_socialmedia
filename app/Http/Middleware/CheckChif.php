<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CheckChif
{
    public function handle(Request $request, Closure $next)
    {
        $user=Auth::user();
        if($user){
            if($user->role == 'chef' || $user->role == 'admin'){
                return $next($request);
            }
        }
        return response()->json('Unauthorized chef',status: 401);
    }
}
