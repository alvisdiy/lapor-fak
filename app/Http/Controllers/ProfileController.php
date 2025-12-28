<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Session::get('user');
        
        if (!$user) {
            return redirect()->route('login');
        }

        return view('profile.index', compact('user'));
    }
}