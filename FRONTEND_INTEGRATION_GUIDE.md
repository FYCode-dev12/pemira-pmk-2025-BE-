# 🚀 PEMIRA PMK 2025 - Setup Frontend Integration

## ✅ Backend sudah dikonfigurasi!

Backend Laravel sudah siap menerima request dari frontend dengan:

-   ✅ CORS configured untuk port 5173, 5174, 3000
-   ✅ Sanctum middleware untuk API authentication
-   ✅ Routes API sudah terdaftar

---

## 📋 Langkah Setup Frontend

### 1. Navigate ke Folder Frontend

```bash
cd ../pemira-pmk-2025-FE-
```

### 2. Install Dependencies

```bash
npm install axios react-router-dom
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

### 3. Copy Files dari Backend ke Frontend

Copy semua file dari folder `frontend-integration/` ke project React Anda:

```bash
# Dari folder backend
cd /home/fycode/Documents/pemira-pmk-2025/pemira-pmk-2025-BE-

# Copy ke frontend (sesuaikan struktur folder Anda)
cp frontend-integration/.env.example ../pemira-pmk-2025-FE-/.env
cp -r frontend-integration/services ../pemira-pmk-2025-FE-/src/
cp -r frontend-integration/contexts ../pemira-pmk-2025-FE-/src/
cp -r frontend-integration/hooks ../pemira-pmk-2025-FE-/src/
cp -r frontend-integration/pages ../pemira-pmk-2025-FE-/src/
cp frontend-integration/App.jsx ../pemira-pmk-2025-FE-/src/
cp frontend-integration/main.jsx ../pemira-pmk-2025-FE-/src/
cp frontend-integration/index.css ../pemira-pmk-2025-FE-/src/
cp frontend-integration/tailwind.config.js ../pemira-pmk-2025-FE-/
cp frontend-integration/postcss.config.js ../pemira-pmk-2025-FE-/
```

**ATAU** jika Anda sudah punya struktur sendiri, copy manual file-file berikut:

#### Required Files:

-   `services/api.js` → API configuration & endpoints
-   `contexts/AuthContext.jsx` → Authentication state management
-   `hooks/useAuth.js` → Auth custom hook
-   `pages/AdminLogin.jsx` → Admin login page
-   `pages/PemilihLogin.jsx` → Pemilih login page
-   `pages/Vote.jsx` → Voting page
-   `pages/Results.jsx` → Results page (admin only)
-   `App.jsx` → Main app with routing
-   `.env` → Environment variables

### 4. Update File Struktur React Anda

Sesuaikan struktur folder React Anda:

```
src/
├── services/
│   └── api.js
├── contexts/
│   └── AuthContext.jsx
├── hooks/
│   └── useAuth.js
├── pages/
│   ├── AdminLogin.jsx
│   ├── PemilihLogin.jsx
│   ├── Vote.jsx
│   └── Results.jsx
├── App.jsx
├── main.jsx
└── index.css
```

### 5. Configure Environment

Edit file `.env`:

```env
VITE_API_URL=http://127.0.0.1:8000
VITE_API_BASE_URL=http://127.0.0.1:8000/api
```

### 6. Update Tailwind Config (jika belum ada)

File `tailwind.config.js`:

```javascript
/** @type {import('tailwindcss').Config} */
export default {
    content: ["./index.html", "./src/**/*.{js,ts,jsx,tsx}"],
    theme: {
        extend: {},
    },
    plugins: [],
};
```

File `src/index.css`:

```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

---

## 🎯 Testing Integration

### Terminal 1 - Start Backend

```bash
cd /home/fycode/Documents/pemira-pmk-2025/pemira-pmk-2025-BE-
php artisan serve
```

### Terminal 2 - Start Frontend

```bash
cd /home/fycode/Documents/pemira-pmk-2025/pemira-pmk-2025-FE-
npm run dev
```

### Test Flow:

