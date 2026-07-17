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
    // Mockup data similar to dashboard.blade.php
    const compareData = {
        id: {
            flag: "🇮🇩",
            name: "Indonesia",
            gdp: "$1.37 Triliun",
            gdpGrowth: "+5.05% (Pertumbuhan Kuat)",
            inflation: 2.8,
            currency: "IDR (Rupiah)",
            rate: "1 USD = Rp 16,300.00",
            population: "275.5 Juta",
            temp: "31°C (Cerah)",
            storm: "2%",
            riskScore: 25,
            riskBadge: "Low Risk",
            riskClass: "risk-low"
        },
        de: {
            flag: "🇩🇪",
            name: "Jerman",
            gdp: "$4.20 Triliun",
            gdpGrowth: "+1.2% (Pertumbuhan Lambat)",
            inflation: 2.1,
            currency: "EUR (Euro)",
            rate: "1 USD = 0.92 EUR (1 EUR = Rp 17,200)",
            population: "83.2 Juta",
            temp: "18°C (Berawan)",
            storm: "5%",
            riskScore: 22,
            riskBadge: "Low Risk",
            riskClass: "risk-low"
        },
        cn: {
            flag: "🇨🇳",
            name: "China",
            gdp: "$17.7 Triliun",
            gdpGrowth: "+4.8% (Kuat)",
            inflation: 0.5,
            currency: "CNY (Yuan)",
            rate: "1 USD = 7.24 CNY (1 CNY = Rp 2,250)",
            population: "1.41 Milyar",
            temp: "28°C (Hujan)",
            storm: "45%",
            riskScore: 47,
            riskBadge: "Medium Risk",
            riskClass: "risk-medium"
        },
        au: {
            flag: "🇦🇺",
            name: "Australia",
            gdp: "$1.62 Triliun",
            gdpGrowth: "+1.9% (Normal)",
            inflation: 3.6,
            currency: "AUD (Dollar Aus)",
            rate: "1 USD = 1.51 AUD (1 AUD = Rp 10,800)",
            population: "26.2 Juta",
            temp: "14°C (Badai Petir)",
            storm: "80%",
            riskScore: 68,
            riskBadge: "High Risk",
            riskClass: "risk-high"
        }
    };

    function runComparison() {
        const keyA = document.getElementById('compare-a').value;
        const keyB = document.getElementById('compare-b').value;
        const dataA = compareData[keyA];
        const dataB = compareData[keyB];

        if (!dataA || !dataB) return;

        // Apply fading effect
        document.querySelectorAll('.table-row-animate').forEach(el => {
            el.style.opacity = 0;
            el.style.transition = 'opacity 0.2s ease';
        });

        setTimeout(() => {
            // Update Country A Headers & Content
            document.getElementById('header-country-a').textContent = `${dataA.flag} ${dataA.name}`;
            document.getElementById('val-a-risk-score').textContent = dataA.riskScore;
            
            const badgeA = document.getElementById('val-a-risk-badge');
            badgeA.textContent = dataA.riskBadge;
            badgeA.className = `badge ${dataA.riskClass} px-3 py-1 rounded-pill`;

            document.getElementById('val-a-gdp').textContent = dataA.gdp;
            document.getElementById('val-a-gdp-growth').textContent = dataA.gdpGrowth;
            document.getElementById('val-a-inflation').textContent = `${dataA.inflation}%`;
            document.getElementById('val-a-currency').textContent = dataA.currency;
            document.getElementById('val-a-rate').textContent = dataA.rate;
            document.getElementById('val-a-population').textContent = dataA.population;
            document.getElementById('val-a-temp').textContent = dataA.temp;
            document.getElementById('val-a-storm').textContent = `Risiko Badai: ${dataA.storm}`;

            // Update Country B Headers & Content
            document.getElementById('header-country-b').textContent = `${dataB.flag} ${dataB.name}`;
            document.getElementById('val-b-risk-score').textContent = dataB.riskScore;

            const badgeB = document.getElementById('val-b-risk-badge');
            badgeB.textContent = dataB.riskBadge;
            badgeB.className = `badge ${dataB.riskClass} px-3 py-1 rounded-pill`;

            document.getElementById('val-b-gdp').textContent = dataB.gdp;
            document.getElementById('val-b-gdp-growth').textContent = dataB.gdpGrowth;
            document.getElementById('val-b-inflation').textContent = `${dataB.inflation}%`;
            document.getElementById('val-b-currency').textContent = dataB.currency;
            document.getElementById('val-b-rate').textContent = dataB.rate;
            document.getElementById('val-b-population').textContent = dataB.population;
            document.getElementById('val-b-temp').textContent = dataB.temp;
            document.getElementById('val-b-storm').textContent = `Risiko Badai: ${dataB.storm}`;

            // Dynamic Styling / Highlighting of better values
            // Inflation coloring (lower inflation is highlighted in green, higher in orange/red)
            const cellInfA = document.getElementById('cell-a-inflation');
            const cellInfB = document.getElementById('cell-b-inflation');
            
            if (dataA.inflation < dataB.inflation) {
                cellInfA.className = 'table-success bg-success bg-opacity-10';
                cellInfB.className = '';
            } else if (dataB.inflation < dataA.inflation) {
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
        }, 200);
    }

    document.getElementById('compare-a').addEventListener('change', runComparison);
    document.getElementById('compare-b').addEventListener('change', runComparison);

    document.addEventListener('DOMContentLoaded', () => {
        runComparison();
    });
</script>
@endsection
