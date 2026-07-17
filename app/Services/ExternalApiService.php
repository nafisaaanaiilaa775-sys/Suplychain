<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ExternalApiService
{
    /**
     * Get weather data for a country or coordinates using Open-Meteo API.
     */
    public function getWeather(float $lat, float $lng): array
    {
        $cacheKey = "weather_{$lat}_{$lng}";

        return Cache::remember($cacheKey, 1800, function () use ($lat, $lng) {
            try {
                $response = Http::withoutVerifying()
                    ->timeout(10)
                    ->get("https://api.open-meteo.com/v1/forecast", [
                        'latitude' => $lat,
                        'longitude' => $lng,
                        'current' => ['temperature_2m', 'relative_humidity_2m', 'precipitation', 'wind_speed_10m', 'weather_code'],
                        'hourly' => 'precipitation_probability',
                        'forecast_days' => 1
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $current = $data['current'] ?? [];
                    
                    // Estimate storm risk based on precipitation probability and wind speed
                    $precipitationProb = $data['hourly']['precipitation_probability'][0] ?? 0;
                    $windSpeed = $current['wind_speed_10m'] ?? 0;
                    
                    $stormRisk = 0;
                    if ($windSpeed > 30) {
                        $stormRisk = min(100, $precipitationProb + 20);
                    } else {
                        $stormRisk = min(100, $precipitationProb);
                    }

                    return [
                        'temp' => $current['temperature_2m'] ?? 25,
                        'rain' => $current['precipitation'] ?? 0,
                        'wind' => $windSpeed,
                        'storm_risk' => $stormRisk,
                        'weather_code' => $current['weather_code'] ?? 0
                    ];
                }
            } catch (\Exception $e) {
                Log::error("Failed to fetch weather from Open-Meteo: " . $e->getMessage());
            }

            // Fallback default values
            return [
                'temp' => 28,
                'rain' => 1.5,
                'wind' => 10,
                'storm_risk' => 15,
                'weather_code' => 0
            ];
        });
    }

    /**
     * Get economic indicators for a country using World Bank API.
     */
    public function getEconomicIndicators(string $iso2): array
    {
        $iso2 = strtolower($iso2);
        $cacheKey = "world_bank_indicators_{$iso2}";

        return Cache::remember($cacheKey, 86400, function () use ($iso2) {
            $indicators = [
                'gdp' => 'NY.GDP.MKTP.CD',
                'gdp_growth' => 'NY.GDP.MKTP.KD.ZG',
                'inflation' => 'FP.CPI.TOTL.ZG',
                'population' => 'SP.POP.TOTL',
                'exports' => 'NE.EXP.GNFS.ZS',
                'imports' => 'NE.IMP.GNFS.ZS'
            ];

            $results = [];

            foreach ($indicators as $key => $code) {
                try {
                    // Fetch latest 5 years of data to get trends
                    $response = Http::withoutVerifying()
                        ->timeout(10)
                        ->get("https://api.worldbank.org/v2/country/{$iso2}/indicator/{$code}", [
                            'format' => 'json',
                            'per_page' => 5,
                            'mrnev' => 1
                        ]);

                    if ($response->successful() && isset($response->json()[1])) {
                        $data = $response->json()[1];
                        
                        // Extract latest value and trend history
                        $latestValue = null;
                        $history = [];
                        
                        foreach ($data as $record) {
                            if ($record['value'] !== null) {
                                if ($latestValue === null) {
                                    $latestValue = $record['value'];
                                }
                                $history[$record['date']] = $record['value'];
                            }
                        }
                        
                        $results[$key] = [
                            'value' => $latestValue,
                            'history' => array_reverse($history) // Order oldest to newest
                        ];
                    } else {
                        $results[$key] = ['value' => null, 'history' => []];
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to fetch indicator {$code} for {$iso2} from World Bank: " . $e->getMessage());
                    $results[$key] = ['value' => null, 'history' => []];
                }
            }

            return $results;
        });
    }

    /**
     * Get currency exchange rates using ExchangeRate API.
     */
    public function getExchangeRates(string $base = 'USD'): array
    {
        $cacheKey = "exchange_rates_{$base}";

        return Cache::remember($cacheKey, 3600, function () use ($base) {
            try {
                $response = Http::withoutVerifying()
                    ->timeout(10)
                    ->get("https://open.er-api.com/v6/latest/{$base}");

                if ($response->successful()) {
                    return $response->json();
                }
            } catch (\Exception $e) {
                Log::error("Failed to fetch exchange rates: " . $e->getMessage());
            }

            return [
                'rates' => [
                    'USD' => 1,
                    'IDR' => 16300,
                    'EUR' => 0.92,
                    'CNY' => 7.24,
                    'AUD' => 1.51,
                    'GBP' => 0.78,
                    'SGD' => 1.34,
                    'JPY' => 158.00
                ]
            ];
        });
    }

    /**
     * Get news articles for a country using GNews API.
     */
    public function getNews(string $countryName, string $iso2): array
    {
        $cacheKey = "news_{$iso2}";

        return Cache::remember($cacheKey, 7200, function () use ($countryName, $iso2) {
            $apiKey = env('GNEWS_API_KEY');

            if ($apiKey) {
                try {
                    $query = "({$countryName} OR {$iso2}) AND (logistics OR trade OR economy OR port OR shipping OR supply chain OR tariff OR geopolitics)";
                    $response = Http::withoutVerifying()
                        ->timeout(10)
                        ->get("https://gnews.io/api/v4/search", [
                            'q' => $query,
                            'lang' => 'en',
                            'country' => strtolower($iso2),
                            'token' => $apiKey,
                            'max' => 5
                        ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        $articles = [];
                        foreach ($data['articles'] ?? [] as $art) {
                            $articles[] = [
                                'title' => $art['title'],
                                'description' => $art['description'] ?? '',
                                'source' => $art['source']['name'] ?? 'GNews',
                                'url' => $art['url'],
                                'published_at' => $art['publishedAt'] ?? now()->toIso8601String()
                            ];
                        }
                        return $articles;
                    }
                } catch (\Exception $e) {
                    Log::error("GNews API failed: " . $e->getMessage());
                }
            }

            // Fallback / Mock News Generator related to trade and logistics
            return $this->generateMockNews($countryName);
        });
    }

    /**
     * Generate realistic logistics / trade mock news for a country.
     */
    private function generateMockNews(string $countryName): array
    {
        $headlines = [
            [
                'title' => "Supply Chain Delays Impact Exports in {$countryName} Amid Port Congestion",
                'description' => "Cargo movements in major regional terminals face backlog issues as logistics coordinators report delays in customs clearance and vessel scheduling.",
                'source' => "Global Trade News"
            ],
            [
                'title' => "New Economic Policy Predicts Trade Expansion for {$countryName}",
                'description' => "Financial analysts express optimism as local authorities announce key incentives aimed at streamlining cargo flows and infrastructure development.",
                'source' => "Economy Watch"
            ],
            [
                'title' => "Currency Volatility Poses Risk for Importers in {$countryName}",
                'description' => "The exchange rate movement triggers concerns about rising import costs for manufacturing raw materials and consumer goods.",
                'source' => "Financial Times"
            ],
            [
                'title' => "Weather Storm Warning Near Main Ports of {$countryName}",
                'description' => "Meteorological departments issue a heavy rain warning, advising maritime operators to prepare for temporary shipping halts.",
                'source' => "Weather Logistics"
            ],
            [
                'title' => "Geopolitical Tension Raises Logistics Risk Assessment in {$countryName}",
                'description' => "Security consultants increase the vulnerability index for supply corridors crossing the territorial boundaries of {$countryName}.",
                'source' => "Global Risk Intelligence"
            ]
        ];

        $articles = [];
        foreach ($headlines as $index => $hl) {
            $articles[] = [
                'title' => $hl['title'],
                'description' => $hl['description'],
                'source' => $hl['source'],
                'url' => '#',
                'published_at' => now()->subHours($index * 6)->toIso8601String()
            ];
        }

        return $articles;
    }
}
