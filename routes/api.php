<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KandidatController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ResultsController;

Route::prefix('auth')->group(function () {
    Route::post('/admin/login', [AuthController::class, 'loginAdmin']);
    Route::post('/super-admin/login', [AuthController::class, 'loginSuperAdmin']);
    Route::post('/pemilih/login', [AuthController::class, 'loginPemilih']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    // Kandidat listing (pemilih & admin)
    Route::get('/kandidat', [KandidatController::class, 'index']);

    // Voting (pemilih)
    Route::post('/vote', [VoteController::class, 'vote']);
    Route::get('/vote/status', [VoteController::class, 'status']);

    // Results (admin & super-admin)
    Route::get('/results/summary', [ResultsController::class, 'summary']);
    Route::get('/results/voters', [ResultsController::class, 'voters']);
});
