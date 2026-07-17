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
    // Mapping currency pairs to country codes for news aggregation
    const pairToIso = {
        USDIDR: 'id',
        USDEUR: 'de',
        USDCNY: 'cn',
        USDAUD: 'au'
    };

    let chartInstance;
    let currentNewsDataset = [];
    let positiveWords = [];
    let negativeWords = [];

    // Fetch Positive/Negative words list from backend for local lexicon rendering
    async function loadLexicon() {
        try {
            positiveWords = ['growth', 'increase', 'profit', 'stable', 'improve', 'safe', 'secure', 'boom', 'recovery', 'gain', 'surplus', 'positive', 'expansion', 'success', 'benefit', 'opportunity', 'smooth', 'efficient', 'resolved', 'strengthen', 'climb', 'rise'];
            negativeWords = ['war', 'crisis', 'inflation', 'delay', 'disaster', 'risk', 'danger', 'drop', 'loss', 'deficit', 'negative', 'decline', 'failure', 'threat', 'storm', 'flood', 'strike', 'protest', 'bottleneck', 'congestion', 'blockage', 'sanction', 'conflict', 'macet'];
        } catch (error) {
            console.error('Failed to load lexicon words:', error);
        }
    }

    // Draw exchange rate history chart
    async function updateCurrencyAndNews(pairKey) {
        const iso2 = pairToIso[pairKey];
        const ctx = document.getElementById('currency-chart').getContext('2d');
        
        try {
            // Fetch country details containing currency & news
            const response = await fetch(`/api/countries/${iso2}`);
            const data = await response.json();
            
            const rate = parseFloat(data.currency.rate_to_usd);
            const currencyName = data.currency.name;
            const currencyCode = data.currency.code;

            // Formatted current rate
            let formattedRate = '';
            if (currencyCode === 'IDR') {
                formattedRate = `Rp ${rate.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            } else if (currencyCode === 'EUR') {
                formattedRate = `${rate.toFixed(4)} EUR`;
            } else {
                formattedRate = `${rate.toFixed(4)} ${currencyCode}`;
            }

            document.getElementById('curr-value').textContent = formattedRate;
            
            // Calculate a mock 24h change and stability metric based on calculated currency risk
            const currencyRisk = data.risk.breakdown.currency;
            let changePercent = (Math.random() * 0.8 - 0.4).toFixed(2);
            let changeSign = changePercent >= 0 ? '+' : '';
            
            document.getElementById('curr-change').textContent = `${changeSign}${changePercent}%`;
            document.getElementById('curr-change').className = changePercent >= 0 ? 'fw-bold text-success fs-6' : 'fw-bold text-danger fs-6';

            let stability = 'Tinggi';
            let stabilityColor = '#10b981'; // Green
            if (currencyRisk > 40) {
                stability = 'Volatilitas Tinggi';
                stabilityColor = '#ef4444'; // Red
            } else if (currencyRisk > 20) {
                stability = 'Moderat';
                stabilityColor = '#f59e0b'; // Amber
            } else {
                stability = 'Sangat Stabil';
            }
            
            const statusEl = document.getElementById('curr-status');
            statusEl.textContent = stability;
            statusEl.style.color = stabilityColor;

            // Generate 30 days of historical rates (simulated random walk from current rate)
            const historyData = [];
            let currentWalker = rate;
            for (let i = 0; i < 30; i++) {
                currentWalker = currentWalker * (1 + (Math.random() * 0.01 - 0.005));
                historyData.push(currentWalker);
            }
            historyData.reverse(); // oldest first

            const labels = Array.from({length: 30}, (_, i) => `Hari ${i+1}`);

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: `Kurs USD ke ${currencyCode}`,
                        data: historyData,
                        borderColor: '#2563eb',
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

            // Set current news dataset
            currentNewsDataset = data.news.map(art => {
                // Determine category based on keywords
                let cat = 'Economy';
                const titleLower = art.title.toLowerCase();
                const descLower = art.description.toLowerCase();
                
                if (titleLower.includes('port') || titleLower.includes('logistics') || titleLower.includes('shipping') || descLower.includes('shipping')) {
                    cat = 'Logistics';
                } else if (titleLower.includes('trade') || titleLower.includes('tariff') || titleLower.includes('export') || descLower.includes('trade')) {
                    cat = 'Trade';
                }

                // Analyze words
                const cleanText = (art.title + ' ' + art.description).toLowerCase().replace(/[^\w\s]/g, '');
                const tokens = cleanText.split(/\s+/).filter(t => t.length > 0);
                
                const posFound = tokens.filter(t => positiveWords.includes(t));
                const negFound = tokens.filter(t => negativeWords.includes(t));

                let sent = 'Netral';
                if (posFound.length > negFound.length) sent = 'Positif';
                else if (negFound.length > posFound.length) sent = 'Negatif';

                return {
                    title: art.title,
                    category: cat,
                    desc: art.description,
                    words: tokens,
                    posCount: posFound.length,
                    posWords: posFound,
                    negCount: negFound.length,
                    negWords: negFound,
                    sentiment: sent
                };
            });

            renderNews('all');

        } catch (error) {
            console.error('Failed to update currency pair:', error);
        }
    }

    // Populate News Feed & Calculate Aggregated Sentiment
    function renderNews(categoryFilter = 'all') {
        const container = document.getElementById('news-container');
        container.innerHTML = '';

        const filteredNews = currentNewsDataset.filter(item => {
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
                    <span class="badge ${item.sentiment === 'Positif' ? 'bg-success bg-opacity-10 text-success border border-success border-opacity-20' : (item.sentiment === 'Negatif' ? 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20' : 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-20')} fs-8 px-2 py-0.5">${item.sentiment}</span>
                </div>
                <h6 class="fw-semibold text-dark mb-0 text-truncate" style="font-size: 0.9rem;">${item.title}</h6>
                <p class="text-secondary mb-0 text-truncate" style="font-size: 0.8rem;">${item.desc}</p>
                <div class="d-flex justify-content-end">
                    <small class="text-primary fs-8">Klik untuk Detail Lexicon <i class="fa-solid fa-chevron-right ms-1"></i></small>
                </div>
            `;
            
            card.addEventListener('click', () => {
                showSentimentDetails(item);
            });
            container.appendChild(card);
        });

        // Update aggregated sentiment percentage bars
        const total = filteredNews.length;
        const posPercent = Math.round((posTotal / total) * 100) || 0;
        const negPercent = Math.round((negTotal / total) * 100) || 0;
        const neuPercent = total > 0 ? (100 - posPercent - negPercent) : 0;

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
            let isPos = positiveWords.includes(w.toLowerCase());
            let isNeg = negativeWords.includes(w.toLowerCase());
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
        updateCurrencyAndNews(e.target.value);
    });

    // Event Listeners for News Filter
    document.querySelectorAll('#news-tabs button').forEach(btn => {
        btn.addEventListener('click', (e) => {
            document.querySelectorAll('#news-tabs button').forEach(b => {
                b.classList.remove('active');
                b.className = b.className.replace('text-white bg-primary', 'text-secondary bg-transparent');
            });
            e.target.classList.add('active');
            e.target.className = e.target.className.replace('text-secondary bg-transparent', 'text-white bg-primary');

            renderNews(e.target.getAttribute('data-cat'));
        });
    });

    document.addEventListener('DOMContentLoaded', async () => {
        await loadLexicon();
        updateCurrencyAndNews('USDIDR');
    });
</script>
@endsection
