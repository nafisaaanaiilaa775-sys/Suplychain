@extends('layouts.app')

@section('title', 'Weather & Port Map')

@section('styles')
<style>
    /* Custom Map Marker Styling */
    .port-marker {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: var(--accent-color);
        color: white;
        border: 2px solid white;
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(99, 102, 241, 0.6);
        transition: transform 0.2s ease;
    }
    
    .port-marker:hover {
        transform: scale(1.2);
    }
    
    .hazard-marker {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: var(--danger);
        color: white;
        border: 2px solid white;
        border-radius: 50%;
        box-shadow: 0 0 15px rgba(239, 68, 68, 0.8);
        animation: pulse-hazard 1.5s infinite;
    }
    
    @keyframes pulse-hazard {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
        70% { transform: scale(1.1); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }

    .leaflet-popup-content-wrapper {
        background-color: var(--bg-secondary) !important;
        color: var(--text-primary) !important;
        border: 1px solid var(--border-color);
        border-radius: 12px;
    }
    .leaflet-popup-tip {
        background-color: var(--bg-secondary) !important;
    }

    .port-list-item {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .port-list-item:hover {
        background: rgba(99, 102, 241, 0.1);
        border-color: var(--accent-color);
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
<div class="container-fluid fade-in-up">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fs-3 fw-bold mb-1" style="font-family: 'Outfit';">Weather & Port Location Map</h1>
            <p class="text-secondary">Peta interaktif pemantauan pelabuhan laut utama dunia serta deteksi dini cuaca ekstrem di sekitarnya.</p>
        </div>
    </div>

    <!-- Map Layout -->
    <div class="row g-4">
        <!-- Sidebar Map Control (Left) -->
        <div class="col-lg-4">
            <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <h5 class="fw-bold mb-3" style="font-family: 'Outfit';">Pencarian & Filter</h5>
                    
                    <!-- Search Input -->
                    <div class="mb-3 position-relative">
                        <label class="form-label text-secondary fs-7">Cari Nama Pelabuhan</label>
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-anchor text-secondary position-absolute ms-3"></i>
                            <input type="text" id="port-search" class="form-control custom-input ps-5 w-100" placeholder="Ketik nama pelabuhan...">
                        </div>
                    </div>

                    <!-- Country Select -->
                    <div class="mb-3">
                        <label class="form-label text-secondary fs-7">Filter Negara</label>
                        <select id="map-country-selector" class="form-select custom-input">
                            <option value="all">Semua Negara</option>
                            <option value="id">Indonesia</option>
                            <option value="de">Jerman</option>
                            <option value="cn">China</option>
                            <option value="au">Australia</option>
                        </select>
                    </div>

                    <!-- Map Layers Toggle -->
                    <div class="mb-4">
                        <label class="form-label text-secondary fs-7">Tampilkan di Peta</label>
                        <div class="d-flex flex-column gap-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="toggle-ports" checked>
                                <label class="form-check-label text-white fs-7" for="toggle-ports">Lokasi Pelabuhan (Anchor)</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="toggle-hazards" checked>
                                <label class="form-check-label text-white fs-7" for="toggle-hazards">Kerawanan Cuaca Buruk (Warning)</label>
                            </div>
                        </div>
                    </div>

                    <!-- Port List Container -->
                    <h6 class="text-secondary mb-2" style="font-size: 0.85rem;">Hasil Pelabuhan Terdaftar:</h6>
                    <div id="port-list" class="overflow-y-auto d-flex flex-column gap-2" style="max-height: 220px; padding-right: 4px;">
                        <!-- Will be dynamically populated via JS -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Interactive Map (Right) -->
        <div class="col-lg-8">
            <div class="glass-card p-2">
                <div id="map" style="height: 550px;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Mockup data pelabuhan global
    const portsDataset = [
        {
            id: 1,
            name: "Port of Tanjung Priok",
            countryCode: "id",
            countryName: "Indonesia",
            lat: -6.1033,
            lng: 106.8911,
            congestion: "Rendah (2 jam delay)",
            congestionColor: "text-success",
            weather: "Cerah, 31°C",
            wind: "8 km/h",
            hazard: false,
            hazardDesc: ""
        },
        {
            id: 2,
            name: "Port of Tanjung Perak",
            countryCode: "id",
            countryName: "Indonesia",
            lat: -7.2023,
            lng: 112.7247,
            congestion: "Sedang (6 jam delay)",
            congestionColor: "text-warning",
            weather: "Cerah Berawan, 32°C",
            wind: "11 km/h",
            hazard: false,
            hazardDesc: ""
        },
        {
            id: 3,
            name: "Port of Hamburg",
            countryCode: "de",
            countryName: "Jerman",
            lat: 53.5458,
            lng: 9.9658,
            congestion: "Rendah (1 jam delay)",
            congestionColor: "text-success",
            weather: "Berawan, 18°C",
            wind: "12 km/h",
            hazard: false,
            hazardDesc: ""
        },
        {
            id: 4,
            name: "Port of Shanghai",
            countryCode: "cn",
            countryName: "China",
            lat: 30.6267,
            lng: 122.0633,
            congestion: "Sangat Tinggi (24 jam delay)",
            congestionColor: "text-danger",
            weather: "Hujan Sedang, 28°C",
            wind: "24 km/h",
            hazard: true,
            hazardDesc: "Hujan Lebat & Angin Kencang (Risiko Hambatan Pengiriman)"
        },
        {
            id: 5,
            name: "Port of Sydney",
            countryCode: "au",
            countryName: "Australia",
            lat: -33.8568,
            lng: 151.2153,
            congestion: "Tinggi (12 jam delay)",
            congestionColor: "text-warning",
            weather: "Badai Petir, 14°C",
            wind: "45 km/h",
            hazard: true,
            hazardDesc: "Peringatan Badai Ekstrem (Operasional Pelabuhan Ditunda)"
        }
    ];

    const countryCoordinates = {
        all: { center: [15, 100], zoom: 3 },
        id: { center: [-2.5489, 118.0149], zoom: 5 },
        de: { center: [51.1657, 10.4515], zoom: 6 },
        cn: { center: [35.8617, 104.1954], zoom: 4 },
        au: { center: [-25.2744, 133.7751], zoom: 4 }
    };

    let map;
    let markerLayerGroup;

    // Initialize Leaflet Map
    function initMap() {
        map = L.map('map').setView(countryCoordinates.all.center, countryCoordinates.all.zoom);
        
        // Light theme map layer from CartoDB Voyager
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);

        markerLayerGroup = L.layerGroup().addTo(map);
        renderPortsAndHazards();
    }

    // Render markers based on filter settings
    function renderPortsAndHazards() {
        markerLayerGroup.clearLayers();
        const searchVal = document.getElementById('port-search').value.toLowerCase();
        const countryVal = document.getElementById('map-country-selector').value;
        const showPorts = document.getElementById('toggle-ports').checked;
        const showHazards = document.getElementById('toggle-hazards').checked;

        const filteredPorts = portsDataset.filter(port => {
            const matchesSearch = port.name.toLowerCase().includes(searchVal);
            const matchesCountry = (countryVal === 'all' || port.countryCode === countryVal);
            return matchesSearch && matchesCountry;
        });

        // Populate left sidebar list
        const listContainer = document.getElementById('port-list');
        listContainer.innerHTML = '';

        if (filteredPorts.length === 0) {
            listContainer.innerHTML = `<div class="text-secondary text-center fs-7 py-3">Tidak ada pelabuhan ditemukan.</div>`;
        }

        filteredPorts.forEach(port => {
            // Add list item
            const item = document.createElement('div');
            item.className = 'port-list-item p-3 d-flex justify-content-between align-items-center';
            item.innerHTML = `
                <div>
                    <span class="fw-semibold text-white d-block" style="font-size: 0.9rem;">${port.name}</span>
                    <small class="text-secondary"><i class="fa-solid fa-location-dot me-1"></i> ${port.countryName}</small>
                </div>
                ${port.hazard ? '<span class="badge bg-danger rounded-circle p-1"><i class="fa-solid fa-triangle-exclamation" style="font-size: 0.65rem;"></i></span>' : '<i class="fa-solid fa-chevron-right text-secondary fs-7"></i>'}
            `;
            item.addEventListener('click', () => {
                map.flyTo([port.lat, port.lng], 9);
                openPortPopup(port);
            });
            listContainer.appendChild(item);

            // Add marker to map
            if (port.hazard && showHazards) {
                // Render hazard marker
                const hazardIcon = L.divIcon({
                    className: 'hazard-marker',
                    html: '<i class="fa-solid fa-triangle-exclamation"></i>',
                    iconSize: [36, 36]
                });

                const m = L.marker([port.lat, port.lng], { icon: hazardIcon }).addTo(markerLayerGroup);
                m.bindPopup(getPopupContent(port));
            } else if (!port.hazard && showPorts) {
                // Render normal port marker
                const portIcon = L.divIcon({
                    className: 'port-marker',
                    html: '<i class="fa-solid fa-anchor"></i>',
                    iconSize: [30, 30]
                });

                const m = L.marker([port.lat, port.lng], { icon: portIcon }).addTo(markerLayerGroup);
                m.bindPopup(getPopupContent(port));
            }
        });
    }

    function getPopupContent(port) {
        return `
            <div style="width: 220px; font-family: 'Inter', sans-serif;">
                <h6 class="fw-bold text-white border-bottom border-secondary pb-2 mb-2 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-anchor text-primary"></i> ${port.name}
                </h6>
                <div class="d-flex flex-column gap-1 text-secondary" style="font-size: 0.8rem;">
                    <div><span class="text-white">Negara:</span> ${port.countryName}</div>
                    <div><span class="text-white">Kemacetan:</span> <span class="${port.congestionColor} fw-semibold">${port.congestion}</span></div>
                    <div><span class="text-white">Cuaca:</span> ${port.weather}</div>
                    <div><span class="text-white">Angin:</span> ${port.wind}</div>
                    ${port.hazard ? `
                        <div class="mt-2 p-2 rounded bg-danger bg-opacity-25 border border-danger border-opacity-50 text-danger" style="font-size: 0.75rem;">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> <strong>Kerawanan:</strong> ${port.hazardDesc}
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
    }

    function openPortPopup(port) {
        // Open popup dynamically on fly-to coordinates
        const pop = L.popup({ closeButton: true })
            .setLatLng([port.lat, port.lng])
            .setContent(getPopupContent(port))
            .openOn(map);
    }

    // Event Listeners for Filters
    document.getElementById('port-search').addEventListener('input', renderPortsAndHazards);
    document.getElementById('toggle-ports').addEventListener('change', renderPortsAndHazards);
    document.getElementById('toggle-hazards').addEventListener('change', renderPortsAndHazards);
    
    document.getElementById('map-country-selector').addEventListener('change', (e) => {
        const countryVal = e.target.value;
        const coords = countryCoordinates[countryVal];
        if (coords) {
            map.flyTo(coords.center, coords.zoom);
        }
        renderPortsAndHazards();
    });

    document.addEventListener('DOMContentLoaded', () => {
        initMap();
    });
</script>
@endsection
