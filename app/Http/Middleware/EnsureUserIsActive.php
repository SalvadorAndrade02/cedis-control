<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | Usuario autenticado pero desactivado
        |--------------------------------------------------------------------------
        */

        if (
            Auth::check()
            && ! Auth::user()->active
        ) {

            Auth::logout();

            $request
                ->session()
                ->invalidate();

            $request
                ->session()
                ->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' =>
                    'Tu acceso al sistema ha sido deshabilitado.',
                ]);
        }

        return $next($request);
    }
}
