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
            $userId = $request->current_user_id;
            $reports = $this->supabase->getAllReports($userId);
            return $this->success($reports, 'Data laporan berhasil diambil', 200);
        } catch (\Exception $e) {
            return $this->error('Gagal mengambil data: ' . $e->getMessage(), 500);
        }
    }

    public function show(Request $request, $id): JsonResponse
    {
        try {
            // 1. Ambil data laporan dari Supabase
            $report = $this->supabase->getReportById($id);
            // 2. Cek barangnya ada gak?
            if (!$report) {
                return $this->error('Laporan tidak ditemukan', 404);
            }
            // 3. Langsung kasih datanya
            return $this->success($report, 'Detail laporan ditemukan', 200);
        } catch (\Exception $e) {
            return $this->error('Error: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            // 1. Validasi Input (Standar)
            $validated = $request->validate([
                'building_id' => 'required|numeric',
                'room_id'     => 'required|numeric',
                'facility_id' => 'required|numeric',
                'description' => 'required|min:10|string',
                'photos'      => 'nullable|array',
                'photos.*'    => 'nullable|image|max:2048',
            ]);

            // 2. Ambil User ID dari Middleware
            $userId = $request->current_user_id;
            if (!$userId) {
                return $this->error('Unauthorized: User ID missing', 401);
            }

            // 3. Cari Data Metadata (Gedung, Ruang, Fasilitas)
            $building = collect($this->supabase->getBuildings())->firstWhere('id', $request->building_id);
            $room     = collect($this->supabase->getRooms($request->building_id))->firstWhere('id', $request->room_id);
            $facility = collect($this->supabase->getFacilities())->firstWhere('id', $request->facility_id);

            // Cek kelengkapan data (Fail Fast)
            if (!$building || !$room || !$facility) {
                return $this->error('Data gedung, ruangan, atau fasilitas tidak valid', 404);
            }

            // 4. Handle Upload Foto (Kalau ada)
            $photoPaths = [];
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    // Nama file unik: time_random.ext
                    $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    $photo->move(public_path('uploads/reports'), $filename);
                    $photoPaths[] = '/uploads/reports/' . $filename;
                }
            }

            // 5. Susun Data untuk Supabase
            $reportData = [
                'user_id'     => (int) $userId,
                'title'       => "Kerusakan {$facility['name']} - {$room['name']}",
                'location'    => "{$building['name']}, {$room['name']}",
                'facility'    => $facility['name'],
                'description' => $request->description,
                'status'      => 'Dikirim',
                'building_id' => (int) $request->building_id,
                'room_id'     => (int) $request->room_id,
                'facility_id' => (int) $request->facility_id,
                'report_code' => 'LPR-' . strtoupper(uniqid()),
                'created_at'  => Carbon::now()->toIso8601String(),
                'updated_at'  => Carbon::now()->toIso8601String(),
                'photo_urls'  => !empty($photoPaths) ? json_encode($photoPaths) : null,
                'photo_url'   => !empty($photoPaths) ? $photoPaths[0] : null,
            ];

            // 6. Kirim ke Supabase
            $result = $this->supabase->createReport($reportData);

            if ($result) {
                return $this->success($result, 'Laporan berhasil dibuat', 201);
            }

            return $this->error('Gagal menyimpan laporan ke database', 500);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validasi gagal', 422, $e->errors());
        } catch (\Exception $e) {
            Log::error('API Store Error: ' . $e->getMessage());
            return $this->error('Terjadi kesalahan server: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            // 1. Cek Laporan Ada & Punya Siapa
            $report = $this->supabase->getReportById($id);
            if (!$report) {
                return $this->error('Laporan tidak ditemukan', 404);
            }

            $userId = $request->current_user_id;
            if ($report['user_id'] != $userId) {
                return $this->error('Unauthorized: Anda tidak berhak mengedit laporan ini', 403);
            }

            // 2. Siapkan Data Update
            $updateData = [];

            // Update Deskripsi (Kalau dikirim)
            if ($request->has('description')) {
                $updateData['description'] = $request->description;
            }
            // Update Status
            if ($request->has('status')) {
                $updateData['status'] = $request->status;
            }

            // Update Foto (Logic sama kayak Store)
            if ($request->hasFile('photos')) {
                $photoPaths = [];
                foreach ($request->file('photos') as $photo) {
                    $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    $photo->move(public_path('uploads/reports'), $filename);
                    $photoPaths[] = '/uploads/reports/' . $filename;
                }

                if (!empty($photoPaths)) {
                    $updateData['photo_urls'] = json_encode($photoPaths);
                    $updateData['photo_url'] = $photoPaths[0];
                }
            }

            $updateData['updated_at'] = Carbon::now()->toIso8601String();

            // 3. Kirim ke Supabase
            $result = $this->supabase->updateReport($id, $updateData);

            if ($result) {
                return $this->success($result, 'Laporan berhasil diupdate', 200);
            }

            return $this->error('Gagal update ke database', 500);
        } catch (\Exception $e) {
            return $this->error('Error: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $report = $this->supabase->getReportById($id);

            if (!$report) {
                return $this->error('Laporan tidak ditemukan', 404);
            }

            $userId = $request->current_user_id;
            if ($report['user_id'] != $userId) {
                return $this->error('Unauthorized: Dilarang menghapus laporan orang lain', 403);
            }

            $result = $this->supabase->deleteReport($id);

            if ($result) {
                return $this->success(null, 'Laporan berhasil dihapus', 200);
            } else {
                return $this->error('Gagal menghapus laporan', 500);
            }
        } catch (\Exception $e) {
            return $this->error('Error: ' . $e->getMessage(), 500);
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
