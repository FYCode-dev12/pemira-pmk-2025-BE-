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

    public function voters(Request $request)
    {
        $user = $request->user();
        if (!$user || !($user instanceof Admin)) {
            return response()->json(['status'=>'error','message'=>'Unauthorized'], 401);
        }

        $query = DB::table('pemilih')
            ->select('id', 'nim', 'nama', 'fakultas', 'program_studi', 'sudah_memilih', 'token', 'waktu_memilih');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        if ($request->has('filter') && $request->filter !== 'all') {
            if ($request->filter === 'voted') {
                $query->where('sudah_memilih', true);
            } elseif ($request->filter === 'not-voted') {
                $query->where('sudah_memilih', false);
            }
        }

        $query->orderBy('id', 'asc');

        $perPage = $request->input('per_page', 50);
        $page = $request->input('page', 1);
        
        $total = $query->count();
        $voters = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

        return response()->json([
            'status' => 'success',
            'data' => $voters,
            'pagination' => [
                'current_page' => (int)$page,
                'per_page' => (int)$perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage),
                'from' => ($page - 1) * $perPage + 1,
                'to' => min($page * $perPage, $total)
            ]
        ]);
    }
}
