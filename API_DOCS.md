# Dokumentasi RESTful API - Lapor Fak
Dokumentasi ini menjelaskan cara menggunakan API untuk sistem pelaporan fasilitas kampus.

Base URL: http://localhost:8000/api

## 1. Authentication (Login)
Sebelum mengakses fitur laporan, user harus login untuk mendapatkan Token Authorization.
- Method: `POST`
- Endpoint: `/login`
### Body (JSON):
```json
{
    "full_name": "Nama Lengkap User",
    "nim": "NIM User"
}
```

 Response Sukses (200 OK):
```json
    {
        "success": true,
        "data": {
            "user": {
                "id": 1,
                "full_name": "John Doe",
                "nim": "12345678"
            },
            "token": "eyJpdiI6IlR..."
        },
        "message": "Login successful"
    }
```

Response Gagal (401 Unauthorized):

```json
    {
        "success": false,
        "message": "Unauthorized",
        "data": null
    }
```
Catatan: Simpan `token` yang dikembalikan. Semua request yang butuh otentikasi wajib menyertakan header:

- `Authorization: <token_anda>`
- `Accept: application/json`
 Contoh curl login:


## 2. Public Data (Dropdown Menu)
Endpoint ini digunakan untuk mengisi pilihan pada form pelaporan (tidak butuh login).

### Get List Gedung
- Method: `GET`
- Endpoint: `/reports/buildings`

Response:

```json
{
     "success": true,
    "data": [
        { "id": 1, "name": "Gedung Rektorat", "address": "..." },
        { "id": 2, "name": "Gedung Fakultas Teknik", "address": "..." }
    ]
}
```

### Get List Ruangan per Gedung
- Method: `GET`
- Endpoint: ` /reports/buildings/{building_id}/rooms`

Contoh: `GET /reports/buildings/1/rooms`

Response:

```json
{
    "success": true,
    "data": [
        { "id": 1, "name": "Ruang Rapat", "floor": "Lantai 1" }
    ]
}
```

### Get List Fasilitas
- Endpoint: `GET /reports/facilities`

Response:

```json
{
    "success": true,
    "data": [
        { "id": 1, "name": "AC" },
        { "id": 2, "name": "Proyektor" }
    ]
}
```

## 3. Manajemen Laporan (Reports)
PENTING: Semua request di bawah ini WAJIB menyertakan header:

- `Authorization: <token>`
- `Accept: application/json`

#### A. Lihat Semua Laporan
- Mengambil daftar laporan milik user yang sedang login.
- Endpoint: `GET /reports`

Response Sukses (200 OK):

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Kerusakan AC - Ruang Rapat",
            "status": "Dikirim",
            "created_at": "2023-10-27T10:00:00.000000Z"
        }
    ],
    "message": "Data laporan berhasil diambil"
}
```

#### B. Buat Laporan Baru
- Endpoint: `POST /reports`
- Tipe Body: `multipart/form-data`

Parameter:

- `building_id`: (text, Wajib) ID dari endpoint Get Buildings.
- `room_id`: (text, Wajib) ID dari endpoint Get Rooms.
- `facility_id`: (text, Wajib) ID dari endpoint Get Facilities.
- `description`: (text, Wajib) Minimal 10 karakter.
- `photos[]`: (File Image, Opsional) Bisa upload multiple file.

Response Sukses (201 Created):

```json
{
    "success": true,
    "data": {
        "id": 5,
        "report_code": "LPR-XY123",
        "title": "Kerusakan AC - Lab Komputer",
        "status": "Dikirim"
    },
    "message": "Laporan berhasil dibuat"
}
```

Response Error Validasi (422 Unprocessable Content):

```json
{
    "success": false,
    "message": "Validation failed",
    "data": {
        "description": ["The description must be at least 10 characters."]
    }
}
```



#### C. Lihat Detail Laporan
- Method: `GET`
- Endpoint: `/reports/{id}`

Contoh: `GET /reports/5`

Response Sukses: Mengembalikan object detail laporan lengkap beserta URL foto, contoh:

```json
{
    "success": true,
    "data": {
        "id": 5,
        "report_code": "LPR-XY123",
        "title": "Kerusakan AC - Lab Komputer",
        "description": "AC tidak dingin sejak kemarin.",
        "status": "Diproses",
        "building": {"id":1,"name":"Gedung Rektorat"},
        "room": {"id":2,"name":"Lab Komputer"},
        "facility": {"id":1,"name":"AC"},
        "photos": ["http://localhost:8000/uploads/reports/photo1.jpg"],
        "created_at": "2023-10-27T10:00:00.000000Z"
    },
    "message": "Data laporan berhasil diambil"
}
```

#### D. Update Laporan
- Mengupdate deskripsi, status, atau menambah foto.
- Endpoint: `POST /reports/{id}` (jika API menggunakan POST untuk update) atau `PATCH /reports/{id}` bila didukung.
- Tipe Body: `multipart/form-data`

Parameter (opsional):

- `description`: (text)
- `status`: (text) Contoh: `Selesai`, `Diproses`.
- `photos[]`: (File Image, Opsional)

Response Sukses (200 OK): Mengembalikan data laporan yang telah diperbarui.

#### E. Hapus Laporan
- Method: `DELETE`
- Endpoint: ` /reports/{id}`

Response Sukses (200 OK):

```json
{
    "success": true,
    "data": null,
    "message": "Laporan berhasil dihapus"
}
```

## 4. Kode Error Umum
- `401 Unauthorized`: Token salah, kadaluwarsa, atau tidak dikirim di Header.
- `403 Forbidden`: Mencoba mengedit/menghapus laporan milik orang lain.
- `404 Not Found`: Endpoint salah atau ID Laporan tidak ditemukan.
- `422 Unprocessable Content`: Data input tidak lengkap atau salah format.
- `500 Server Error`: Terjadi kesalahan pada server (Database down, dll).

