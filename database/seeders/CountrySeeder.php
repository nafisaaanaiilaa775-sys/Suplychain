<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [];

        try {
            // Fetch from REST Countries API (mledoze GitHub mirror)
            $response = Http::withoutVerifying()->timeout(30)->get('https://raw.githubusercontent.com/mledoze/countries/master/dist/countries.json');
            
            if ($response->successful()) {
                $data = $response->json();
                
                foreach ($data as $item) {
                    $name = $item['name']['common'] ?? null;
                    $iso2 = $item['cca2'] ?? null;
                    $iso3 = $item['cca3'] ?? null;
                    
                    if (!$name || !$iso2) {
                        continue;
                    }

                    $lat = $item['latlng'][0] ?? null;
                    $lng = $item['latlng'][1] ?? null;
                    
                    $capital = isset($item['capital']) && is_array($item['capital']) 
                        ? implode(', ', $item['capital']) 
                        : null;
                        
                    $region = $item['region'] ?? null;
                    
                    $currencyCode = null;
                    $currencyName = null;
                    if (isset($item['currencies']) && is_array($item['currencies'])) {
                        $keys = array_keys($item['currencies']);
                        if (!empty($keys)) {
                            $currencyCode = $keys[0];
                            $currencyName = $item['currencies'][$currencyCode]['name'] ?? null;
                        }
                    }

                    // Default values for GDP, inflation, population
                    $population = $item['population'] ?? null;

                    $countries[] = [
                        'name' => $name,
                        'iso2' => strtolower($iso2),
                        'iso3' => strtolower($iso3),
                        'latitude' => $lat,
                        'longitude' => $lng,
                        'currency_code' => $currencyCode,
                        'currency_name' => $currencyName,
                        'region' => $region,
                        'capital' => $capital,
                        'population' => $population,
                        'gdp' => null,
                        'gdp_growth' => null,
                        'inflation' => null,
                        'exports' => null,
                        'imports' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to fetch countries from API: ' . $e->getMessage());
        }

        // If API fails or returns empty, fallback to a solid predefined list of countries
        if (empty($countries)) {
            $fallback = [
                ['name' => 'Indonesia', 'iso2' => 'id', 'iso3' => 'idn', 'latitude' => -0.789275, 'longitude' => 113.921327, 'currency_code' => 'IDR', 'currency_name' => 'Indonesian rupiah', 'region' => 'Asia', 'capital' => 'Jakarta', 'population' => 273523615],
                ['name' => 'Germany', 'iso2' => 'de', 'iso3' => 'deu', 'latitude' => 51.165691, 'longitude' => 10.451526, 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'region' => 'Europe', 'capital' => 'Berlin', 'population' => 83240525],
                ['name' => 'China', 'iso2' => 'cn', 'iso3' => 'chn', 'latitude' => 35.86166, 'longitude' => 104.195397, 'currency_code' => 'CNY', 'currency_name' => 'Renminbi', 'region' => 'Asia', 'capital' => 'Beijing', 'population' => 1411000000],
                ['name' => 'Australia', 'iso2' => 'au', 'iso3' => 'aus', 'latitude' => -25.274398, 'longitude' => 133.775136, 'currency_code' => 'AUD', 'currency_name' => 'Australian dollar', 'region' => 'Oceania', 'capital' => 'Canberra', 'population' => 25687041],
                ['name' => 'United States', 'iso2' => 'us', 'iso3' => 'usa', 'latitude' => 37.09024, 'longitude' => -95.712891, 'currency_code' => 'USD', 'currency_name' => 'United States dollar', 'region' => 'Americas', 'capital' => 'Washington, D.C.', 'population' => 331002651],
                ['name' => 'Japan', 'iso2' => 'jp', 'iso3' => 'jpn', 'latitude' => 36.204824, 'longitude' => 138.252924, 'currency_code' => 'JPY', 'currency_name' => 'Japanese yen', 'region' => 'Asia', 'capital' => 'Tokyo', 'population' => 125800000],
                ['name' => 'Singapore', 'iso2' => 'sg', 'iso3' => 'sgp', 'latitude' => 1.352083, 'longitude' => 103.819836, 'currency_code' => 'SGD', 'currency_name' => 'Singapore dollar', 'region' => 'Asia', 'capital' => 'Singapore', 'population' => 5685807],
                ['name' => 'United Kingdom', 'iso2' => 'gb', 'iso3' => 'gbr', 'latitude' => 55.378051, 'longitude' => -3.435973, 'currency_code' => 'GBP', 'currency_name' => 'British pound', 'region' => 'Europe', 'capital' => 'London', 'population' => 67081000],
            ];

            foreach ($fallback as $c) {
                $countries[] = array_merge($c, [
                    'gdp' => null,
                    'gdp_growth' => null,
                    'inflation' => null,
                    'exports' => null,
                    'imports' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Chunk insert to handle large amount of records safely in SQLite
        $chunks = array_chunk($countries, 50);
        foreach ($chunks as $chunk) {
            // Check for duplicates before insert if needed, but since it's fresh seed we just insert
            // Ensure we only insert unique iso2 keys
            foreach ($chunk as $item) {
                Country::updateOrCreate(
                    ['iso2' => $item['iso2']],
                    $item
                );
            }
        }
    }
}
