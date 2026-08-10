<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next, string $role = 'admin'): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($role === 'root' && !$user->isRoot()) {
            return redirect('/home')->with('error', 'Acesso exclusivo Geinf');
        }

        if ($role === 'admin' && !$user->isAdmin()) {
            return redirect('/home')->with('error', 'Acesso não autorizado');
        }

        return $next($request);
    }
}