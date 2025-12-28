<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SupabaseService;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index(Request $request)
    {
        try {
            $user = Session::get('user');
            if (!$user || !isset($user['id'])) {
                if ($request->header('X-Requested-With') == 'XMLHttpRequest') {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
                return redirect()->route('login')
                    ->with('error', 'Session expired. Please login again.');
            }

            $userId = $user['id'];
            Log::info('Getting all reports for user', ['user_id' => $userId]);
            $allReports = $this->supabase->getAllReports($userId);

            $status = $request->get('status');
            $reports = collect($allReports);

            if ($status && $status !== 'all' && $status !== 'semua') {
                $reports = $reports->where('status', $status);
            }

            $reports = $reports->values()->all();

            Log::info('Reports filtered', [
                'total_reports' => count($allReports),
                'filtered_count' => count($reports),
                'status_filter' => $status
            ]);

            // Check if request is AJAX/API
            if ($request->header('X-Requested-With') == 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'data' => $reports,
                    'message' => 'Reports retrieved successfully'
                ]);
            }

            return view('reports.index', compact('reports', 'allReports'));

        } catch (\Exception $e) {
            Log::error('Error in reports index:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            if ($request->header('X-Requested-With') == 'XMLHttpRequest') {
                return response()->json(['error' => 'Failed to retrieve reports'], 500);
            }
            return back()->with('error', 'Terjadi kesalahan saat mengambil data laporan.');
        }
    }

    public function createStep1()
    {
        $buildings = $this->supabase->getBuildings();
        return view('reports.create-step1', compact('buildings'));
    }

    public function createStep2(Request $request)
    {
        $request->validate(['building_id' => 'required|numeric']);
        
        Session::put('report_data.building_id', $request->building_id);
        
        $building = collect($this->supabase->getBuildings())
            ->where('id', $request->building_id)
            ->first();
        
        $rooms = $this->supabase->getRooms($request->building_id);
        
        return view('reports.create-step2', [
            'building' => $building,
            'rooms' => $rooms
        ]);
    }

    public function createStep3(Request $request)
    {
        $request->validate(['room_id' => 'required|numeric']);
        
        Session::put('report_data.room_id', $request->room_id);
        
        $facilities = $this->supabase->getFacilities();
        return view('reports.create-step3', compact('facilities'));
    }

    public function createStep4(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate(['facility_id' => 'required|numeric']);
            Session::put('report_data.facility_id', $request->facility_id);
        }
        
        $reportData = Session::get('report_data', []);
        
        if (empty($reportData) || 
            !isset($reportData['building_id']) || 
            !isset($reportData['room_id']) || 
            !isset($reportData['facility_id'])) {
            
            return redirect()->route('reports.create-step1')
                ->with('error', 'Sesi pembuatan laporan telah kadaluarsa. Silakan mulai dari awal.');
        }
        
        return view('reports.create-step4');
    }

    public function store(Request $request) 
    {
        try {
            Log::info('=== STORE METHOD STARTED ===');
            Log::info('Request data:', ['data' => $request->except('photos')]);
            
            $request->validate([
                'description' => 'required|min:10|string',
                'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'building_id' => 'required|numeric',
                'room_id' => 'required|numeric',
                'facility_id' => 'required|numeric'
            ]);

            $userId = Session::get('user.id');
            if (!$userId) {
                Log::warning('No user ID in session');
                return redirect()->route('login')
                    ->with('error', 'Session expired. Please login again.');
            }
            
            Log::info('User ID from session:', ['user_id' => $userId]);

            $building = collect($this->supabase->getBuildings())
                ->where('id', $request->building_id)
                ->first();
            
            if (!$building) {
                Log::error('Building not found', ['building_id' => $request->building_id]);
                return back()->with('error', 'Building not found. Please start over.')
                    ->withInput();
            }
            
            $rooms = $this->supabase->getRooms($request->building_id);
            $room = collect($rooms)->where('id', $request->room_id)->first();
            
            if (!$room) {
                Log::error('Room not found', ['room_id' => $request->room_id]);
                return back()->with('error', 'Room not found. Please start over.')
                    ->withInput();
            }
            
            $facilities = $this->supabase->getFacilities();
            $facility = collect($facilities)->where('id', $request->facility_id)->first();
            
            if (!$facility) {
                Log::error('Facility not found', ['facility_id' => $request->facility_id]);
                return back()->with('error', 'Facility not found. Please start over.')
                    ->withInput();
            }

            $reportCode = 'LAPORAN-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
            
            Log::info('Generated report code:', ['report_code' => $reportCode]);

            $data = [
                'user_id' => (int) $userId,
                'title' => "Kerusakan {$facility['name']} - {$room['name']}",
                'location' => "{$building['name']}, {$room['name']}",
                'facility' => $facility['name'],
                'description' => $request->description,
                'status' => 'Dikirim',
                'building_id' => (int) $request->building_id,
                'room_id' => (int) $request->room_id,
                'facility_id' => (int) $request->facility_id,
                'report_code' => $reportCode, 
                'created_at' => Carbon::now()->toIso8601String(),
                'updated_at' => Carbon::now()->toIso8601String()
            ];

            Log::info('Data prepared for Supabase:', $data);

            $photoPaths = [];
            if ($request->hasFile('photos')) {
                Log::info('Photos found:', ['count' => count($request->file('photos'))]);
                
                foreach ($request->file('photos') as $index => $photo) {
                    $filename = time() . '_' . $index . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    
                    $uploadPath = public_path('uploads/reports');
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    
                    $photo->move($uploadPath, $filename);
                    
                    $photoPaths[] = '/uploads/reports/' . $filename;
                    
                    Log::info('Photo uploaded:', ['file' => $filename]);
                }
                
                if (!empty($photoPaths)) {
                    $data['photo_urls'] = json_encode($photoPaths);
                    
                    $data['photo_url'] = $photoPaths[0];
                    
                    Log::info('Photos processed:', [
                        'photo_urls' => $photoPaths,
                        'photo_url' => $data['photo_url']
                    ]);
                }
            } else {
                Log::info('No photos uploaded');
            }

            Log::info('Calling Supabase createReport...');
            $result = $this->supabase->createReport($data);
            
            Log::info('Supabase createReport result:', ['result' => $result]);

            if ($result) {
                Log::info('Report created successfully:', ['report_id' => $result['id'] ?? 'N/A']);
                Session::forget('report_data');

                // Check if request is AJAX/API
                if ($request->header('X-Requested-With') == 'XMLHttpRequest') {
                    return response()->json([
                        'success' => true,
                        'data' => $result,
                        'message' => 'Report created successfully'
                    ], 201);
                }

                return redirect()->route('reports.index')
                    ->with('success', 'Laporan berhasil dibuat! Tim kami akan segera menindaklanjutinya.');
            } else {
                Log::error('Supabase createReport returned false/null');
                Log::error('Last data sent to Supabase:', $data);

                try {
                    $client = new \GuzzleHttp\Client([
                        'base_uri' => config('supabase.url') . '/rest/v1/',
                        'headers' => [
                            'apikey' => config('supabase.key'),
                            'Authorization' => 'Bearer ' . config('supabase.key'),
                            'Content-Type' => 'application/json',
                            'Prefer' => 'return=representation'
                        ]
                    ]);

                    $response = $client->post('reports', ['json' => $data]);
                    $debugResult = json_decode($response->getBody(), true);
                    Log::info('Direct Guzzle result:', $debugResult);

                } catch (\Exception $debugE) {
                    Log::error('Direct Guzzle error:', [
                        'message' => $debugE->getMessage(),
                        'trace' => $debugE->getTraceAsString()
                    ]);
                }

                if ($request->header('X-Requested-With') == 'XMLHttpRequest') {
                    return response()->json(['error' => 'Failed to create report'], 500);
                }

                return back()->with('error', 'Gagal menyimpan laporan ke database. Silakan coba lagi.')
                    ->withInput();
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            Log::error('Error in store method:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Request $request, $id)
    {
        $report = $this->supabase->getReportById($id);

        if (!$report) {
            if ($request->header('X-Requested-With') == 'XMLHttpRequest') {
                return response()->json(['error' => 'Report not found'], 404);
            }
            abort(404, 'Laporan tidak ditemukan');
        }

        $userId = Session::get('user.id');
        if ($report['user_id'] != $userId) {
            if ($request->header('X-Requested-With') == 'XMLHttpRequest') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            abort(403, 'Anda tidak memiliki akses ke laporan ini');
        }

        // Check if request is AJAX/API
        if ($request->header('X-Requested-With') == 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'data' => $report,
                'message' => 'Report retrieved successfully'
            ]);
        }

        return view('reports.show', compact('report'));
    }

    public function edit($id)
    {
        $report = $this->supabase->getReportById($id);
        
        if (!$report) {
            abort(404);
        }

        $userId = Session::get('user.id');
        if ($report['user_id'] != $userId) {
            abort(403);
        }

        $facilities = $this->supabase->getFacilities();
        return view('reports.edit', compact('report', 'facilities'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'description' => 'required|min:10',
                'status' => 'required|in:Dikirim,Diproses,Selesai,Ditolak',
                'new_photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ]);

            
            $report = $this->supabase->getReportById($id);
            $userId = Session::get('user.id');
            
            if (!$report || $report['user_id'] != $userId) {
                abort(404);
            }

            
            $existingPhotos = [];
            if (!empty($report['photo_urls'])) {
                $existingPhotos = json_decode($report['photo_urls'], true);
            } elseif (!empty($report['photo_url'])) {
                $existingPhotos = [$report['photo_url']];
            }

            
            if (!empty($request->deleted_photos)) {
                $deletedPhotos = json_decode($request->deleted_photos, true);
                if (is_array($deletedPhotos)) {
                    
                    $existingPhotos = array_diff($existingPhotos, $deletedPhotos);
                    
                    
                    foreach ($deletedPhotos as $photoPath) {
                        $fullPath = public_path(str_replace('/', '\\', $photoPath));
                        if (file_exists($fullPath)) {
                            unlink($fullPath);
                        }
                    }
                }
            }

            
            $newPhotoPaths = [];
            if ($request->hasFile('new_photos')) {
                foreach ($request->file('new_photos') as $index => $photo) {
                    
                    $filename = time() . '_edit_' . $index . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    
                    
                    $uploadPath = public_path('uploads/reports');
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    
                    
                    $photo->move($uploadPath, $filename);
                    
                    
                    $newPhotoPaths[] = '/uploads/reports/' . $filename;
                }
            }

            
            $allPhotos = array_merge($existingPhotos, $newPhotoPaths);
            
            
            $data = [
                'description' => $request->description,
                'status' => $request->status,
                'updated_at' => Carbon::now()->toIso8601String(),
            ];

            
            if (!empty($allPhotos)) {
                $data['photo_urls'] = json_encode($allPhotos);
                $data['photo_url'] = $allPhotos[0]; 
            } else {
                $data['photo_urls'] = null;
                $data['photo_url'] = null;
            }

            Log::info('Updating report photos', [
                'report_id' => $id,
                'existing_count' => count($existingPhotos),
                'new_count' => count($newPhotoPaths),
                'deleted_count' => !empty($deletedPhotos) ? count($deletedPhotos) : 0,
                'final_count' => count($allPhotos)
            ]);

            $result = $this->supabase->updateReport($id, $data);

            if ($result) {
                return redirect()->route('reports.show', $id)
                    ->with('success', 'Laporan berhasil diperbarui.');
            }

            return back()->with('error', 'Gagal memperbarui laporan.')
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error updating report:', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat memperbarui laporan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            
            $report = $this->supabase->getReportById($id);
            $userId = Session::get('user.id');
            
            if (!$report || $report['user_id'] != $userId) {
                abort(404);
            }

            $result = $this->supabase->deleteReport($id);

            if ($result) {
                return redirect()->route('reports.index')
                    ->with('success', 'Laporan berhasil dihapus.');
            }

            return back()->with('error', 'Gagal menghapus laporan.');
            
        } catch (\Exception $e) {
            Log::error('Error deleting report:', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat menghapus laporan.');
        }
    }
    
    /**
     * Debug method untuk testing Supabase connection
     */
    public function debugSupabase()
    {
        try {
            Log::info('=== DEBUG SUPABASE ===');
            
            
            $connectionTest = $this->supabase->testConnection();
            Log::info('Connection test:', ['result' => $connectionTest]);
            
            
            $testData = [
                'user_id' => 1,
                'title' => 'Debug Report ' . time(),
                'location' => 'Debug Location',
                'facility' => 'AC',
                'description' => 'Debug description for testing',
                'status' => 'Dikirim',
                'building_id' => 1,
                'room_id' => 1,
                'facility_id' => 1,
                'report_code' => 'DEBUG-' . time(),
                'created_at' => Carbon::now()->toIso8601String(),
                'updated_at' => Carbon::now()->toIso8601String()
            ];
            
            Log::info('Test data for Supabase:', $testData);
            
            $result = $this->supabase->createReport($testData);
            
            return response()->json([
                'connection_test' => $connectionTest ? 'OK' : 'FAILED',
                'create_report_test' => $result ? 'SUCCESS' : 'FAILED',
                'result_data' => $result,
                'test_data_sent' => $testData,
                'timestamp' => now()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Debug error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}