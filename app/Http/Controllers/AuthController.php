<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SupabaseService;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string',
            'nim' => 'required|string'
        ]);

        $full_name = $request->input('full_name');
        $nim = $request->input('nim');

        \Log::info('🔓 LOGIN ATTEMPT', [
            'full_name' => $full_name,
            'nim' => $nim
        ]);

        $user = $this->supabase->login($full_name, $nim);

        if ($user) {
            // Store user data in session
            \Log::info('✅ USER FOUND FROM SUPABASE', [
                'user_data' => $user
            ]);

            Session::put('user', $user);
            Session::save(); // Force save session

            \Log::info('✅ SESSION SAVED', [
                'session_id' => Session::getId(),
                'user_in_session' => Session::get('user'),
                'has_user_check' => Session::has('user')
            ]);
            
            return redirect()->route('dashboard');
        }

        \Log::warning('❌ LOGIN FAILED - User not found', [
            'full_name' => $full_name,
            'nim' => $nim
        ]);

        return back()->withErrors([
            'error' => 'Nama pengguna atau NIM yang Anda masukkan salah. Silakan coba lagi.',
        ]);
    }

    public function logout(Request $request)
    {
        Session::forget('user');
        Session::flush();
        
        return redirect()->route('login');
    }
}