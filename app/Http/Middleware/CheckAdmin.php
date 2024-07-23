<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\GeneralTrait;


class CheckAdmin
{
    
    public function handle(Request $request, Closure $next)
    {
        $user=Auth::user();
        if($user){
            if($user->role == 'admin'){
                return $next($request);
            }
        }
        return response()->json('Unauthorized admin',status: 401);
    }
}

