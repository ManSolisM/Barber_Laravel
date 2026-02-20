<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientePermanenteMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->isPermanente()) {
            return redirect()
                ->route('cliente.dashboard')
                ->withErrors(['error' => 'Esta función es solo para clientes permanentes.']);
        }

        return $next($request);
    }
}