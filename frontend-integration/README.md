# Frontend Integration Files
# Copy these files to your React Vite project: ../pemira-pmk-2025-FE-

## 📁 Directory Structure
```
pemira-pmk-2025-FE-/
├── .env
├── .env.example
├── src/
│   ├── services/
│   │   └── api.js
│   ├── hooks/
│   │   └── useAuth.js
│   ├── contexts/
│   │   └── AuthContext.jsx
│   ├── pages/
│   │   ├── Login.jsx
│   │   ├── AdminLogin.jsx
│   │   ├── PemilihLogin.jsx
│   │   ├── Kandidat.jsx
│   │   ├── Vote.jsx
│   │   └── Results.jsx
│   └── App.jsx
```

## 🚀 Setup Instructions

### 1. Install Dependencies
```bash
cd ../pemira-pmk-2025-FE-
npm install axios react-router-dom
```

### 2. Copy Files
Copy all files from `frontend-integration/` to your React project

### 3. Update .env
Create `.env` file in your React project root:
```env
VITE_API_URL=http://127.0.0.1:8000
VITE_API_BASE_URL=http://127.0.0.1:8000/api
```

### 4. Start Development
```bash
# Terminal 1 - Backend
cd pemira-pmk-2025-BE-
php artisan serve

# Terminal 2 - Frontend
cd pemira-pmk-2025-FE-
npm run dev
```

## 📝 Files Overview

### Core Files
- **services/api.js** - Axios instance & API calls
- **contexts/AuthContext.jsx** - Authentication state management
- **hooks/useAuth.js** - Custom hook for auth

### Pages
- **Login.jsx** - Main login page (router)
- **AdminLogin.jsx** - Admin/Super Admin login
- **PemilihLogin.jsx** - Pemilih login with NIM & token
- **Kandidat.jsx** - Display kandidat list
- **Vote.jsx** - Voting form
- **Results.jsx** - Real-time results (admin only)

## 🔑 Features
- ✅ Axios interceptors for token management
- ✅ Protected routes
- ✅ Auto logout on 401
- ✅ Role-based access control
- ✅ Error handling
- ✅ Loading states

## 📖 Usage Examples

See individual files for detailed implementation.

---

**Generated**: November 21, 2025
**Backend**: Laravel 11 + Sanctum
**Frontend**: React 18 + Vite
