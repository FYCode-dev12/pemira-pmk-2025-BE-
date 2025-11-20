<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class Admin extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'admin';
    public $timestamps = false; // migration only has created_at

    protected $fillable = [
        'username', 'password', 'role'
    ];

    protected $hidden = [
        'password'
    ];

    public function isSuper(): bool
    {
        return $this->role === 'super-admin';
    }

    public function verifyPassword(string $plain): bool
    {
        return Hash::check($plain, $this->password);
    }
}
