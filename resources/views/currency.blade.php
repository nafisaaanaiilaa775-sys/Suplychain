@extends('layouts.app')

@section('title', 'Currency & News Intelligence')

@section('content')
<div class="container-fluid fade-in-up">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fs-3 fw-bold mb-1" style="font-family: 'Outfit';">Currency & News Intelligence</h1>
            <p class="text-secondary">Analisis pergerakan nilai tukar mata uang global serta intelijen berita bisnis dengan analisis sentimen otomatis.</p>
        </div>
    </div>

    <!-- Main Layout -->
    <div class="row g-4">
        <!-- Left Column: Currency Impact Dashboard (50%) -->
        <div class="col-xl-6">
            <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0" style="font-family: 'Outfit';">Currency Impact Dashboard</h5>
                        <div style="width: 150px;">
                            <select id="currency-pair-selector" class="form-select custom-input py-1 fs-7">
                                <option value="USDIDR">USD / IDR</option>
                                <option value="USDEUR">USD / EUR</option>
                                <option value="USDCNY">USD / CNY</option>
                                <option value="USDAUD">USD / AUD</option>
                            </select>
                        </div>
                    </div>
                    <p class="text-secondary fs-7 mb-4">Tren nilai tukar 30 hari terakhir terhadap USD untuk memantau fluktuasi biaya logistik.</p>

                    <!-- Exchange rate summary stats -->
                    <div class="row g-3 mb-4 text-center">
                        <div class="col-4">
                            <div class="bg-light border border-light-subtle p-3 rounded-3">
                                <small class="text-secondary fs-8 d-block mb-1">Kurs Saat Ini</small>
                                <span class="fw-bold text-dark fs-6" id="curr-value">Rp 16,300.00</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-light border border-light-subtle p-3 rounded-3">
                                <small class="text-secondary fs-8 d-block mb-1">Perubahan (24j)</small>
                                <span class="fw-bold text-success fs-6" id="curr-change">+0.45%</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-light border border-light-subtle p-3 rounded-3">
                                <small class="text-secondary fs-8 d-block mb-1">Stabilitas</small>
                                <span class="fw-bold fs-6" id="curr-status" style="color: #d97706;">Moderat</span>
                            </div>
                        </div>
                    </div>

                    <!-- Chart.js Container -->
                    <div class="bg-light p-2 rounded-3 border border-light-subtle" style="height: 280px; position: relative;">
                        <canvas id="currency-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: News Intelligence & Sentiment (50%) -->
        <div class="col-xl-6">
            <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <h5 class="fw-bold mb-3" style="font-family: 'Outfit';">News Intelligence & Sentiment Analysis</h5>
                    <p class="text-secondary fs-7 mb-3">Monitoring sentimen berita geopolitik, ekonomi, dan rantai pasokan global.</p>

                    <!-- Aggregated Sentiment Indicator -->
                    <div class="p-3 bg-light rounded-4 border border-light-subtle mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fs-7 text-dark fw-semibold">Rangkuman Sentimen Pasar</span>
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-3 py-1 rounded-pill" id="market-sentiment-status">Negatif</span>
                        </div>
                        <div class="progress bg-light border border-light-subtle" style="height: 8px;">
                            <div id="sent-pos" class="progress-bar bg-success" role="progressbar" style="width: 25%" title="Positif: 25%"></div>
                            <div id="sent-neu" class="progress-bar bg-warning" role="progressbar" style="width: 15%" title="Netral: 15%"></div>
                            <div id="sent-neg" class="progress-bar bg-danger" role="progressbar" style="width: 60%" title="Negatif: 60%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2 fs-8 text-secondary">
                            <span><i class="fa-solid fa-circle text-success me-1"></i> Positif: <span id="lbl-pos" class="text-dark fw-semibold">25%</span></span>
                            <span><i class="fa-solid fa-circle text-warning me-1"></i> Netral: <span id="lbl-neu" class="text-dark fw-semibold">15%</span></span>
                            <span><i class="fa-solid fa-circle text-danger me-1"></i> Negatif: <span id="lbl-neg" class="text-dark fw-semibold">60%</span></span>
                        </div>
                    </div>

                    <!-- Category Tab Filter -->
                    <ul class="nav nav-pills nav-fill mb-3 bg-light border border-light-subtle p-1 rounded-3 gap-1 fs-7" id="news-tabs">
                        <li class="nav-item"><button class="nav-link active py-1 text-white border-0" data-cat="all">Semua</button></li>
                        <li class="nav-item"><button class="nav-link py-1 text-secondary border-0 bg-transparent" data-cat="Logistics">Logistik</button></li>
                        <li class="nav-item"><button class="nav-link py-1 text-secondary border-0 bg-transparent" data-cat="Trade">Dagang</button></li>
                        <li class="nav-item"><button class="nav-link py-1 text-secondary border-0 bg-transparent" data-cat="Economy">Ekonomi</button></li>
                    </ul>

                    <!-- News Articles List -->
                    <div id="news-container" class="overflow-y-auto d-flex flex-column gap-2" style="max-height: 280px; padding-right: 4px;">
                        <!-- Will be dynamically populated via JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lexicon Sentiment Details Modal -->
