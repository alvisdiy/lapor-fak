<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Session;

class SupabaseAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::has('user')) {
            $user = Session::get('user');
            if (isset($user->id)) {
                $request->merge(['current_user_id' => $user->id]);
            } elseif (is_array($user) && isset($user['id'])) {
                $request->merge(['current_user_id' => $user['id']]);
            }

            return $next($request);
        }

        $token = $request->header('Authorization');

        if (!$token) {
            if (!$request->expectsJson()) {
                return redirect()->route('login')->withErrors(['error' => 'Sesi habis, silakan login kembali.']);
            }

            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Header Authorization tidak ditemukan.'
            ], 401);
        }

        $token = str_replace('Bearer ', '', $token);

        try {
            $userId = Crypt::decryptString($token);
            $request->merge(['current_user_id' => $userId]);
        } catch (DecryptException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Token!'
            ], 401);
        }

        return $next($request);
    }
}
