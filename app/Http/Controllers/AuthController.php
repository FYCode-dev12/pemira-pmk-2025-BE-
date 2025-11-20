<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Pemilih;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function loginAdmin(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $admin = Admin::where('username', $data['username'])
            ->where('role', 'admin')
            ->first();

        if (!$admin || !$admin->verifyPassword($data['password'])) {
            throw ValidationException::withMessages([
                'username' => ['Kredensial admin tidak valid']
            ]);
        }

        $token = $admin->createToken('admin-token', ['admin'])->plainTextToken;

        return response()->json([
            'status' => 'success',
            'role' => $admin->role,
            'token' => $token,
            'admin' => [
                'id' => $admin->id,
                'username' => $admin->username,
                'role' => $admin->role
            ]
        ]);
    }

    public function loginSuperAdmin(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $admin = Admin::where('username', $data['username'])
            ->where('role', 'super-admin')
            ->first();

        if (!$admin || !$admin->verifyPassword($data['password'])) {
            throw ValidationException::withMessages([
                'username' => ['Kredensial super admin tidak valid']
            ]);
        }

        $token = $admin->createToken('super-admin-token', ['super-admin'])->plainTextToken;

        return response()->json([
            'status' => 'success',
            'role' => $admin->role,
            'token' => $token,
            'admin' => [
                'id' => $admin->id,
                'username' => $admin->username,
                'role' => $admin->role
            ]
        ]);
    }

    public function loginPemilih(Request $request)
    {
        $data = $request->validate([
            'nim' => 'required|string',
            'token' => 'required|string'
        ]);

        $pemilih = Pemilih::where('nim', $data['nim'])
            ->where('token', $data['token'])
            ->first();

        if (!$pemilih) {
            throw ValidationException::withMessages([
                'nim' => ['Token atau NIM tidak valid']
            ]);
        }

        $token = $pemilih->createToken('pemilih-token', ['pemilih'])->plainTextToken;

        return response()->json([
            'status' => 'success',
            'role' => 'pemilih',
            'token' => $token,
            'pemilih' => [
                'id' => $pemilih->id,
                'nim' => $pemilih->nim,
                'nama' => $pemilih->nama,
                'fakultas' => $pemilih->fakultas,
                'program_studi' => $pemilih->program_studi,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil'
        ]);
    }
}
