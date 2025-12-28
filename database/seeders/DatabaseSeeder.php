<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\SupabaseService;

class DatabaseSeeder extends Seeder
{
    protected $supabase;

    public function __construct()
    {
        $this->supabase = app(SupabaseService::class);
    }

    public function run()
    {
        // Data users sudah diinsert via SQL di Supabase
        // Jika perlu, bisa tambahkan data dummy lainnya
    }
}