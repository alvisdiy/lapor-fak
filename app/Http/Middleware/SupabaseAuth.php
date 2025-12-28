<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SupabaseAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // DEBUGGING: Log all request details
        \Log::info('═══ SupabaseAuth Middleware Called ═══');
        \Log::info('REQUEST DETAILS', [
            'path' => $request->path(),
            'url' => $request->url(),
            'method' => $request->method(),
            'session_id' => Session::getId(),
            'has_session_user' => Session::has('user'),
            'all_session_keys' => array_keys(Session::all()),
            'user_data' => Session::get('user') ?? 'NULL',
            'cookies' => $request->cookies->all()
        ]);

        if (!Session::has('user')) {
            \Log::warning('❌ AUTH FAILED - No user in session!', [
                'url' => $request->path(),
                'session_id' => Session::getId()
            ]);
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        \Log::info('✅ AUTH SUCCESS - User found in session', [
            'user_id' => Session::get('user')['id'] ?? 'unknown'
        ]);

        return $next($request);
    }
}