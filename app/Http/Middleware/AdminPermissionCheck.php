<?php
namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;

class AdminPermissionCheck
{
    public function handle(Request $request, Closure $next) {
        if (! Auth::guard('admin')->check()) {
            return redirect('/ad/login');
        }

        return $next($request);
    }
}