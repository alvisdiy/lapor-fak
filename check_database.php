<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\SupabaseService;

$supabase = app(SupabaseService::class);

echo "Testing Supabase connection...\n";

// Test connection
if ($supabase->testConnection()) {
    echo "✓ Connected to Supabase\n";
} else {
    echo "✗ Failed to connect to Supabase\n";
    exit(1);
}

// Test create a simple report
echo "\nTesting report creation...\n";

$testData = [
    'user_id' => 1,
    'title' => 'Test Report',
    'location' => 'Test Location',
    'facility' => 'Test Facility',
    'description' => 'Test description',
    'status' => 'Dikirim',
    'building_id' => 1,
    'room_id' => 1,
    'facility_id' => 1,
    'report_id' => 'TEST-' . time(),
    'created_at' => now()->toIso8601String(),
    'updated_at' => now()->toIso8601String()
];

$result = $supabase->createReport($testData);

if ($result) {
    echo "✓ Report created successfully\n";
    echo "Report ID: " . ($result['id'] ?? 'N/A') . "\n";
} else {
    echo "✗ Failed to create report\n";
    echo "Possible issues:\n";
    echo "1. Table 'reports' doesn't exist\n";
    echo "2. Missing columns in table\n";
    echo "3. Check Supabase RLS (Row Level Security)\n";
}