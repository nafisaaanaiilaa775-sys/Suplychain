<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = [
        'name',
        'iso2',
        'iso3',
        'latitude',
        'longitude',
        'currency_code',
        'currency_name',
        'region',
        'capital',
        'gdp',
        'gdp_growth',
        'inflation',
        'population',
        'exports',
        'imports'
    ];

    public function ports(): HasMany
    {
        return $this->hasMany(Port::class);
    }

    public function riskScores(): HasMany
    {
        return $this->hasMany(RiskScore::class);
    }

    public function newsCaches(): HasMany
    {
        return $this->hasMany(NewsCache::class);
    }
}
