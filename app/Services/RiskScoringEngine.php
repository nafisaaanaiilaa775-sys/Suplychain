<?php

namespace App\Services;

use App\Models\PositiveWord;
use App\Models\NegativeWord;
use App\Models\Country;
use Illuminate\Support\Facades\Cache;

class RiskScoringEngine
{
    /**
     * Perform lexicon-based sentiment analysis on a text.
     * Returns positive count, negative count, and overall sentiment.
     */
    public function analyzeSentiment(string $text): array
    {
        // Tokenize text into words (alphanumeric and lowercase)
        $text = strtolower($text);
        preg_match_all('/\b\w+\b/', $text, $matches);
        $words = $matches[0] ?? [];

        if (empty($words)) {
            return ['positive' => 0, 'negative' => 0, 'sentiment' => 'Neutral'];
        }

        // Fetch lexicons (cached for performance)
        $positiveWords = Cache::remember('lexicon_positive_words', 3600, function () {
            return PositiveWord::pluck('word')->toArray();
        });

        $negativeWords = Cache::remember('lexicon_negative_words', 3600, function () {
            return NegativeWord::pluck('word')->toArray();
        });

        $positiveScore = 0;
        $negativeScore = 0;

        foreach ($words as $word) {
            if (in_array($word, $positiveWords)) {
                $positiveScore++;
            }
            if (in_array($word, $negativeWords)) {
                $negativeScore++;
            }
        }

        $sentiment = 'Neutral';
        if ($positiveScore > $negativeScore) {
            $sentiment = 'Positive';
        } elseif ($negativeScore > $positiveScore) {
            $sentiment = 'Negative';
        }

        return [
            'positive' => $positiveScore,
            'negative' => $negativeScore,
            'sentiment' => $sentiment
        ];
    }

    /**
     * Compute risk scores for a country based on weather, inflation, currency, and news sentiment.
     */
    public function calculateRisk(Country $country, array $weatherData, array $newsArticles, array $exchangeRates): array
    {
        // 1. Weather Risk (30% weight)
        // Calculated based on storm risk, wind speed, and rain
        $stormRisk = $weatherData['storm_risk'] ?? 0;
        $windSpeed = $weatherData['wind'] ?? 0;
        $rain = $weatherData['rain'] ?? 0;
        $weatherRisk = (int) min(100, $stormRisk * 0.8 + ($windSpeed * 0.5) + ($rain * 2));

        // 2. Inflation Risk (20% weight)
        // Target inflation is ~2%. Deviations (deflation or high inflation) increase risk
        $inflation = $country->inflation ?? 2.5; // Fallback to 2.5% if null
        $inflationRisk = 10;
        if ($inflation < 0) {
            $inflationRisk = (int) min(100, abs($inflation) * 15 + 20); // Deflation concerns
        } elseif ($inflation > 3) {
            $inflationRisk = (int) min(100, ($inflation - 3) * 12 + 15); // High inflation risk
        } else {
            $inflationRisk = (int) max(5, min(30, ($inflation) * 8)); // Stable range
        }

        // 3. Currency Volatility Risk (10% weight)
        // Base volatility on currency code stability
        $currencyCode = strtoupper($country->currency_code ?? 'USD');
        $currencyRisk = 15; // default stable
        if ($currencyCode === 'USD' || $currencyCode === 'EUR' || $currencyCode === 'GBP' || $currencyCode === 'CHF') {
            $currencyRisk = 10; // Very stable
        } elseif ($currencyCode === 'IDR' || $currencyCode === 'INR' || $currencyCode === 'BRL' || $currencyCode === 'TRY' || $currencyCode === 'ARS') {
            $currencyRisk = 45; // Higher volatility
        } elseif ($currencyCode === 'CNY' || $currencyCode === 'JPY' || $currencyCode === 'AUD' || $currencyCode === 'SGD') {
            $currencyRisk = 20; // Medium stability
        }

        // 4. News Sentiment Risk (40% weight)
        $totalPositive = 0;
        $totalNegative = 0;
        
        foreach ($newsArticles as $art) {
            $textToAnalyze = ($art['title'] ?? '') . ' ' . ($art['description'] ?? '');
            $sentimentResult = $this->analyzeSentiment($textToAnalyze);
            $totalPositive += $sentimentResult['positive'];
            $totalNegative += $sentimentResult['negative'];
        }

        $newsRisk = 30; // default neutral
        if (($totalPositive + $totalNegative) > 0) {
            $newsRisk = (int) (($totalNegative / ($totalPositive + $totalNegative)) * 100);
        }

        // Weighted overall risk score
        // Risk = Weather (30%) + Inflation (20%) + Currency (10%) + News Sentiment (40%)
        $overallScore = (int) (
            ($weatherRisk * 0.3) +
            ($inflationRisk * 0.2) +
            ($currencyRisk * 0.1) +
            ($newsRisk * 0.4)
        );

        // Classify Risk Level
        $badge = 'Low Risk';
        $badgeClass = 'risk-low';
        $progressColor = '#10b981'; // Green

        if ($overallScore > 65) {
            $badge = 'High Risk';
            $badgeClass = 'risk-high';
            $progressColor = '#ef4444'; // Red
        } elseif ($overallScore > 35) {
            $badge = 'Medium Risk';
            $badgeClass = 'risk-medium';
            $progressColor = '#f59e0b'; // Orange
        }

        return [
            'score' => $overallScore,
            'badge' => $badge,
            'badgeClass' => $badgeClass,
            'progressColor' => $progressColor,
            'breakdown' => [
                'weather' => $weatherRisk,
                'inflation' => $inflationRisk,
                'currency' => $currencyRisk,
                'news' => $newsRisk
            ]
        ];
    }
}
