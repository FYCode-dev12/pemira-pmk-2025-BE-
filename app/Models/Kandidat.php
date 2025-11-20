<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kandidat extends Model
{
    use HasFactory;

    protected $table = 'kandidat';
    public $timestamps = false; // migration only has created_at

    protected $fillable = [
        'nama','nomor_urut','visi','misi','foto_url'
    ];
}
