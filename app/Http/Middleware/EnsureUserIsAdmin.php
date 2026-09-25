<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/** Management panel gate: any active staff role. Individual sections add can:<permission> on top. */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->is_active) {
            // Deactivated while signed in: end the session straight away.
            Auth::guard('web')->logout();
            $request->session()->invalidate();

            return redirect()->route('login')->withErrors(['email' => 'Your account has been deactivated.']);
        }

        if (! $user || ! $user->isStaff()) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
