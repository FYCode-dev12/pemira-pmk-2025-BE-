<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suara extends Model
{
    use HasFactory;

    protected $table = 'suara';
    public $timestamps = false; // menggunakan custom timestamp di kolom

    protected $fillable = [
        'pemilih_id','nim','nama','fakultas','program_studi','kandidat_id','waktu_vote'
    ];

    protected $casts = [
        'waktu_vote' => 'datetime'
    ];

    public function kandidat()
    {
        return $this->belongsTo(Kandidat::class, 'kandidat_id');
    }
}
