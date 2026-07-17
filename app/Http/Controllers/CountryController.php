<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\RiskScore;
use App\Services\ExternalApiService;
use App\Services\RiskScoringEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    protected ExternalApiService $apiService;
    protected RiskScoringEngine $riskEngine;

    public function __construct(ExternalApiService $apiService, RiskScoringEngine $riskEngine)
    {
        $this->apiService = $apiService;
        $this->riskEngine = $riskEngine;
    }

    /**
     * Get list of all countries for dropdown selectors.
     */
    public function index(): JsonResponse
    {
        $countries = Country::select('id', 'name', 'iso2', 'iso3', 'currency_code', 'currency_name')
            ->orderBy('name', 'asc')
            ->get();
            
        return response()->json($countries);
    }

    /**
     * Get detailed information for a single country including indicators, weather, news, and risk score.
     */
    public function show(string $iso2): JsonResponse
    {
        $country = Country::where('iso2', strtolower($iso2))->first();

        if (!$country) {
            return response()->json(['message' => 'Country not found'], 404);
        }

        // 1. Fetch economic indicators from World Bank (cached inside service)
        $indicators = $this->apiService->getEconomicIndicators($country->iso2);
        
        // Update database with latest values if available
        $country->update([
            'gdp' => $indicators['gdp']['value'] ?? $country->gdp,
            'gdp_growth' => $indicators['gdp_growth']['value'] ?? $country->gdp_growth,
            'inflation' => $indicators['inflation']['value'] ?? $country->inflation,
            'population' => $indicators['population']['value'] ?? $country->population,
            'exports' => $indicators['exports']['value'] ?? $country->exports,
            'imports' => $indicators['imports']['value'] ?? $country->imports,
        ]);

        // 2. Fetch current weather for the capital/coordinates
        $lat = $country->latitude ?? 0;
        $lng = $country->longitude ?? 0;
        $weather = $this->apiService->getWeather($lat, $lng);

        // 3. Fetch trade/supply chain news
        $news = $this->apiService->getNews($country->name, $country->iso2);

        // 4. Fetch exchange rates
        $ratesData = $this->apiService->getExchangeRates('USD');
        $exchangeRates = $ratesData['rates'] ?? [];
        
        // Calculate currency rate relative to USD
        $rateToUsd = $exchangeRates[strtoupper($country->currency_code)] ?? null;

        // 5. Calculate Risk Score
        $risk = $this->riskEngine->calculateRisk($country, $weather, $news, $exchangeRates);

        // Save calculated risk to history database
        RiskScore::create([
            'country_id' => $country->id,
            'score' => $risk['score'],
            'weather_risk' => $risk['breakdown']['weather'],
            'inflation_risk' => $risk['breakdown']['inflation'],
            'currency_risk' => $risk['breakdown']['currency'],
            'news_risk' => $risk['breakdown']['news']
        ]);

        return response()->json([
            'country' => $country,
            'indicators' => $indicators,
            'weather' => $weather,
            'news' => $news,
            'currency' => [
                'code' => $country->currency_code,
                'name' => $country->currency_name,
                'rate_to_usd' => $rateToUsd
            ],
            'risk' => $risk
        ]);
    }

    /**
     * Get historical risk trends for a country.
     */
    public function getRiskHistory(string $iso2): JsonResponse
    {
        $country = Country::where('iso2', strtolower($iso2))->first();

        if (!$country) {
            return response()->json(['message' => 'Country not found'], 404);
        }

        // Return latest 10 risk scores
        $history = RiskScore::where('country_id', $country->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->reverse()
            ->values();

        return response()->json($history);
    }
}
