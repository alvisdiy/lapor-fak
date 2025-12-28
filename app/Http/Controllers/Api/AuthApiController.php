<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\SupabaseService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class AuthApiController extends ApiController
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'full_name' => 'required|string',
                'nim' => 'required|string'
            ]);

            $user = $this->supabase->login($validated['full_name'], $validated['nim']);

            if ($user) {
                $encryptedToken = Crypt::encryptString($user['id']);
                return $this->success([
                    'user' => $user,
                    'token' => $encryptedToken,
                    'message_for_dev' => 'Gunakan token ini di Header Authorization untuk request selanjutnya'
                ], 'Login successful', 200);
            } else {
                return $this->error('Invalid credentials', 401);
            }
        } catch (\Exception $e) {
            return $this->error('Server error: ' . $e->getMessage(), 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        return $this->success(null, 'Logout successful (Client side only)', 200);
    }

    public function currentUser(Request $request): JsonResponse
    {
        try {
            $user = Session::get('user');

            if (!$user) {
                return $this->error('Unauthorized', 401);
            }

            Log::info('API - Get current user', ['user_id' => $user['id']]);

            return $this->success($user, 'User retrieved successfully', 200);
        } catch (\Exception $e) {
            Log::error('API - Get current user error: ' . $e->getMessage());
            return $this->error('Server error: ' . $e->getMessage(), 500);
        }
    }
}
