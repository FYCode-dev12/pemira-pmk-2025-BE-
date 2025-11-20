<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Pemilih extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'pemilih';
    public $timestamps = false; // only created_at fixed

    protected $fillable = [
        'nim','nama','fakultas','program_studi','token','sudah_memilih','waktu_memilih'
    ];

    protected $casts = [
        'sudah_memilih' => 'boolean',
        'waktu_memilih' => 'datetime'
    ];
}