1. Buka browser: `http://localhost:5173`
2. Test login pemilih:
    - NIM: `119380073`
    - Token: `yhwcjk`
3. Test admin login:
    - Username: `admin` / Password: `Admin123!`
    - Username: `superadmin` / Password: `SuperAdmin123!`

---

## 📁 File Structure Overview

### Backend (Laravel)

```
pemira-pmk-2025-BE-/
├── routes/api.php              → API endpoints
├── app/Http/Controllers/       → Controllers
├── bootstrap/app.php           → CORS & Sanctum config
├── config/cors.php             → CORS settings
├── config/sanctum.php          → Sanctum settings
└── frontend-integration/       → Files to copy to React
```

### Frontend (React)

```
pemira-pmk-2025-FE-/
├── .env                        → Environment variables
├── src/
│   ├── services/api.js         → Axios & API calls
│   ├── contexts/AuthContext.jsx → Auth state
│   ├── hooks/useAuth.js        → Auth hook
│   ├── pages/
│   │   ├── AdminLogin.jsx      → Admin login
│   │   ├── PemilihLogin.jsx    → Pemilih login
│   │   ├── Vote.jsx            → Voting interface
│   │   └── Results.jsx         → Results dashboard
│   ├── App.jsx                 → Main app & routing
│   └── main.jsx                → Entry point
└── package.json
```

---

## 🔑 API Endpoints Available

### Authentication

-   `POST /api/auth/admin/login`
-   `POST /api/auth/super-admin/login`
-   `POST /api/auth/pemilih/login`
-   `POST /api/auth/logout`

### Kandidat

-   `GET /api/kandidat`

### Voting

-   `POST /api/vote`
-   `GET /api/vote/status`

### Results

-   `GET /api/results/summary`

---

## 🎨 Features Implemented

### Frontend Features:

✅ Admin & Pemilih separate login pages
✅ Token-based authentication
✅ Protected routes (vote & results)
✅ Real-time results with auto-refresh
✅ Vote status checking (sudah memilih/belum)
✅ Responsive design with Tailwind CSS
✅ Error handling & loading states
✅ Auto logout on 401

### Backend Features:

✅ CORS configured for frontend
✅ Sanctum authentication
✅ Role-based access control
✅ API endpoints ready

---

## 🔧 Troubleshooting

### CORS Error

**Problem**: `Access-Control-Allow-Origin` error
**Solution**: Pastikan backend sudah running dan CORS sudah configured (sudah done!)

### 401 Unauthorized

**Problem**: Request ditolak
**Solution**:

1. Login terlebih dahulu
2. Token otomatis tersimpan di localStorage
3. Periksa token di browser DevTools → Application → Local Storage

### Module not found

**Problem**: Import error
**Solution**: Install dependencies:

```bash
npm install axios react-router-dom
npm install -D tailwindcss postcss autoprefixer
```

### Port already in use

**Problem**: Port 5173 sudah digunakan
**Solution**: Kill process atau gunakan port lain:

```bash
npm run dev -- --port 5174
```

---

## 📝 Next Steps

1. ✅ Copy files ke frontend project
2. ✅ Install dependencies
3. ✅ Update .env
4. ✅ Test login flow
5. ⏳ Tambahkan data kandidat (backend)
6. ⏳ Customize UI sesuai branding PMK
7. ⏳ Add loading animations
8. ⏳ Deploy to production

---

## 💡 Tips

-   **Development**: Gunakan `npm run dev` untuk hot reload
-   **Production**: Build dengan `npm run build`
-   **Token Management**: Token disimpan di localStorage, auto-attached ke setiap request
-   **Auto Refresh**: Results page refresh setiap 5 detik
-   **One Time Vote**: Pemilih hanya bisa vote sekali

---

**Generated**: November 21, 2025
**Status**: Backend ✅ | Frontend Files ✅ | Integration Ready 🚀
