<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SupabaseService
{
    protected $client;
    protected $headers;
    protected $url;

    public function __construct()
    {
        $this->url = config('supabase.url');
        $this->headers = [
            'apikey' => config('supabase.key'),
            'Authorization' => 'Bearer ' . config('supabase.key'),
            'Content-Type' => 'application/json',
            'Prefer' => 'return=minimal'
        ];

        $this->client = new Client([
            'base_uri' => $this->url . '/rest/v1/',
            'headers' => $this->headers,
            'timeout' => 30,
        ]);
    }

    public function login($full_name, $nim)
    {
        try {
            // Debug log
            \Log::info('Login attempt', ['full_name' => $full_name, 'nim' => $nim]);
            
            // Query untuk mencari user dengan full_name DAN nim
            $response = $this->client->get('users', [
                'query' => [
                    'full_name' => 'eq.' . $full_name,
                    'nim' => 'eq.' . $nim,
                    'select' => 'id,full_name,nim,full_name,program_studi',
                    'limit' => 1
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            
            \Log::info('Login response data', ['data' => $data]);
            
            if (!empty($data) && is_array($data)) {
                return $data[0];
            }
            
            return null;
        } catch (\Exception $e) {
            \Log::error('Supabase login error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    public function getAllReports($userId = null)
    {
        try {
            $query = [
                'select' => '*,users(full_name)',
                'order' => 'created_at.desc'
            ];
            
            if ($userId) {
                $query['user_id'] = 'eq.' . $userId;
            }

            $response = $this->client->get('reports', [
                'query' => $query
            ]);

            $data = json_decode($response->getBody(), true);
            return is_array($data) ? $data : [];
        } catch (\Exception $e) {
            Log::error('Supabase get reports error: ' . $e->getMessage());
            return [];
        }
    }
    
    public function createReport($data)
    {
        try {
            Log::info('Creating report in Supabase', ['data' => $data]);
            
            $response = $this->client->post('reports', [
                'json' => $data,
                'headers' => [
                    ...$this->headers,
                    'Prefer' => 'return=representation'
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            
            Log::info('Supabase response', [
                'status' => $response->getStatusCode(),
                'body' => $result
            ]);
            
            return !empty($result) ? $result[0] : null;
        } catch (\Exception $e) {
            Log::error('Supabase create report error: ' . $e->getMessage(), [
                'response' => isset($response) ? $response->getBody()->getContents() : 'No response',
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    public function updateReport($id, $data)
    {
        try {
            $response = $this->client->patch("reports?id=eq.{$id}", [
                'json' => $data,
                'headers' => [
                    ...$this->headers,
                    'Prefer' => 'return=representation'
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            return !empty($result) ? $result[0] : null;
        } catch (\Exception $e) {
            Log::error('Supabase update report error: ' . $e->getMessage());
            return null;
        }
    }

    public function deleteReport($id)
    {
        try {
            $response = $this->client->delete("reports?id=eq.{$id}", [
                'headers' => [
                    ...$this->headers,
                    'Prefer' => 'return=minimal'
                ]
            ]);

            return $response->getStatusCode() === 204;
        } catch (\Exception $e) {
            Log::error('Supabase delete report error: ' . $e->getMessage());
            return false;
        }
    }

    public function getReport($id)
    {
        try {
            $response = $this->client->get('reports', [
                'query' => [
                    'id' => 'eq.' . $id,
                    'select' => '*,users(full_name)'
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            return !empty($data) ? $data[0] : null;
        } catch (\Exception $e) {
            Log::error('Supabase get report error: ' . $e->getMessage());
            return null;
        }
    }

    public function getBuildings()
    {
        return [
            ['id' => 1, 'name' => 'Gedung Rektorat', 'address' => 'Jl. Pendidikan No. 1, Kampus A'],
            ['id' => 2, 'name' => 'Gedung Fakultas Teknik', 'address' => 'Jl. Teknologi No. 5, Kampus B'],
            ['id' => 3, 'name' => 'Gedung Perpustakaan Pusat', 'address' => 'Jl. Ilmu Pengetahuan No. 10, Kampus A'],
            ['id' => 4, 'name' => 'Gedung Auditorium', 'address' => 'Jl. Utama Boulevard, Kampus C'],
            ['id' => 5, 'name' => 'Gedung A', 'address' => 'Jl. Utama Kampus'],
            ['id' => 6, 'name' => 'Gedung B', 'address' => 'Jl. Utama Kampus'],
            ['id' => 7, 'name' => 'Gedung C', 'address' => 'Jl. Utama Kampus'],
            ['id' => 8, 'name' => 'Gedung Utama', 'address' => 'Jl. Utama Kampus'],
            ['id' => 9, 'name' => 'Gedung D', 'address' => 'Jl. Utama Kampus'],
        ];
    }

    public function getRooms($buildingId)
    {
        $rooms = [
            1 => [
                ['id' => 1, 'name' => 'Ruang Rapat Cendana', 'floor' => 'Lantai 5', 'building' => 'Gedung A'],
                ['id' => 2, 'name' => 'Ruang Direksi', 'floor' => 'Lantai 10', 'building' => 'Gedung A'],
            ],
            2 => [
                ['id' => 3, 'name' => 'Laboratorium Inovasi', 'floor' => 'Lantai 3', 'building' => 'Gedung B'],
            ],
            3 => [
                ['id' => 4, 'name' => 'Perpustakaan Sentral', 'floor' => 'Lantai 2', 'building' => 'Gedung C'],
            ],
            4 => [
                ['id' => 5, 'name' => 'Auditorium Merdeka', 'floor' => 'Lantai 1', 'building' => 'Gedung Utama'],
                ['id' => 6, 'name' => 'Lobi Utama', 'floor' => 'Lantai 1', 'building' => 'Gedung Utama'],
            ],
            5 => [
                ['id' => 7, 'name' => 'Ruang 201', 'floor' => 'Lantai 2', 'building' => 'Gedung A'],
                ['id' => 8, 'name' => 'Ruang 301', 'floor' => 'Lantai 3', 'building' => 'Gedung A'],
            ],
            6 => [
                ['id' => 9, 'name' => 'Laboratorium Komputer', 'floor' => 'Lantai 1', 'building' => 'Gedung B'],
                ['id' => 10, 'name' => 'Ruang Kelas B-101', 'floor' => 'Lantai 1', 'building' => 'Gedung B'],
            ],
            7 => [
                ['id' => 11, 'name' => 'Perpustakaan Lantai 2', 'floor' => 'Lantai 2', 'building' => 'Gedung C'],
            ],
            8 => [
                ['id' => 12, 'name' => 'Auditorium Merdeka', 'floor' => 'Lantai 1', 'building' => 'Gedung Utama'],
                ['id' => 13, 'name' => 'Lobi Utama', 'floor' => 'Lantai 1', 'building' => 'Gedung Utama'],
                ['id' => 14, 'name' => 'Ruang Server', 'floor' => 'Basement', 'building' => 'Gedung Utama'],
            ],
            9 => [
                ['id' => 15, 'name' => 'Kantin Karyawan', 'floor' => 'Lantai Dasar', 'building' => 'Gedung D'],
            ],
        ];

        return $rooms[$buildingId] ?? [];
    }

    public function getFacilities()
    {
        return [
            ['id' => 1, 'name' => 'Toilet'],
            ['id' => 2, 'name' => 'AC', 'subtypes' => ['AC Split', 'AC Central', 'AC Portable']],
            ['id' => 3, 'name' => 'Proyektor'],
            ['id' => 4, 'name' => 'Meja & Kursi'],
            ['id' => 5, 'name' => 'Pintu'],
            ['id' => 6, 'name' => 'Lampu'],
            ['id' => 7, 'name' => 'Wi-Fi'],
            ['id' => 8, 'name' => 'Lainnya'],
        ];
    }

    public function getStats($userId)
    {
        try {
            // Get current date for this month
            $currentMonth = date('Y-m');
            
            // Get total reports this month
            $response = $this->client->get('reports', [
                'query' => [
                    'user_id' => 'eq.' . $userId,
                    'created_at' => 'gte.' . $currentMonth . '-01T00:00:00Z',
                    'created_at' => 'lte.' . $currentMonth . '-31T23:59:59Z',
                    'select' => 'id'
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            $totalThisMonth = is_array($data) ? count($data) : 0;

            // Get completed reports
            $response = $this->client->get('reports', [
                'query' => [
                    'user_id' => 'eq.' . $userId,
                    'status' => 'eq.Selesai',
                    'select' => 'id'
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            $completed = is_array($data) ? count($data) : 0;

            // Get pending reports
            $response = $this->client->get('reports', [
                'query' => [
                    'user_id' => 'eq.' . $userId,
                    'or' => '(status.eq.Dikirim,status.eq.Diproses)',
                    'select' => 'id'
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            $pending = is_array($data) ? count($data) : 0;

            return [
                'total_this_month' => $totalThisMonth,
                'completed' => $completed,
                'pending' => $pending
            ];
        } catch (\Exception $e) {
            Log::error('Supabase get stats error: ' . $e->getMessage());
            return [
                'total_this_month' => 0,
                'completed' => 0,
                'pending' => 0
            ];
        }
    }

    public function getReportById($id)
    {
        return $this->getReport($id);
    }

    public function searchRooms($query, $buildingId = null)
    {
        $allRooms = [];
        $buildings = $buildingId ? 
            [collect($this->getBuildings())->where('id', $buildingId)->first()] : 
            $this->getBuildings();

        foreach ($buildings as $building) {
            $rooms = $this->getRooms($building['id']);
            foreach ($rooms as $room) {
                $room['building_id'] = $building['id'];
                $room['building_name'] = $building['name'];
                $allRooms[] = $room;
            }
        }

        if (empty($query)) {
            return $allRooms;
        }

        return array_filter($allRooms, function($room) use ($query) {
            return stripos($room['name'], $query) !== false || 
                   stripos($room['floor'], $query) !== false;
        });
    }

    public function testConnection()
    {
        try {
            $response = $this->client->get('users', [
                'query' => [
                    'limit' => 1,
                    'select' => 'id'
                ]
            ]);
            
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            Log::error('Supabase connection test failed: ' . $e->getMessage());
            return false;
        }
    }
}