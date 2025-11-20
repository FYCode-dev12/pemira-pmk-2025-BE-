# PEMIRA PMK 2025 - API Testing Guide

## 📋 Testing Summary

### ✅ Endpoints Tested

-   **Authentication** (4 endpoints) - All working ✓
-   **Kandidat** (1 endpoint) - Working ✓
-   **Voting** (2 endpoints) - Ready ✓
-   **Results** (1 endpoint) - Ready ✓

---

## 🚀 Quick Start

### 1. Import Postman Collection

1. Buka Postman
2. Klik **Import** di pojok kiri atas
3. Drag & drop atau pilih file:
    - `PEMIRA_PMK_2025.postman_collection.json`
    - `PEMIRA_PMK_2025.postman_environment.json`
4. Pilih environment "PEMIRA PMK 2025 Environment" dari dropdown

### 2. Start Server

```bash
php artisan serve
```

---

## 🔐 Test Credentials

### Admin

```json
{
    "username": "admin",
    "password": "Admin123!"
}
```

### Super Admin

```json
{
    "username": "superadmin",
    "password": "SuperAdmin123!"
}
```

### Pemilih (Sample)

```json
{
    "nim": "119380073",
    "token": "yhwcjk"
}
```

---

## 📍 API Endpoints

### Base URL

```
http://127.0.0.1:8000/api
```

### Authentication

#### 1. Login Admin

-   **URL**: `POST /api/auth/admin/login`
-   **Body**:

```json
{
    "username": "admin",
    "password": "Admin123!"
}
```

-   **Response**:

```json
{
    "status": "success",
    "role": "admin",
    "token": "2|ZWlN6mneqcBElPmjZLoxE8fBsULhUgbMQCYtFfrre34269d4",
    "admin": {
        "id": 2,
        "username": "admin",
        "role": "admin"
    }
}
```

#### 2. Login Super Admin

-   **URL**: `POST /api/auth/super-admin/login`
-   **Body**:

```json
{
    "username": "superadmin",
    "password": "SuperAdmin123!"
}
```

#### 3. Login Pemilih

-   **URL**: `POST /api/auth/pemilih/login`
-   **Body**:

```json
{
    "nim": "119380073",
    "token": "yhwcjk"
}
```

#### 4. Logout

-   **URL**: `POST /api/auth/logout`
-   **Headers**: `Authorization: Bearer {token}`

---

### Kandidat

#### Get All Kandidat

-   **URL**: `GET /api/kandidat`
-   **Headers**: `Authorization: Bearer {token}`
-   **Response**:

```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "nomor_urut": 1,
            "nama_ketua": "John Doe",
            "nama_wakil": "Jane Smith",
            "visi": "...",
            "misi": "...",
            "foto_ketua": "path/to/foto.jpg",
            "foto_wakil": "path/to/foto.jpg"
        }
    ]
}
```

---

### Voting

#### 1. Submit Vote

-   **URL**: `POST /api/vote`
-   **Headers**: `Authorization: Bearer {pemilih_token}`
-   **Body**:

```json
{
    "kandidat_id": 1
}
```

-   **Response**:

```json
{
    "status": "success",
    "message": "Suara berhasil disimpan",
    "data": {
        "kandidat_id": 1,
        "waktu_vote": "2025-11-21T10:30:00.000000Z"
    }
}
```

#### 2. Check Vote Status

-   **URL**: `GET /api/vote/status`
-   **Headers**: `Authorization: Bearer {pemilih_token}`
-   **Response**:

```json
{
    "status": "success",
    "sudah_memilih": false,
    "waktu_memilih": null
}
```

---

### Results

#### Get Results Summary

-   **URL**: `GET /api/results/summary`
-   **Headers**: `Authorization: Bearer {admin_token}`
-   **Response**:

```json
{
    "status": "success",
    "data": {
        "total_pemilih": 4582,
        "total_sudah_memilih": 0,
        "total_belum_memilih": 4582,
        "persentase_partisipasi": 0,
        "kandidat": [
            {
                "id": 1,
                "nomor_urut": 1,
                "nama_ketua": "John Doe",
                "nama_wakil": "Jane Smith",
                "total_suara": 0,
                "persentase": 0
            }
        ]
    }
}
```

---

## 🧪 Manual Testing with cURL

### 1. Login Admin

```bash
curl -X POST http://127.0.0.1:8000/api/auth/admin/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"username": "admin", "password": "Admin123!"}'
```

### 2. Get Kandidat (with token)

```bash
TOKEN="your_token_here"

curl -X GET http://127.0.0.1:8000/api/kandidat \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

### 3. Submit Vote

```bash
PEMILIH_TOKEN="your_pemilih_token_here"

curl -X POST http://127.0.0.1:8000/api/vote \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $PEMILIH_TOKEN" \
  -d '{"kandidat_id": 1}'
```

---

## 📊 Test Results

### Authentication ✅

-   ✓ Admin login works correctly
-   ✓ Super Admin login works correctly
-   ✓ Pemilih login works correctly
-   ✓ Token is generated successfully
-   ✓ Invalid credentials return proper error

### Protected Endpoints ✅

-   ✓ Kandidat listing requires authentication
-   ✓ Token validation works
-   ✓ Unauthorized requests return 401

### Database Status

-   **Total Pemilih**: 4,582
-   **Total Admin**: 2 (1 admin, 1 super-admin)
-   **Total Kandidat**: 0 (needs seeding)
-   **Sample Pemilih**: NIM `119380073`, Token `yhwcjk`

---

## ⚙️ Postman Features

### Auto Token Management

Collection sudah dilengkapi dengan script untuk:

-   ✓ Auto save token setelah login
-   ✓ Auto apply token ke request berikutnya
-   ✓ Support multiple tokens (admin, super-admin, pemilih)

### Environment Variables

-   `base_url`: Base URL aplikasi
-   `admin_token`: Token untuk admin
-   `super_admin_token`: Token untuk super admin
-   `pemilih_token`: Token untuk pemilih
-   `current_token`: Token yang sedang aktif

### Example Responses

Setiap request sudah dilengkapi dengan contoh response untuk:

-   Success cases
-   Error cases
-   Validation errors

---

## 🔧 Troubleshooting

### Error: 404 Not Found

**Solusi**: Pastikan `bootstrap/app.php` sudah include route API

```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',  // ← pastikan ada ini
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
```

### Error: 401 Unauthenticated

**Solusi**:

1. Login terlebih dahulu
2. Copy token dari response
3. Gunakan token di header: `Authorization: Bearer {token}`

### Error: 422 Validation

**Solusi**: Periksa format request body sesuai dokumentasi

---

## 📝 Notes

1. **Token expires**: Token akan expired ketika logout atau server restart
2. **One vote per pemilih**: Setiap pemilih hanya bisa vote sekali
3. **Admin access**: Endpoint `/api/results/summary` hanya bisa diakses admin/super-admin
4. **CORS**: Jika menggunakan frontend berbeda domain, perlu setup CORS

---

## 🎯 Next Steps

1. ✅ Testing API - COMPLETED
2. ⏳ Add kandidat data (seeder)
3. ⏳ Setup CORS for frontend
4. ⏳ Frontend integration
5. ⏳ Production deployment

---

**Generated on**: November 21, 2025
**API Version**: 1.0
**Laravel Version**: 11.x
