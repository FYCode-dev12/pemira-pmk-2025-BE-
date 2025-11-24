<?php
namespace App\Http\Controllers;

use App\Models\Pemilih;
use App\Models\Kandidat;
use App\Models\Suara;
use App\Models\VotingSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VoteController extends Controller
{
    public function status(Request $request)
    {
        $user = $request->user();
        if (!$user || !($user instanceof Pemilih)) {
            return response()->json(['status'=>'error','message'=>'Unauthorized'], 401);
        }

        $suara = Suara::where('pemilih_id', $user->id)->first();
        return response()->json([
            'status' => 'success',
            'sudah_memilih' => (bool)$user->sudah_memilih,
            'pilihan' => $suara ? [
                'kandidat_id' => $suara->kandidat_id,
                'nama_kandidat' => $suara->kandidat->nama,
                'nomor_urut' => $suara->kandidat->nomor_urut,
                'waktu_vote' => $suara->waktu_vote
            ] : null
        ]);
    }

    public function vote(Request $request)
    {
        // Check if voting is open
        if (!VotingSetting::isVotingOpen()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Voting sedang ditutup. Silakan tunggu hingga voting dibuka kembali.'
            ], 403);
        }

        $data = $request->validate([
            'kandidat_id' => 'required|integer|exists:kandidat,id'
        ]);

        $pemilih = $request->user();
        if (!$pemilih || !($pemilih instanceof Pemilih)) {
            return response()->json(['status'=>'error','message'=>'Unauthorized'], 401);
        }

        if ($pemilih->sudah_memilih) {
            throw ValidationException::withMessages([
                'kandidat_id' => ['Anda sudah melakukan voting']
            ]);
        }

        $kandidat = Kandidat::find($data['kandidat_id']);

        DB::transaction(function () use ($pemilih, $kandidat) {
            // Double check no existing vote
            if (Suara::where('pemilih_id', $pemilih->id)->exists()) {
                throw ValidationException::withMessages([
                    'kandidat_id' => ['Vote sudah tercatat']
                ]);
            }

            Suara::create([
                'pemilih_id' => $pemilih->id,
                'nim' => $pemilih->nim,
                'nama' => $pemilih->nama,
                'fakultas' => $pemilih->fakultas,
                'program_studi' => $pemilih->program_studi,
                'kandidat_id' => $kandidat->id,
            ]);

            $pemilih->sudah_memilih = true;
            $pemilih->waktu_memilih = now();
            $pemilih->save();
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Vote berhasil disimpan',
        ]);
    }
}
