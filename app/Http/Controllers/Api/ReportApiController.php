<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\SupabaseService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReportApiController extends ApiController
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    /**
     * GET /api/reports
     * Get all reports with optional filtering
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // API routes are disabled - use web routes at /reports
            Log::info('API - Reports endpoint disabled');

            return $this->error('Not found', 404);

        } catch (\Exception $e) {
            Log::error('API - Get reports error: ' . $e->getMessage());
            return $this->error('Failed to retrieve reports', 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $report = $this->supabase->getReportById($id);
            
            if (!$report) {
                return $this->error('Report not found', 404);
            }

            $user = Session::get('user');
            if (!$user || $report['user_id'] != $user['id']) {
                return $this->error('Unauthorized', 403);
            }

            Log::info('API - Get report', ['report_id' => $id]);

            return $this->success($report, 'Report retrieved successfully', 200);

        } catch (\Exception $e) {
            Log::error('API - Get report error: ' . $e->getMessage());
            return $this->error('Failed to retrieve report', 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            Log::info('API - Create report started');

            $validated = $request->validate([
                'building_id' => 'required|numeric',
                'room_id' => 'required|numeric',
                'facility_id' => 'required|numeric',
                'description' => 'required|min:10|string',
                'photos' => 'nullable|array',
                'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $userId = $request->user()->id ?? Session::get('user')['id'] ?? null;
            
            if (!$userId) {
                return $this->error('Unauthorized', 401);
            }

            $building = collect($this->supabase->getBuildings())
                ->where('id', $request->building_id)
                ->first();
            
            if (!$building) {
                return $this->error('Building not found', 404);
            }

            $room = collect($this->supabase->getRooms($request->building_id))
                ->where('id', $request->room_id)
                ->first();
            
            if (!$room) {
                return $this->error('Room not found', 404);
            }

            $facility = collect($this->supabase->getFacilities())
                ->where('id', $request->facility_id)
                ->first();
            
            if (!$facility) {
                return $this->error('Facility not found', 404);
            }

            $reportCode = 'LAPORAN-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

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

            $photoPaths = [];
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $index => $photo) {
                    $filename = time() . '_' . $index . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    
                    $uploadPath = public_path('uploads/reports');
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    
                    $photo->move($uploadPath, $filename);
                    $photoPaths[] = '/uploads/reports/' . $filename;
                }
                
                if (!empty($photoPaths)) {
                    $data['photo_urls'] = json_encode($photoPaths);
                    $data['photo_url'] = $photoPaths[0];
                }
            }

            $result = $this->supabase->createReport($data);

            if ($result) {
                Log::info('API - Report created successfully', ['report_id' => $result['id'] ?? 'N/A']);
                
                return $this->success(
                    $result,
                    'Report created successfully',
                    201
                );
            } else {
                Log::error('API - Failed to create report');
                return $this->error('Failed to create report', 500);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('API - Validation error:', ['errors' => $e->errors()]);
            return $this->error('Validation failed', 422, $e->errors());
            
        } catch (\Exception $e) {
            Log::error('API - Create report error: ' . $e->getMessage());
            return $this->error('Server error: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            Log::info('API - Update report started', ['report_id' => $id]);

            $report = $this->supabase->getReportById($id);
            
            if (!$report) {
                return $this->error('Report not found', 404);
            }

            $user = Session::get('user');
            if (!$user || $report['user_id'] != $user['id']) {
                return $this->error('Unauthorized', 403);
            }

            $updateData = [];
            
            if ($request->has('description')) {
                $updateData['description'] = $request->description;
            }
            
            if ($request->has('status')) {
                $updateData['status'] = $request->status;
            }


            if ($request->hasFile('photos')) {
                $photoPaths = [];
                
                if (!empty($report['photo_urls'])) {
                    $photoPaths = json_decode($report['photo_urls'], true) ?? [];
                }

                foreach ($request->file('photos') as $index => $photo) {
                    $filename = time() . '_' . $index . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    
                    $uploadPath = public_path('uploads/reports');
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    
                    $photo->move($uploadPath, $filename);
                    $photoPaths[] = '/uploads/reports/' . $filename;
                }
                
                if (!empty($photoPaths)) {
                    $updateData['photo_urls'] = json_encode($photoPaths);
                    $updateData['photo_url'] = $photoPaths[0];
                }
            }

            $updateData['updated_at'] = Carbon::now()->toIso8601String();

            $result = $this->supabase->updateReport($id, $updateData);

            if ($result) {
                Log::info('API - Report updated successfully', ['report_id' => $id]);
                
                return $this->success(
                    $result,
                    'Report updated successfully',
                    200
                );
            } else {
                Log::error('API - Failed to update report');
                return $this->error('Failed to update report', 500);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('API - Validation error:', ['errors' => $e->errors()]);
            return $this->error('Validation failed', 422, $e->errors());
            
        } catch (\Exception $e) {
            Log::error('API - Update report error: ' . $e->getMessage());
            return $this->error('Server error: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            Log::info('API - Delete report started', ['report_id' => $id]);

            $report = $this->supabase->getReportById($id);
            
            if (!$report) {
                return $this->error('Report not found', 404);
            }

            $user = Session::get('user');
            if (!$user || $report['user_id'] != $user['id']) {
                return $this->error('Unauthorized', 403);
            }

            $result = $this->supabase->deleteReport($id);

            if ($result) {
                Log::info('API - Report deleted successfully', ['report_id' => $id]);
                
                return $this->success(
                    null,
                    'Report deleted successfully',
                    200
                );
            } else {
                Log::error('API - Failed to delete report');
                return $this->error('Failed to delete report', 500);
            }

        } catch (\Exception $e) {
            Log::error('API - Delete report error: ' . $e->getMessage());
            return $this->error('Server error: ' . $e->getMessage(), 500);
        }
    }

    public function editData($id): JsonResponse
    {
        try {
            Log::info('API - Get report edit data', ['report_id' => $id]);

            $report = $this->supabase->getReportById($id);
            
            if (!$report) {
                return $this->error('Report not found', 404);
            }

            $user = Session::get('user');
            if (!$user || $report['user_id'] != $user['id']) {
                return $this->error('Unauthorized', 403);
            }

            $buildings = $this->supabase->getBuildings();
            $rooms = $this->supabase->getRooms($report['building_id']);
            $facilities = $this->supabase->getFacilities();

            return $this->success([
                'report' => $report,
                'buildings' => $buildings,
                'rooms' => $rooms,
                'facilities' => $facilities
            ], 'Edit data retrieved successfully', 200);

        } catch (\Exception $e) {
            Log::error('API - Get edit data error: ' . $e->getMessage());
            return $this->error('Server error: ' . $e->getMessage(), 500);
        }
    }

    public function getBuildings(): JsonResponse
    {
        try {
            $buildings = $this->supabase->getBuildings();
            return $this->success($buildings, 'Buildings retrieved successfully', 200);
        } catch (\Exception $e) {
            Log::error('API - Get buildings error: ' . $e->getMessage());
            return $this->error('Failed to retrieve buildings', 500);
        }
    }

    public function getRooms($building_id): JsonResponse
    {
        try {
            $rooms = $this->supabase->getRooms($building_id);
            return $this->success($rooms, 'Rooms retrieved successfully', 200);
        } catch (\Exception $e) {
            Log::error('API - Get rooms error: ' . $e->getMessage());
            return $this->error('Failed to retrieve rooms', 500);
        }
    }

    public function getFacilities(): JsonResponse
    {
        try {
            $facilities = $this->supabase->getFacilities();
            return $this->success($facilities, 'Facilities retrieved successfully', 200);
        } catch (\Exception $e) {
            Log::error('API - Get facilities error: ' . $e->getMessage());
            return $this->error('Failed to retrieve facilities', 500);
        }
    }
}
