<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sets the application locale based on the authenticated user's locale preference.
 *
 * This must run as middleware rather than in a service provider, because service provider
 * callbacks (e.g. Auth::resolved) only fire when the auth guard is first accessed — which
 * can happen mid-render (e.g. at an @can directive). Any translation calls before that
 * point would still use the default locale. Middleware runs before any view is rendered,
 * guaranteeing a consistent locale for all __() calls throughout the request.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            app()->setLocale(Auth::user()->locale->value);
        }

        return $next($request);
    }
}

