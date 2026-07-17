@extends('layouts.app')

@section('title', 'Country Comparison Engine')

@section('content')
<div class="container-fluid fade-in-up">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fs-3 fw-bold mb-1" style="font-family: 'Outfit';">Country Comparison Engine</h1>
            <p class="text-secondary">Bandingkan profil ekonomi, cuaca, dan tingkat risiko antara dua negara untuk mitigasi keputusan rantai pasok.</p>
        </div>
    </div>

    <!-- Country Selectors Bar -->
    <div class="glass-card p-4 mb-4">
        <div class="row align-items-center g-3">
            <div class="col-md-5">
                <label class="form-label text-secondary fs-7">Negara Pertama (A)</label>
                <select id="compare-a" class="form-select custom-input">
                    <option value="de" selected>🇩🇪 Jerman (Germany)</option>
                    <option value="id">🇮🇩 Indonesia</option>
                    <option value="cn">🇨🇳 China</option>
                    <option value="au">🇦🇺 Australia</option>
                </select>
            </div>
            
            <div class="col-md-2 text-center d-flex justify-content-center align-items-end" style="height: 100%;">
                <div class="rounded-circle bg-primary bg-opacity-20 border border-primary border-opacity-35 text-primary d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; margin-top: 28px;">
                    <i class="fa-solid fa-right-left fs-5"></i>
                </div>
            </div>

            <div class="col-md-5">
                <label class="form-label text-secondary fs-7">Negara Kedua (B)</label>
                <select id="compare-b" class="form-select custom-input">
                    <option value="au" selected>🇦🇺 Australia</option>
                    <option value="id">🇮🇩 Indonesia</option>
                    <option value="de">🇩🇪 Jerman (Germany)</option>
                    <option value="cn">🇨🇳 China</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Comparison Grid -->
    <div class="row g-4">
        <div class="col-12">
            <div class="glass-card p-4">
                <div class="table-responsive">
                    <table class="table custom-table text-center align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 35%;" class="fs-5 text-white" id="header-country-a">🇩🇪 Jerman</th>
                                <th style="width: 30%;" class="fs-6 text-secondary">Indikator Pembanding</th>
                                <th style="width: 35%;" class="fs-5 text-white" id="header-country-b">🇦🇺 Australia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Risk Score Row -->
                            <tr class="table-row-animate">
                                <td>
                                    <div class="d-flex flex-column align-items-center">
                                        <h3 class="fw-bold mb-1" id="val-a-risk-score">22</h3>
                                        <span class="badge risk-low px-3 py-1 rounded-pill" id="val-a-risk-badge">Low Risk</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-white uppercase fs-7">Skor Risiko Rantai Pasok</span>
                                    <small class="d-block text-secondary fs-8 mt-1">Lebih rendah = lebih aman</small>
                                </td>
                                <td>
                                    <div class="d-flex flex-column align-items-center">
                                        <h3 class="fw-bold mb-1" id="val-b-risk-score">68</h3>
                                        <span class="badge risk-high px-3 py-1 rounded-pill" id="val-b-risk-badge">High Risk</span>
                                    </div>
                                </td>
                            </tr>

                            <!-- GDP Row -->
                            <tr class="table-row-animate">
                                <td>
                                    <span class="fs-5 fw-semibold" id="val-a-gdp">$4.20 Triliun</span>
                                    <small class="d-block text-success" id="val-a-gdp-growth">+1.2%</small>
                                </td>
                                <td class="fw-bold text-secondary fs-7">GDP & Pertumbuhan</td>
                                <td>
                                    <span class="fs-5 fw-semibold" id="val-b-gdp">$1.62 Triliun</span>
                                    <small class="d-block text-success" id="val-b-gdp-growth">+1.9%</small>
                                </td>
                            </tr>

                            <!-- Inflation Row -->
                            <tr class="table-row-animate">
                                <td id="cell-a-inflation">
                                    <span class="fs-5 fw-semibold" id="val-a-inflation">2.1%</span>
                                </td>
                                <td class="fw-bold text-secondary fs-7">Tingkat Inflasi</td>
                                <td id="cell-b-inflation">
                                    <span class="fs-5 fw-semibold" id="val-b-inflation">3.6%</span>
                                </td>
                            </tr>

                            <!-- Currency Row -->
                            <tr class="table-row-animate">
                                <td>
                                    <span class="fs-5 fw-semibold" id="val-a-currency">EUR (€)</span>
                                    <small class="d-block text-secondary" id="val-a-rate">1 EUR = Rp 17,200</small>
                                </td>
                                <td class="fw-bold text-secondary fs-7">Mata Uang & Kurs Lokal</td>
                                <td>
                                    <span class="fs-5 fw-semibold" id="val-b-currency">AUD ($)</span>
                                    <small class="d-block text-secondary" id="val-b-rate">1 AUD = Rp 10,800</small>
                                </td>
                            </tr>

                            <!-- Population Row -->
                            <tr class="table-row-animate">
                                <td><span class="fs-6 text-white" id="val-a-population">83.2 Juta</span></td>
                                <td class="fw-bold text-secondary fs-7">Total Populasi</td>
                                <td><span class="fs-6 text-white" id="val-b-population">26.2 Juta</span></td>
                            </tr>

                            <!-- Weather Row -->
                            <tr class="table-row-animate">
                                <td>
                                    <span class="fs-6 d-block text-white" id="val-a-temp">18°C (Berawan)</span>
                                    <small class="text-secondary" id="val-a-storm">Risiko Badai: 5%</small>
                                </td>
                                <td class="fw-bold text-secondary fs-7">Kondisi Cuaca Pelabuhan</td>
                                <td>
                                    <span class="fs-6 d-block text-white" id="val-b-temp">14°C (Badai Petir)</span>
                                    <small class="text-danger" id="val-b-storm">Risiko Badai: 80%</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const selectorA = document.getElementById('compare-a');
    const selectorB = document.getElementById('compare-b');
    let loadedCountries = [];

    // Helper functions for formatting
    function formatGDP(value) {
        if (!value) return "N/A";
        if (value >= 1e12) {
            return "$" + (value / 1e12).toFixed(2) + " Triliun";
        } else if (value >= 1e9) {
            return "$" + (value / 1e9).toFixed(2) + " Milyar";
        }
        return "$" + value.toLocaleString('id-ID');
    }

    function formatPopulation(value) {
        if (!value) return "N/A";
        if (value >= 1e9) {
            return (value / 1e9).toFixed(2) + " Milyar";
        } else if (value >= 1e6) {
            return (value / 1e6).toFixed(2) + " Juta";
        }
        return value.toLocaleString('id-ID');
    }

    // Load countries list
    async function loadCountries() {
        try {
            const response = await fetch('/api/countries');
            loadedCountries = await response.json();
            
            // Populate select A
            selectorA.innerHTML = '';
            // Populate select B
            selectorB.innerHTML = '';
            
            loadedCountries.forEach(country => {
                const optA = document.createElement('option');
                optA.value = country.iso2;
                optA.textContent = `${country.iso2.toUpperCase()} - ${country.name}`;
                if (country.iso2 === 'de') optA.selected = true;
                selectorA.appendChild(optA);

                const optB = document.createElement('option');
                optB.value = country.iso2;
                optB.textContent = `${country.iso2.toUpperCase()} - ${country.name}`;
                if (country.iso2 === 'au') optB.selected = true;
                selectorB.appendChild(optB);
            });

            runComparison();
        } catch (error) {
            console.error('Failed to load countries list:', error);
        }
    }

    async function runComparison() {
        const keyA = selectorA.value;
        const keyB = selectorB.value;
        
        if (!keyA || !keyB) return;

        // Apply fading / loading state
        document.querySelectorAll('.table-row-animate').forEach(el => {
            el.style.opacity = 0.5;
        });

        try {
            const [resA, resB] = await Promise.all([
                fetch(`/api/countries/${keyA}`).then(r => r.json()),
                fetch(`/api/countries/${keyB}`).then(r => r.json())
            ]);

            // Update Country A Content
            document.getElementById('header-country-a').textContent = `${keyA.toUpperCase()} - ${resA.country.name}`;
            document.getElementById('val-a-risk-score').textContent = resA.risk.score;
            
            const badgeA = document.getElementById('val-a-risk-badge');
            badgeA.textContent = resA.risk.badge;
            badgeA.className = `badge ${resA.risk.badgeClass} px-3 py-1 rounded-pill`;

            document.getElementById('val-a-gdp').textContent = formatGDP(resA.country.gdp);
            
            const gdpGrowthA = resA.country.gdp_growth || 0;
            document.getElementById('val-a-gdp-growth').textContent = `${gdpGrowthA >= 0 ? '+' : ''}${gdpGrowthA}%`;
            
            const inflA = parseFloat(resA.country.inflation || 0);
            document.getElementById('val-a-inflation').textContent = `${inflA}%`;
            document.getElementById('val-a-currency').textContent = `${resA.currency.code} (${resA.currency.name})`;
            
            const rateStrA = resA.currency.rate_to_usd 
                ? `1 USD = ${parseFloat(resA.currency.rate_to_usd).toLocaleString('id-ID')} ${resA.currency.code}` 
                : '1 USD = N/A';
            document.getElementById('val-a-rate').textContent = rateStrA;
            document.getElementById('val-a-population').textContent = formatPopulation(resA.country.population);
            document.getElementById('val-a-temp').textContent = `${resA.weather.temp}°C`;
            document.getElementById('val-a-storm').textContent = `Risiko Badai: ${resA.weather.storm_risk}%`;

            // Update Country B Content
            document.getElementById('header-country-b').textContent = `${keyB.toUpperCase()} - ${resB.country.name}`;
            document.getElementById('val-b-risk-score').textContent = resB.risk.score;

            const badgeB = document.getElementById('val-b-risk-badge');
            badgeB.textContent = resB.risk.badge;
            badgeB.className = `badge ${resB.risk.badgeClass} px-3 py-1 rounded-pill`;

            document.getElementById('val-b-gdp').textContent = formatGDP(resB.country.gdp);
            
            const gdpGrowthB = resB.country.gdp_growth || 0;
            document.getElementById('val-b-gdp-growth').textContent = `${gdpGrowthB >= 0 ? '+' : ''}${gdpGrowthB}%`;
            
            const inflB = parseFloat(resB.country.inflation || 0);
            document.getElementById('val-b-inflation').textContent = `${inflB}%`;
            document.getElementById('val-b-currency').textContent = `${resB.currency.code} (${resB.currency.name})`;
            
            const rateStrB = resB.currency.rate_to_usd 
                ? `1 USD = ${parseFloat(resB.currency.rate_to_usd).toLocaleString('id-ID')} ${resB.currency.code}` 
                : '1 USD = N/A';
            document.getElementById('val-b-rate').textContent = rateStrB;
            document.getElementById('val-b-population').textContent = formatPopulation(resB.country.population);
            document.getElementById('val-b-temp').textContent = `${resB.weather.temp}°C`;
            document.getElementById('val-b-storm').textContent = `Risiko Badai: ${resB.weather.storm_risk}%`;

            // Highlight better values (inflation closer to 2.0% is highlighted)
            const cellInfA = document.getElementById('cell-a-inflation');
            const cellInfB = document.getElementById('cell-b-inflation');
            
            const devA = Math.abs(inflA - 2.0);
            const devB = Math.abs(inflB - 2.0);
            
            if (devA < devB) {
                cellInfA.className = 'table-success bg-success bg-opacity-10';
                cellInfB.className = '';
            } else if (devB < devA) {
                cellInfB.className = 'table-success bg-success bg-opacity-10';
                cellInfA.className = '';
            } else {
                cellInfA.className = '';
                cellInfB.className = '';
            }

            // Restore Opacity
            document.querySelectorAll('.table-row-animate').forEach(el => {
                el.style.opacity = 1;
            });
        } catch (error) {
            console.error('Failed to run comparison:', error);
        }
    }

    selectorA.addEventListener('change', runComparison);
    selectorB.addEventListener('change', runComparison);

    document.addEventListener('DOMContentLoaded', () => {
        loadCountries();
    });
</script>
@endsection
