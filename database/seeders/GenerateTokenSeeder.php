<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenerateTokenSeeder extends Seeder
{
    public function run()
    {
        $processed = 0;
        
        $this->command->info("Starting token generation...");
        
        // Process dalam chunk untuk performa lebih baik
        DB::table('pemilih')
            ->where(function($query) {
                $query->whereNull('token')
                      ->orWhere('token', '')
                      ->orWhere('token', '0');
            })
            ->orderBy('id') // ← WAJIB ada orderBy saat pakai chunk
            ->chunk(100, function ($pemilihs) use (&$processed) {
                foreach ($pemilihs as $pemilih) {
                    $token = $this->generateUniqueToken();
                    
                    DB::table('pemilih')
                        ->where('id', $pemilih->id)
                        ->update(['token' => $token]);
                    
                    $processed++;
                    
                    if ($processed % 10 == 0) {
                        $this->command->info("Processed: {$processed}");
                    }
                }
            });
        
        $this->command->info("✓ Token generated successfully for {$processed} pemilih!");
    }

    private function generateUniqueToken()
    {
        do {
            $token = strtolower(Str::random(6));
            $exists = DB::table('pemilih')->where('token', $token)->exists();
        } while ($exists);

        return $token;
    }
}