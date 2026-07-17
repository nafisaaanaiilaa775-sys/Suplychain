@extends('layouts.app')

@section('title', 'Global Country Dashboard')

@section('content')
<div class="container-fluid fade-in-up">
    <!-- Top Row: Welcome & Selector -->
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h1 class="fs-3 fw-bold mb-1" style="font-family: 'Outfit';">Global Country Dashboard</h1>
            <p class="text-secondary mb-0">Analisis risiko rantai pasok dan indikator ekonomi per negara secara real-time.</p>
        </div>
        <div class="col-md-4 mt-3 mt-md-0">
            <div class="d-flex align-items-center gap-3 justify-content-md-end">
                <label for="country-selector" class="text-secondary text-nowrap mb-0">Pilih Negara:</label>
                <select id="country-selector" class="form-select custom-input" style="width: 200px;">
                    <option value="id" selected>🇮🇩 Indonesia</option>
                    <option value="de">🇩🇪 Germany</option>
                    <option value="cn">🇨🇳 China</option>
                    <option value="au">🇦🇺 Australia</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Body -->
    <div class="row g-4">
        <!-- Left Section: Key Country Metrics -->
        <div class="col-xl-8">
            <div class="row g-4">
                <!-- GDP Card -->
                <div class="col-md-6">
                    <div class="glass-card p-4 accent-border-left-primary h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-secondary uppercase fs-7 tracking-wider">Produk Domestik Bruto (GDP)</span>
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 text-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-sack-dollar"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-1" id="val-gdp">$1.37 Triliun</h3>
                        <span class="text-success fs-7" id="val-gdp-growth">
                            <i class="fa-solid fa-arrow-trend-up me-1"></i>+5.05% (Pertumbuhan Tahunan)
                        </span>
                    </div>
                </div>

                <!-- Inflation Card -->
                <div class="col-md-6">
                    <div class="glass-card p-4 accent-border-left-warning h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-secondary uppercase fs-7 tracking-wider">Tingkat Inflasi</span>
                            <div class="rounded-circle bg-warning bg-opacity-10 p-2 text-warning d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-percent"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-1" id="val-inflation">2.8%</h3>
                        <span class="text-secondary fs-7" id="val-inflation-status">
                            <i class="fa-solid fa-circle-check text-success me-1"></i>Dalam batas aman
                        </span>
                    </div>
                </div>

                <!-- Population Card -->
                <div class="col-md-6">
                    <div class="glass-card p-4 accent-border-left-primary h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-secondary uppercase fs-7 tracking-wider">Total Populasi</span>
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 text-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-users"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-1" id="val-population">275.5 Juta</h3>
                        <span class="text-secondary fs-7">Tenaga kerja & pasar potensial</span>
                    </div>
                </div>

                <!-- Currency Card -->
                <div class="col-md-6">
                    <div class="glass-card p-4 accent-border-left-primary h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-secondary uppercase fs-7 tracking-wider">Mata Uang & Kurs</span>
                            <div class="rounded-circle bg-info bg-opacity-10 p-2 text-info d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-money-bill-transfer"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-1" id="val-currency">IDR (Rupiah)</h3>
                        <span class="text-secondary fs-7" id="val-exchange-rate">1 USD = Rp 16,300.00</span>
                    </div>
                </div>

                <!-- Weather Card -->
                <div class="col-12">
                    <div class="glass-card p-4 accent-border-left-primary">
                        <h5 class="fw-bold mb-3" style="font-family: 'Outfit';">Kondisi Cuaca Pelabuhan Utama</h5>
                        <div class="row g-3 text-center">
                            <div class="col-6 col-md-3">
                                <div class="bg-light p-3 rounded-3 border border-light-subtle">
                                    <span class="text-secondary d-block mb-1 fs-7">Temperatur</span>
                                    <h4 class="fw-bold mb-0 text-dark" id="weather-temp">31°C</h4>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="bg-light p-3 rounded-3 border border-light-subtle">
                                    <span class="text-secondary d-block mb-1 fs-7">Curah Hujan</span>
                                    <h4 class="fw-bold mb-0 text-dark" id="weather-rain">1.2 mm</h4>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="bg-light p-3 rounded-3 border border-light-subtle">
                                    <span class="text-secondary d-block mb-1 fs-7">Kecepatan Angin</span>
                                    <h4 class="fw-bold mb-0 text-dark" id="weather-wind">8 km/h</h4>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="bg-light p-3 rounded-3 border border-light-subtle">
                                    <span class="text-secondary d-block mb-1 fs-7">Risiko Badai</span>
                                    <h4 class="fw-bold mb-0 text-success" id="weather-storm">Rendah (2%)</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Section: Risk Scoring Engine -->
        <div class="col-xl-4">
            <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0" style="font-family: 'Outfit';">Risk Scoring Engine</h5>
                        <span class="badge risk-low px-3 py-2 rounded-pill fw-semibold fs-7" id="risk-badge">Low Risk</span>
                    </div>

                    <!-- Visual Circle Progress -->
                    <div class="d-flex justify-content-center my-4">
                        <div class="position-relative d-flex align-items-center justify-content-center" style="width: 180px; height: 180px;">
                            <!-- Outer SVG ring -->
                            <svg class="position-absolute w-100 h-100" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="40" stroke="#e2e8f0" stroke-width="8" fill="transparent" />
                                <circle id="risk-progress" cx="50" cy="50" r="40" stroke="#10b981" stroke-width="8" fill="transparent" 
                                        stroke-dasharray="251.2" stroke-dashoffset="188.4" stroke-linecap="round" 
                                        style="transform: rotate(-90deg); transform-origin: 50% 50%; transition: stroke-dashoffset 0.8s ease, stroke 0.8s ease;" />
                            </svg>
                            <div class="text-center">
                                <h1 class="display-5 fw-bold mb-0 text-dark" id="risk-score">25</h1>
                                <span class="text-secondary fs-7">dari 100</span>
                            </div>
                        </div>
                    </div>

                    <!-- Risk Indicators Breakdown -->
                    <h6 class="text-secondary mb-3 mt-4" style="font-size: 0.85rem;">Rincian Komponen Risiko:</h6>
                    <div class="d-flex flex-column gap-3">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fs-7 text-secondary"><i class="fa-solid fa-cloud-showers-water me-2 text-info"></i> Risiko Cuaca</span>
                                <span class="fs-7 fw-semibold text-dark" id="risk-weather-val">5%</span>
                            </div>
                            <div class="progress bg-light border border-light-subtle" style="height: 6px;">
                                <div id="risk-weather-bar" class="progress-bar bg-info" role="progressbar" style="width: 5%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fs-7 text-secondary"><i class="fa-solid fa-chart-line me-2 text-warning"></i> Risiko Inflasi</span>
                                <span class="fs-7 fw-semibold text-dark" id="risk-inflation-val">25%</span>
                            </div>
                            <div class="progress bg-light border border-light-subtle" style="height: 6px;">
                                <div id="risk-inflation-bar" class="progress-bar bg-warning" role="progressbar" style="width: 25%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fs-7 text-secondary"><i class="fa-solid fa-money-bill-wave me-2 text-primary"></i> Volatilitas Kurs</span>
                                <span class="fs-7 fw-semibold text-dark" id="risk-currency-val">10%</span>
                            </div>
                            <div class="progress bg-light border border-light-subtle" style="height: 6px;">
                                <div id="risk-currency-bar" class="progress-bar bg-primary" role="progressbar" style="width: 10%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fs-7 text-secondary"><i class="fa-solid fa-newspaper me-2 text-danger"></i> Sentimen Negatif Berita</span>
                                <span class="fs-7 fw-semibold text-dark" id="risk-news-val">60%</span>
                            </div>
                            <div class="progress bg-light border border-light-subtle" style="height: 6px;">
                                <div id="risk-news-bar" class="progress-bar bg-danger" role="progressbar" style="width: 60%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top border-light-subtle">
                    <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2 py-2" id="btn-save-watchlist">
                        <i class="fa-regular fa-star"></i> Simpan ke Watchlist Saya
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const selector = document.getElementById('country-selector');
    let loadedCountries = [];
    let currentCountryId = null;

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
            
            // Populate selector
            selector.innerHTML = '';
            loadedCountries.forEach(country => {
                const option = document.createElement('option');
                option.value = country.iso2;
                option.textContent = `${country.iso2.toUpperCase()} - ${country.name}`;
                if (country.iso2 === 'id') {
                    option.selected = true;
                }
                selector.appendChild(option);
            });

            // Load initial dashboard
            updateDashboard('id');
        } catch (error) {
            console.error('Failed to load countries list:', error);
        }
    }

    // Update UI elements based on selected country
    async function updateDashboard(countryCode) {
        try {
            // Apply fading effect / loading state
            const container = document.querySelector('.fade-in-up');
            container.style.opacity = 0.5;

            const response = await fetch(`/api/countries/${countryCode}`);
            const data = await response.json();
            
            if (response.status !== 200) {
                alert(data.message || 'Gagal memuat data negara');
                return;
            }

            currentCountryId = data.country.id;

            // Apply dashboard values
            document.getElementById('val-gdp').textContent = formatGDP(data.country.gdp);
            
            const gdpGrowth = data.country.gdp_growth;
            const growthSign = gdpGrowth >= 0 ? '+' : '';
            const growthColorClass = gdpGrowth >= 0 ? 'text-success' : 'text-danger';
            const growthIcon = gdpGrowth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down';
            document.getElementById('val-gdp-growth').className = `${growthColorClass} fs-7`;
            document.getElementById('val-gdp-growth').innerHTML = `<i class="fa-solid ${growthIcon} me-1"></i>${growthSign}${gdpGrowth}% (Pertumbuhan Tahunan)`;

            document.getElementById('val-inflation').textContent = `${data.country.inflation}%`;
            
            const inflationStatusEl = document.getElementById('val-inflation-status');
            if (data.country.inflation <= 3.0 && data.country.inflation >= 0) {
                inflationStatusEl.innerHTML = `<i class="fa-solid fa-circle-check text-success me-1"></i>Dalam batas aman`;
            } else if (data.country.inflation < 0) {
                inflationStatusEl.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-info me-1"></i>Deflasi Terjadi`;
            } else {
                inflationStatusEl.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-warning me-1"></i>Mulai Tinggi`;
            }

            document.getElementById('val-population').textContent = formatPopulation(data.country.population);
            document.getElementById('val-currency').textContent = `${data.currency.code} (${data.currency.name})`;
            
            const rateStr = data.currency.rate_to_usd 
                ? `1 USD = ${parseFloat(data.currency.rate_to_usd).toLocaleString('id-ID')} ${data.currency.code}` 
                : '1 USD = N/A';
            document.getElementById('val-exchange-rate').textContent = rateStr;

            // Weather
            document.getElementById('weather-temp').textContent = `${data.weather.temp}°C`;
            document.getElementById('weather-rain').textContent = `${data.weather.rain} mm`;
            document.getElementById('weather-wind').textContent = `${data.weather.wind} km/h`;
            
            const stormEl = document.getElementById('weather-storm');
            const stormRisk = data.weather.storm_risk;
            stormEl.textContent = `${stormRisk}%`;
            if (stormRisk > 50) {
                stormEl.className = 'fw-bold mb-0 text-danger';
            } else if (stormRisk > 20) {
                stormEl.className = 'fw-bold mb-0 text-warning';
            } else {
                stormEl.className = 'fw-bold mb-0 text-success';
            }

            // Risk Engine
            document.getElementById('risk-score').textContent = data.risk.score;
            
            const badge = document.getElementById('risk-badge');
            badge.textContent = data.risk.badge;
            badge.className = `badge ${data.risk.badgeClass} px-3 py-2 rounded-pill fw-semibold fs-7`;

            // Progress Ring Circle Math
            const circle = document.getElementById('risk-progress');
            const radius = circle.r.baseVal.value;
            const circumference = 2 * Math.PI * radius; // ~251.2
            const offset = circumference - (data.risk.score / 100) * circumference;
            circle.style.strokeDashoffset = offset;
            circle.style.stroke = data.risk.progressColor;

            // Bars
            document.getElementById('risk-weather-val').textContent = `${data.risk.breakdown.weather}%`;
            document.getElementById('risk-weather-bar').style.width = `${data.risk.breakdown.weather}%`;

            document.getElementById('risk-inflation-val').textContent = `${data.risk.breakdown.inflation}%`;
            document.getElementById('risk-inflation-bar').style.width = `${data.risk.breakdown.inflation}%`;

            document.getElementById('risk-currency-val').textContent = `${data.risk.breakdown.currency}%`;
            document.getElementById('risk-currency-bar').style.width = `${data.risk.breakdown.currency}%`;

            document.getElementById('risk-news-val').textContent = `${data.risk.breakdown.news}%`;
            document.getElementById('risk-news-bar').style.width = `${data.risk.breakdown.news}%`;

            // Restore Opacity
            container.style.opacity = 1;
            container.style.transform = 'translateY(0)';
        } catch (error) {
            console.error('Failed to update dashboard:', error);
        }
    }

    selector.addEventListener('change', (e) => {
        updateDashboard(e.target.value);
    });

    // Trigger initial progress circle load
    document.addEventListener('DOMContentLoaded', () => {
        loadCountries();
    });

    // Button watchlist action
    document.getElementById('btn-save-watchlist').addEventListener('click', async function() {
        if (!currentCountryId) return;
        
        try {
            const response = await fetch('/api/watchlist', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ country_id: currentCountryId })
            });

            const result = await response.json();
            if (response.ok) {
                alert(result.message);
            } else {
                alert(result.message || 'Gagal menambahkan ke daftar pantauan');
            }
        } catch (error) {
            console.error('Watchlist save failed:', error);
            alert('Terjadi kesalahan saat menyimpan ke watchlist');
        }
    });
</script>
@endsection
