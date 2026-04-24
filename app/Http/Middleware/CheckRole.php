<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Si no está autenticado
        if(!$request->user()){
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Verifica si el usuario tiene alguno de los roles permitidos
        if($request->user()->hasAnyRole($roles)){
            return $next($request);
        }

        return response()->json(['error'=>'Forbidden'],403);
    }
}
