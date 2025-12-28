<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\SupabaseService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

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
            Log::info('API - Login attempt');

            $validated = $request->validate([
                'full_name' => 'required|string',
                'nim' => 'required|string'
            ]);

            $user = $this->supabase->login($validated['full_name'], $validated['nim']);

            if ($user) {
                Log::info('API - Login successful', ['user_id' => $user['id']]);
             
                Session::put('user', $user);
                Session::put('user.id', $user['id']);

                return $this->success([
                    'user' => $user,
                    'token' => Session::getId()
                ], 'Login successful', 200);
            } else {
                Log::warning('API - Login failed: invalid credentials');
                return $this->error('Invalid credentials', 401);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('API - Login validation error:', ['errors' => $e->errors()]);
            return $this->error('Validation failed', 422, $e->errors());
            
        } catch (\Exception $e) {
            Log::error('API - Login error: ' . $e->getMessage());
            return $this->error('Server error: ' . $e->getMessage(), 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            Log::info('API - Logout');
            
            Session::flush();
            
            return $this->success(null, 'Logout successful', 200);

        } catch (\Exception $e) {
            Log::error('API - Logout error: ' . $e->getMessage());
            return $this->error('Server error: ' . $e->getMessage(), 500);
        }
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
