<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        Admin::firstOrCreate(
            ['username' => 'superadmin'],
            [
                'password' => Hash::make('SuperAdmin123!'),
                'role' => 'super-admin'
            ]
        );

        // Regular Admin
        Admin::firstOrCreate(
            ['username' => 'admin'],
            [
                'password' => Hash::make('Admin123!'),
                'role' => 'admin'
            ]
        );
    }
}
