# API Voting Status - Frontend Integration Guide

## Overview

API untuk mengontrol status voting (buka/tutup) yang dapat diakses oleh admin/super-admin.

---

## Backend API Endpoints

### 1. Get Voting Status

**URL**: `GET /api/admin/voting-status`  
**Auth**: Required (Bearer Token - Admin/Super Admin)

**Response Success**:

```json
{
    "status": "success",
    "data": {
        "is_open": false,
        "message": "Voting sedang ditutup"
    }
}
```

### 2. Toggle Voting Status

**URL**: `POST /api/admin/voting-status`  
**Auth**: Required (Bearer Token - Admin/Super Admin)

**Request Body**:

```json
{
    "is_open": true // true = buka, false = tutup
}
```

**Response Success**:

```json
{
    "status": "success",
    "message": "Voting berhasil dibuka",
    "data": {
        "is_open": true
    }
}
```

---

## Frontend Integration

### Update API Service (`src/services/api.js`)

Tambahkan fungsi berikut ke file `api.js`:

```javascript
// Admin - Voting Control API
export const adminAPI = {
    // Get voting status
    getVotingStatus: async () => {
        return await api.get("/admin/voting-status");
    },

    // Toggle voting status
    toggleVotingStatus: async (isOpen) => {
        return await api.post("/admin/voting-status", {
            is_open: isOpen,
        });
    },
};
```

---

## React Component Example

### Admin Dashboard - Voting Control

```jsx
import React, { useState, useEffect } from "react";
import { adminAPI } from "../services/api";

const VotingControl = () => {
    const [isOpen, setIsOpen] = useState(false);
    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState("");

    useEffect(() => {
        fetchVotingStatus();
    }, []);

    const fetchVotingStatus = async () => {
        try {
            const response = await adminAPI.getVotingStatus();
            setIsOpen(response.data.data.is_open);
            setMessage(response.data.data.message);
        } catch (error) {
            console.error("Error fetching voting status:", error);
        }
    };

    const handleToggle = async () => {
        if (
            !window.confirm(
                `Apakah Anda yakin ingin ${
                    isOpen ? "menutup" : "membuka"
                } voting?`
            )
        ) {
            return;
        }

        setLoading(true);
        try {
            const response = await adminAPI.toggleVotingStatus(!isOpen);
            setIsOpen(response.data.data.is_open);
            setMessage(response.data.message);
            alert(response.data.message);
        } catch (error) {
            alert("Gagal mengubah status voting");
            console.error("Error toggling voting status:", error);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="bg-white p-6 rounded-lg shadow-md">
            <h2 className="text-xl font-bold mb-4">Kontrol Voting</h2>

            <div className="mb-4">
                <div
                    className={`inline-block px-4 py-2 rounded ${
                        isOpen
                            ? "bg-green-100 text-green-700"
                            : "bg-red-100 text-red-700"
                    }`}
                >
                    <span className="font-semibold">Status: </span>
                    {message}
                </div>
            </div>

            <button
                onClick={handleToggle}
                disabled={loading}
                className={`px-6 py-3 rounded font-semibold text-white ${
                    isOpen
                        ? "bg-red-500 hover:bg-red-600"
                        : "bg-green-500 hover:bg-green-600"
                } disabled:bg-gray-400`}
            >
                {loading
                    ? "Loading..."
                    : isOpen
                    ? "🔒 Tutup Voting"
                    : "🔓 Buka Voting"}
            </button>

            <div className="mt-4 p-3 bg-yellow-50 text-sm text-yellow-700 rounded">
                <strong>Info:</strong> Ketika voting ditutup, pemilih tidak akan
                bisa melakukan voting.
            </div>
        </div>
    );
};

export default VotingControl;
```

---

## Pemilih - Check Voting Status Before Voting

Update `Vote.jsx` untuk mengecek status voting:

```jsx
import React, { useState, useEffect } from "react";
import { kandidatAPI, voteAPI, adminAPI } from "../services/api";

const Vote = () => {
    const [votingOpen, setVotingOpen] = useState(false);
    const [kandidat, setKandidat] = useState([]);
    const [selectedKandidat, setSelectedKandidat] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetchData();
    }, []);

    const fetchData = async () => {
        try {
            // Check voting status
            const statusRes = await adminAPI.getVotingStatus();
            setVotingOpen(statusRes.data.data.is_open);

            // Get kandidat list
            const kandidatRes = await kandidatAPI.getAll();
            setKandidat(kandidatRes.data.data);

            setLoading(false);
        } catch (err) {
            console.error("Error fetching data:", err);
            setLoading(false);
        }
    };

    const handleVote = async () => {
        if (!votingOpen) {
            alert(
                "Voting sedang ditutup. Silakan tunggu hingga dibuka kembali."
            );
            return;
        }

        // ... existing vote logic
    };

    if (!votingOpen) {
        return (
            <div className="min-h-screen bg-gray-100 py-8">
                <div className="max-w-2xl mx-auto px-4">
                    <div className="bg-white p-8 rounded-lg shadow-md text-center">
                        <div className="text-red-500 text-6xl mb-4">🔒</div>
                        <h2 className="text-2xl font-bold mb-4">
                            Voting Sedang Ditutup
                        </h2>
                        <p className="text-gray-600">
                            Mohon tunggu hingga panitia membuka voting kembali.
                        </p>
                    </div>
                </div>
            </div>
        );
    }

    // ... rest of voting UI
};
```

---

## Database Structure

**Table**: `voting_settings`

| Column     | Type         | Description                   |
| ---------- | ------------ | ----------------------------- |
| id         | bigint       | Primary key                   |
| key        | varchar(255) | Setting key (e.g., 'is_open') |
| value      | text         | Setting value ('0' or '1')    |
| created_at | timestamp    | Created timestamp             |
| updated_at | timestamp    | Updated timestamp             |

---

## Testing

### Postman/cURL

**Get Status**:

```bash
curl -X GET http://127.0.0.1:8000/api/admin/voting-status \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Accept: application/json"
```

**Open Voting**:

```bash
curl -X POST http://127.0.0.1:8000/api/admin/voting-status \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"is_open": true}'
```

**Close Voting**:

```bash
curl -X POST http://127.0.0.1:8000/api/admin/voting-status \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"is_open": false}'
```

---

## Features

✅ Admin can open/close voting  
✅ Real-time status check  
✅ Prevents voting when closed  
✅ Returns clear error message (403) when pemilih tries to vote while closed  
✅ Persistent setting stored in database  
✅ Automatic validation

---

## Security Notes

-   Only admin/super-admin dapat mengakses endpoint ini
-   Requires valid Bearer token
-   Voting status checked sebelum menerima vote
-   Settings stored securely in database

---

**Files Modified**:

-   ✅ Migration: `2025_11_24_182324_create_voting_settings_table.php`
-   ✅ Model: `app/Models/VotingSetting.php`
-   ✅ Controller: `app/Http/Controllers/AdminController.php`
-   ✅ Routes: `routes/api.php`
-   ✅ VoteController: Updated to check voting status
-   ✅ Postman Collection: Updated with new endpoints

**Generated**: November 25, 2025
