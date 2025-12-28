<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class SupabaseAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Ambil token dari Header
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Header Authorization tidak ditemukan.'
            ], 401);
        }
        $token = str_replace('Bearer ', '', $token);

        try {
            // 2. DECRYPT TOKEN
            $userId = Crypt::decryptString($token);

            // 3. Simpan ID asli ke request
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
