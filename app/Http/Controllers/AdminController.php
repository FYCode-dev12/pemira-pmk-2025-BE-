<?php

namespace App\Http\Controllers;

use App\Models\VotingSetting;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Get voting status
     */
    public function getVotingStatus()
    {
        $isOpen = VotingSetting::isVotingOpen();
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'is_open' => $isOpen,
                'message' => $isOpen ? 'Voting sedang dibuka' : 'Voting sedang ditutup'
            ]
        ]);
    }

    /**
     * Toggle voting status (open/close)
     */
    public function toggleVotingStatus(Request $request)
    {
        $data = $request->validate([
            'is_open' => 'required|boolean'
        ]);

        VotingSetting::set('is_open', $data['is_open'] ? '1' : '0');

        return response()->json([
            'status' => 'success',
            'message' => $data['is_open'] ? 'Voting berhasil dibuka' : 'Voting berhasil ditutup',
            'data' => [
                'is_open' => (bool) $data['is_open']
            ]
        ]);
    }
}
