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
    const selector = document.getElementById('map-country-selector');
    let map;
    let markerLayerGroup;
    let loadedCountries = [];
    let currentPorts = [];
    let activeWeather = null;

    // Initialize Leaflet Map
    function initMap() {
        // Center on Southeast Asia / Equator initially
        map = L.map('map').setView([-2.5, 118], 4);
        
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);

        markerLayerGroup = L.layerGroup().addTo(map);
    }

    // Load countries list
    async function loadCountries() {
        try {
            const response = await fetch('/api/countries');
            loadedCountries = await response.json();
            
            selector.innerHTML = '<option value="">-- Pilih Negara --</option>';
            loadedCountries.forEach(country => {
                const option = document.createElement('option');
                option.value = country.iso2;
                option.dataset.lat = country.latitude;
                option.dataset.lng = country.longitude;
                option.textContent = `${country.iso2.toUpperCase()} - ${country.name}`;
                selector.appendChild(option);
            });

            // Default load Indonesia (id)
            selector.value = 'id';
            triggerCountryChange('id');
        } catch (error) {
            console.error('Failed to load countries:', error);
        }
    }

    // Trigger country change
    async function triggerCountryChange(iso2) {
        if (!iso2) return;
        
        const selectedOption = selector.options[selector.selectedIndex];
        const lat = parseFloat(selectedOption.dataset.lat);
        const lng = parseFloat(selectedOption.dataset.lng);
        
        if (!isNaN(lat) && !isNaN(lng)) {
            map.flyTo([lat, lng], 5);
        }

        try {
            // 1. Fetch weather of country to assess global hazard warning
            const weatherResponse = await fetch(`/api/countries/${iso2}`);
            const countryData = await weatherResponse.json();
            activeWeather = countryData.weather;

            // 2. Fetch ports of selected country
            const portsResponse = await fetch(`/api/ports?country_code=${iso2}`);
            currentPorts = await portsResponse.json();

            renderPortsAndHazards();
        } catch (error) {
            console.error('Failed to load country ports or weather:', error);
        }
    }

    // Render markers based on filter settings
    function renderPortsAndHazards() {
        markerLayerGroup.clearLayers();
        const searchVal = document.getElementById('port-search').value.toLowerCase();
        const showPorts = document.getElementById('toggle-ports').checked;
        const showHazards = document.getElementById('toggle-hazards').checked;

        const filteredPorts = currentPorts.filter(port => {
            return port.name.toLowerCase().includes(searchVal);
        });

        // Populate left sidebar list
        const listContainer = document.getElementById('port-list');
        listContainer.innerHTML = '';

        if (filteredPorts.length === 0) {
            listContainer.innerHTML = `<div class="text-secondary text-center fs-7 py-3">Tidak ada pelabuhan ditemukan.</div>`;
        }

        filteredPorts.forEach(port => {
            const hasHazard = activeWeather && (activeWeather.storm_risk > 40 || activeWeather.wind > 25);
            const hazardDesc = hasHazard 
                ? `Badai / Angin Kencang (${activeWeather.wind} km/h, Risiko Badai ${activeWeather.storm_risk}%)` 
                : '';

            // Add list item
            const item = document.createElement('div');
            item.className = 'port-list-item p-3 d-flex justify-content-between align-items-center';
            item.innerHTML = `
                <div>
                    <span class="fw-semibold text-white d-block" style="font-size: 0.9rem;">${port.name}</span>
                    <small class="text-secondary"><i class="fa-solid fa-barcode me-1"></i> ${port.code || 'N/A'}</small>
                </div>
                ${hasHazard ? '<span class="badge bg-danger rounded-circle p-1"><i class="fa-solid fa-triangle-exclamation" style="font-size: 0.65rem;"></i></span>' : '<i class="fa-solid fa-chevron-right text-secondary fs-7"></i>'}
            `;
            item.addEventListener('click', () => {
                map.flyTo([port.latitude, port.longitude], 8);
                openPortPopup(port, hasHazard, hazardDesc);
            });
            listContainer.appendChild(item);

            // Add marker to map
            if (hasHazard && showHazards) {
                // Render hazard marker
                const hazardIcon = L.divIcon({
                    className: 'hazard-marker',
                    html: '<i class="fa-solid fa-triangle-exclamation"></i>',
                    iconSize: [36, 36]
                });

                const m = L.marker([port.latitude, port.longitude], { icon: hazardIcon }).addTo(markerLayerGroup);
                m.bindPopup(getPopupContent(port, true, hazardDesc));
            } else if (showPorts) {
                // Render normal port marker
                const portIcon = L.divIcon({
                    className: 'port-marker',
                    html: '<i class="fa-solid fa-anchor"></i>',
                    iconSize: [30, 30]
                });

                const m = L.marker([port.latitude, port.longitude], { icon: portIcon }).addTo(markerLayerGroup);
                m.bindPopup(getPopupContent(port, false, ''));
            }
        });
    }

    function getPopupContent(port, hasHazard, hazardDesc) {
        const weatherStr = activeWeather 
            ? `${activeWeather.temp}°C, Curah Hujan ${activeWeather.rain} mm` 
            : 'N/A';
        const windStr = activeWeather ? `${activeWeather.wind} km/h` : 'N/A';

        return `
            <div style="width: 220px; font-family: 'Inter', sans-serif;">
                <h6 class="fw-bold text-white border-bottom border-secondary pb-2 mb-2 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-anchor text-primary"></i> ${port.name}
                </h6>
                <div class="d-flex flex-column gap-1 text-secondary" style="font-size: 0.8rem;">
                    <div><span class="text-white">LOCODE:</span> ${port.code || 'N/A'}</div>
                    <div><span class="text-white">Cuaca Pelabuhan:</span> ${weatherStr}</div>
                    <div><span class="text-white">Kecepatan Angin:</span> ${windStr}</div>
                    ${hasHazard ? `
                        <div class="mt-2 p-2 rounded bg-danger bg-opacity-25 border border-danger border-opacity-50 text-danger" style="font-size: 0.75rem;">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> <strong>Kerawanan:</strong> ${hazardDesc}
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
    }

    function openPortPopup(port, hasHazard, hazardDesc) {
        const pop = L.popup({ closeButton: true })
            .setLatLng([port.latitude, port.longitude])
            .setContent(getPopupContent(port, hasHazard, hazardDesc))
            .openOn(map);
    }

    // Event Listeners for Filters
    document.getElementById('port-search').addEventListener('input', renderPortsAndHazards);
    document.getElementById('toggle-ports').addEventListener('change', renderPortsAndHazards);
    document.getElementById('toggle-hazards').addEventListener('change', renderPortsAndHazards);
    
    selector.addEventListener('change', (e) => {
        triggerCountryChange(e.target.value);
    });

    document.addEventListener('DOMContentLoaded', () => {
        initMap();
        loadCountries();
    });
</script>
@endsection