<div class="modal fade" id="sentimentModal" tabindex="-1" aria-labelledby="sentimentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border border-light-subtle text-dark" style="background-color: #ffffff;">
            <div class="modal-header border-light-subtle">
                <h5 class="modal-title fw-bold" id="sentimentModalLabel" style="font-family: 'Outfit';"><i class="fa-solid fa-magnifying-glass-chart text-primary me-2"></i>Lexicon Sentiment Analysis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-sentiment-body">
                <!-- Content will be injected dynamically -->
            </div>
            <div class="modal-footer border-light-subtle">
                <button type="button" class="btn btn-secondary fs-7" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Mockup Data Nilai Tukar (30 hari terakhir)
    const currencyHistory = {
        USDIDR: {
            pair: "USD / IDR",
            current: "Rp 16,300.00",
            change: "+0.45%",
            status: "Stabil",
            label: "USD ke Rupiah",
            data: [16100, 16120, 16090, 16150, 16130, 16180, 16200, 16190, 16220, 16250, 16230, 16280, 16320, 16300, 16290, 16300]
        },
        USDEUR: {
            pair: "USD / EUR",
            current: "0.92 EUR",
            change: "-0.12%",
            status: "Sangat Stabil",
            label: "USD ke Euro",
            data: [0.90, 0.91, 0.905, 0.908, 0.912, 0.915, 0.911, 0.918, 0.922, 0.92, 0.919, 0.924, 0.921, 0.922, 0.92, 0.92]
        },
        USDCNY: {
            pair: "USD / CNY",
            current: "7.24 CNY",
            change: "+0.18%",
            status: "Stabil",
            label: "USD ke Yuan",
            data: [7.15, 7.16, 7.18, 7.17, 7.19, 7.21, 7.20, 7.22, 7.23, 7.25, 7.24, 7.26, 7.23, 7.25, 7.24, 7.24]
        },
        USDAUD: {
            pair: "USD / AUD",
            current: "1.51 AUD",
            change: "+1.10%",
            status: "Volatilitas Tinggi",
            label: "USD ke Dollar Aus",
            data: [1.48, 1.482, 1.49, 1.485, 1.495, 1.502, 1.50, 1.505, 1.51, 1.508, 1.515, 1.52, 1.512, 1.515, 1.51, 1.51]
        }
    };

    // Mockup Berita dengan Analisis Sentimen Leksikon
    const newsDataset = [
        {
            title: "Inflation increases while exports decrease due to war.",
            category: "Economy",
            desc: "Peningkatan inflasi global memicu penurunan tajam ekspor barang manufaktur seiring berlanjutnya konflik bersenjata.",
            words: ["inflation", "increases", "exports", "decrease", "war"],
            posCount: 1,
            posWords: ["increases"],
            negCount: 3,
            negWords: ["inflation", "decrease", "war"],
            sentiment: "Negatif"
        },
        {
            title: "Global shipping logistics face severe delays in crucial shipping lanes.",
            category: "Logistics",
            desc: "Kemacetan hebat di Terusan Suez menyebabkan keterlambatan pengiriman kontainer lebih dari dua minggu.",
            words: ["global", "shipping", "logistics", "face", "severe", "delays"],
            posCount: 0,
            posWords: [],
            negCount: 2,
            negWords: ["severe", "delays"],
            sentiment: "Negatif"
        },
        {
            title: "Trade growth shows stable recovery and profit increase this quarter.",
            category: "Trade",
            desc: "Pulihnya hubungan dagang bilateral mendorong peningkatan keuntungan logistik regional secara signifikan.",
            words: ["trade", "growth", "shows", "stable", "recovery", "profit", "increase"],
            posCount: 5,
            posWords: ["growth", "stable", "recovery", "profit", "increase"],
            negCount: 0,
            negWords: [],
            sentiment: "Positif"
        },
        {
            title: "Rising fuel prices threaten to cause economic crisis and transport delay.",
            category: "Economy",
            desc: "Harga solar industri naik 15%, menekan ongkos logistik darat dan memicu ancaman krisis ekonomi.",
            words: ["fuel", "prices", "threaten", "crisis", "transport", "delay"],
            posCount: 0,
            posWords: [],
            negCount: 3,
            negWords: ["threaten", "crisis", "delay"],
            sentiment: "Negatif"
        },
        {
            title: "New ports agreement improves trade lines and cuts delivery transit times.",
            category: "Logistics",
            desc: "Penandatanganan kerjasama pelabuhan baru berhasil memangkas birokrasi dan waktu singgah kapal kontainer.",
            words: ["ports", "agreement", "improves", "trade", "cuts", "delivery", "transit"],
            posCount: 1,
            posWords: ["improves"],
            negCount: 0,
            negWords: [],
            sentiment: "Positif"
        }
    ];

    // Chart.js initialization
    let chartInstance;
    
    function drawChart(pairKey) {
        const ctx = document.getElementById('currency-chart').getContext('2d');
        const chartData = currencyHistory[pairKey];

        // Update stats
        document.getElementById('curr-value').textContent = chartData.current;
        document.getElementById('curr-change').textContent = chartData.change;
        document.getElementById('curr-status').textContent = chartData.status;

        // Change color based on change direction and status styling
        const changeEl = document.getElementById('curr-change');
        const statusEl = document.getElementById('curr-status');
        
        if (chartData.change.startsWith('+')) {
            changeEl.className = 'fw-bold text-success fs-6';
        } else {
            changeEl.className = 'fw-bold text-danger fs-6';
        }

        if (chartData.status.includes('Tinggi')) {
            statusEl.style.color = '#ef4444'; // Red
        } else if (chartData.status.includes('Sangat Stabil')) {
            statusEl.style.color = '#10b981'; // Green
        } else {
            statusEl.style.color = '#d97706'; // Amber/Orange
        }

        const labels = Array.from({length: chartData.data.length}, (_, i) => `Hari ${i+1}`);

        if (chartInstance) {
            chartInstance.destroy();
        }

        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: chartData.label,
                    data: chartData.data,
                    borderColor: '#2563eb', // Corporate Blue
                    backgroundColor: 'rgba(37, 99, 235, 0.05)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#2563eb'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: { color: '#64748b', font: { size: 9 } }
                    },
                    y: {
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: { color: '#64748b', font: { size: 9 } }
                    }
                }
            }
        });
    }

    // Populate News Feed & Calculate Aggregated Sentiment
    function renderNews(categoryFilter = 'all') {
        const container = document.getElementById('news-container');
        container.innerHTML = '';

        const filteredNews = newsDataset.filter(item => {
            return categoryFilter === 'all' || item.category === categoryFilter;
        });

        if (filteredNews.length === 0) {
            container.innerHTML = `<div class="text-secondary text-center py-3 fs-7">Tidak ada berita untuk kategori ini.</div>`;
            return;
        }

        let posTotal = 0;
        let negTotal = 0;
        let neuTotal = 0;

        filteredNews.forEach(item => {
            if (item.sentiment === 'Positif') posTotal++;
            else if (item.sentiment === 'Negatif') negTotal++;
            else neuTotal++;

            const card = document.createElement('div');
            card.className = 'glass-card p-3 d-flex flex-column gap-2 border-light-subtle cursor-pointer';
            card.style.background = '#f8fafc';
            card.innerHTML = `
                <div class="d-flex justify-content-between align-items-start">
                    <span class="badge bg-light text-secondary border border-light-subtle px-2 py-0.5 rounded fs-8">${item.category}</span>
                    <span class="badge ${item.sentiment === 'Positif' ? 'bg-success bg-opacity-10 text-success border border-success border-opacity-20' : 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20'} fs-8 px-2 py-0.5">${item.sentiment}</span>
                </div>
                <h6 class="fw-semibold text-dark mb-0 text-truncate" style="font-size: 0.9rem;">${item.title}</h6>
                <p class="text-secondary mb-0 text-truncate" style="font-size: 0.8rem;">${item.desc}</p>
                <div class="d-flex justify-content-end">
                    <small class="text-primary fs-8">Klik untuk Detail Lexicon <i class="fa-solid fa-chevron-right ms-1"></i></small>
                </div>
            `;
            
            // Add click event for lexicon modal
            card.addEventListener('click', () => {
                showSentimentDetails(item);
            });
            container.appendChild(card);
        });

        // Update aggregated sentiment percentage bars
        const total = filteredNews.length;
        const posPercent = Math.round((posTotal / total) * 100);
        const negPercent = Math.round((negTotal / total) * 100);
        const neuPercent = 100 - posPercent - negPercent;

        document.getElementById('sent-pos').style.width = `${posPercent}%`;
        document.getElementById('sent-neu').style.width = `${neuPercent}%`;
        document.getElementById('sent-neg').style.width = `${negPercent}%`;

        document.getElementById('lbl-pos').textContent = `${posPercent}%`;
        document.getElementById('lbl-neu').textContent = `${neuPercent}%`;
        document.getElementById('lbl-neg').textContent = `${negPercent}%`;

        const banner = document.getElementById('market-sentiment-status');
        if (negPercent > posPercent) {
            banner.textContent = "Negatif";
            banner.className = "badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-3 py-1 rounded-pill";
        } else if (posPercent > negPercent) {
            banner.textContent = "Positif";
            banner.className = "badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-3 py-1 rounded-pill";
        } else {
            banner.textContent = "Netral";
            banner.className = "badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-20 px-3 py-1 rounded-pill";
        }
    }

    // Modal popup helper
    function showSentimentDetails(newsItem) {
        const body = document.getElementById('modal-sentiment-body');
        
        let wordsHtml = newsItem.words.map(w => {
            let isPos = newsItem.posWords.includes(w.toLowerCase());
            let isNeg = newsItem.negWords.includes(w.toLowerCase());
            if (isPos) {
                return `<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-2 py-1 m-1 fs-7"><i class="fa-solid fa-plus me-1"></i> ${w}</span>`;
            } else if (isNeg) {
                return `<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-2 py-1 m-1 fs-7"><i class="fa-solid fa-minus me-1"></i> ${w}</span>`;
            } else {
                return `<span class="badge bg-light border border-light-subtle px-2 py-1 m-1 fs-7 text-dark">${w}</span>`;
            }
        }).join('');

        body.innerHTML = `
            <div class="mb-3">
                <span class="text-secondary fs-8">Kalimat Berita:</span>
                <p class="fs-6 fw-semibold text-dark mt-1 border-start border-primary border-3 ps-3">${newsItem.title}</p>
            </div>
            <div class="mb-3">
                <span class="text-secondary fs-8">Daftar Kata (Tokens):</span>
                <div class="d-flex flex-wrap mt-2">
                    ${wordsHtml}
                </div>
            </div>
            <hr class="border-light-subtle">
            <div class="row text-center mt-3 g-2">
                <div class="col-4">
                    <div class="bg-success bg-opacity-10 border border-success border-opacity-20 p-2 rounded">
                        <small class="text-success d-block fs-8">Positive Score</small>
                        <span class="fs-4 fw-bold text-success">${newsItem.posCount}</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-danger bg-opacity-10 border border-danger border-opacity-20 p-2 rounded">
                        <small class="text-danger d-block fs-8">Negative Score</small>
                        <span class="fs-4 fw-bold text-danger">${newsItem.negCount}</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-primary bg-opacity-10 border border-primary border-opacity-20 p-2 rounded">
                        <small class="text-primary d-block fs-8">Hasil Sentimen</small>
                        <span class="fs-5 fw-bold text-primary" style="line-height: 2.2rem;">${newsItem.sentiment}</span>
                    </div>
                </div>
            </div>
            <div class="mt-3 p-3 bg-light border border-light-subtle rounded-3 text-secondary" style="font-size: 0.8rem;">
                <i class="fa-solid fa-circle-info me-2 text-info"></i>
                Formula: Sentimen ditentukan oleh skor mana yang lebih dominan. Jika Skor Positif > Skor Negatif maka <strong>Positif</strong>, sebaliknya menjadi <strong>Negatif</strong>.
            </div>
        `;

        const modal = new bootstrap.Modal(document.getElementById('sentimentModal'));
        modal.show();
    }

    // Event Listeners for Currency
    document.getElementById('currency-pair-selector').addEventListener('change', (e) => {
        drawChart(e.target.value);
    });

    // Event Listeners for News Filter
    document.querySelectorAll('#news-tabs button').forEach(btn => {
        btn.addEventListener('click', (e) => {
            // Remove active class
            document.querySelectorAll('#news-tabs button').forEach(b => {
                b.classList.remove('active');
                b.className = b.className.replace('text-white bg-primary', 'text-secondary bg-transparent');
            });
            // Add active class
            e.target.classList.add('active');
            e.target.className = e.target.className.replace('text-secondary bg-transparent', 'text-white bg-primary');

            renderNews(e.target.getAttribute('data-cat'));
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        drawChart('USDIDR');
        renderNews('all');
    });
</script>
@endsection
