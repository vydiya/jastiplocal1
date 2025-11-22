<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * Usage examples:
     * - ->middleware('role:admin')
     * - ->middleware('role:admin,jastiper')      // daftar dipisah koma
     * - ->middleware('check.role:admin')         // kompatibilitas jika masih ada pemanggilan lama
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
          if (! Auth()->check()) { 
            return redirect()->route('login'); 
        } 
 
    if (! in_array(Auth()->user()->role, $roles)) { 
        abort(403, 'Anda tidak memiliki akses ke halaman ini.'); 
    } 
 
    return $next($request); 
    }
}
