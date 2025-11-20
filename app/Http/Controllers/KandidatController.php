<?php
namespace App\Http\Controllers;

use App\Models\Kandidat;

class KandidatController extends Controller
{
    public function index()
    {
        $items = Kandidat::select('id','nama','nomor_urut','visi','misi','foto_url')->orderBy('nomor_urut')->get();
        return response()->json([
            'status' => 'success',
            'data' => $items
        ]);
    }
}
