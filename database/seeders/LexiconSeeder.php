<?php

namespace Database\Seeders;

use App\Models\PositiveWord;
use App\Models\NegativeWord;
use Illuminate\Database\Seeder;

class LexiconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positives = [
            'growth', 'increase', 'profit', 'stable', 'improve', 'safe', 'secure', 'boom', 
            'recovery', 'gain', 'surplus', 'positive', 'expansion', 'success', 'benefit',
            'oportunity', 'smooth', 'efficient', 'resolved', 'strengthen', 'climb', 'rise',
            'tumbuh', 'meningkat', 'keuntungan', 'stabil', 'membaik', 'aman', 'lancar', 
            'efisien', 'sukses', ' surplus', 'naik', 'bagus', 'pulih', 'menguat'
        ];

        $negatives = [
            'war', 'crisis', 'inflation', 'delay', 'disaster', 'risk', 'danger', 'drop', 
            'loss', 'deficit', 'negative', 'decline', 'failure', 'threat', 'storm', 'flood',
            'strike', 'protest', 'bottleneck', 'congestion', 'blockage', 'sanction', 'conflict',
            'perang', 'krisis', 'inflasi', 'keterlambatan', 'bencana', 'risiko', 'bahaya', 
            'turun', 'kerugian', 'defisit', 'negatif', 'penurunan', 'kegagalan', 'ancaman', 
            'badai', 'banjir', 'mogok', 'kemacetan', 'blokade', 'sanksi', 'konflik', 'macet'
        ];

        foreach ($positives as $word) {
            PositiveWord::firstOrCreate(['word' => trim(strtolower($word))]);
        }

        foreach ($negatives as $word) {
            NegativeWord::firstOrCreate(['word' => trim(strtolower($word))]);
        }
    }
}
