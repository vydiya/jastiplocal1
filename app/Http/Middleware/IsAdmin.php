<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     * Hanya izinkan user dengan role === 'admin'
     */
    public function handle(Request $request, Closure $next)
    {
        // jika belum login → tolak (atau redirect ke login)
        if (! Auth::check()) {
            // redirect ke login admin (opsional)
            return redirect()->route('admin.login');
        }

        // jika bukan admin → abort 403
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya admin yang bisa mengakses halaman ini.');
        }

        return $next($request);
    }
}