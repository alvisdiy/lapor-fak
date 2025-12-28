<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SupabaseService;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index()
    {
        $userId = Session::get('user.id');
        
        if (!$userId) {
            return redirect()->route('login');
        }

        $stats = $this->supabase->getStats($userId);
        $reports = $this->supabase->getAllReports($userId);
        
        $recentReports = array_slice($reports, 0, 4);
        
        return view('dashboard.index', [
            'stats' => $stats,
            'reports' => $recentReports
        ]);
    }
}