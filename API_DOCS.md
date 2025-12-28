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
Response Sukses (200): Copy bagian token dari response ini untuk digunakan pada request selanjutnya.

```json
{
    "success": true,
    "data": {
        "user": { ... },
        "token": "eyJpdiI6IlR..."  <-- COPY TOKEN INI
    },
    "message": "Login successful"
}
```
## 2.Cara Menggunakan Token
Setiap request ke endpoint di bawah ini WAJIB menyertakan Header berikut:

- Key: Authorization
- Value: Paste_Token_Panjang_Di_Sini

## 3. Laporan (Reports)
### A. Lihat Semua Laporan
Mengambil daftar laporan milik user yang sedang login.

- Method: `GET`
- Endpoint: `/reports`
- Headers: Authorization: `<token>`

Response: List data laporan.

### B. Buat Laporan Baru
- Method: `POST`
- Endpoint: `/reports`
- Headers: Authorization: `<token>`

##### Body (Form-Data / Multipart):
- building_id: (text, contoh: 1)
- room_id: (text, contoh: 1)
- facility_id: (text, contoh: 1)
- description: (text, minimal 10 karakter)
- photos[]: (File - Opsional)

## C. Lihat Detail Laporan berdasarkan id laporan
- Method: `GET`
- Endpoint: `/reports/{id}`
- Headers: `Authorization: <token>`
Contoh:` GET /reports/5`

## D. Update Laporan
- Method: `POST`
- Endpoint: `/reports/{id}`
- Headers: `Authorization: <token>`
  
#### Body (Form-Data):
- description: (Teks baru)
- status: (Status baru, misal: Selesai)
photos[]: (Upload foto baru jika ada)

## E. Hapus Laporan
- Method: `DELETE`
- Endpoint:` /reports/{id}`
- Headers: `Authorization: <token>`
  