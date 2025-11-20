<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Kandidat;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ResultsController extends Controller
{
    public function summary(Request $request)
    {
        $user = $request->user();
        if (!$user || !($user instanceof Admin)) {
            return response()->json(['status'=>'error','message'=>'Unauthorized'], 401);
        }
        // Allow both admin and super-admin

        $rows = DB::table('suara')
            ->select('kandidat_id', DB::raw('COUNT(*) as total'))
            ->groupBy('kandidat_id')
            ->get();

        $kandidatMap = Kandidat::all()->keyBy('id');
        $data = [];
        foreach ($kandidatMap as $k) {
            $found = $rows->firstWhere('kandidat_id', $k->id);
            $data[] = [
                'kandidat_id' => $k->id,
                'nama' => $k->nama,
                'nomor_urut' => $k->nomor_urut,
                'total_suara' => $found ? (int)$found->total : 0
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'total_pemilih_sudah_memilih' => DB::table('pemilih')->where('sudah_memilih', true)->count(),
            'total_pemilih' => DB::table('pemilih')->count()
        ]);
    }
}
