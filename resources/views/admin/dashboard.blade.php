@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid fade-in-up">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fs-3 fw-bold mb-1" style="font-family: 'Outfit';">Admin Panel</h1>
            <p class="text-secondary">Kelola data pengguna, dataset pelabuhan dunia, dan artikel analisis risiko rantai pasok.</p>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary fs-8 uppercase d-block mb-1">Total Pengguna</span>
                    <h3 class="fw-bold mb-0 text-white">128</h3>
                </div>
                <div class="bg-primary bg-opacity-20 text-primary p-3 rounded-3">
                    <i class="fa-solid fa-users fs-4"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary fs-8 uppercase d-block mb-1">Pelabuhan Terdaftar</span>
                    <h3 class="fw-bold mb-0 text-white">3,452</h3>
                </div>
                <div class="bg-info bg-opacity-20 text-info p-3 rounded-3">
                    <i class="fa-solid fa-anchor fs-4"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary fs-8 uppercase d-block mb-1">Artikel Analisis</span>
                    <h3 class="fw-bold mb-0 text-white">42</h3>
                </div>
                <div class="bg-warning bg-opacity-20 text-warning p-3 rounded-3">
                    <i class="fa-solid fa-book-open fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation Tabs for Admin -->
    <div class="glass-card p-4">
        <ul class="nav nav-tabs border-secondary mb-4 gap-2" id="adminTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link text-white active border-0 bg-transparent" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab" aria-controls="users" aria-selected="true">
                    <i class="fa-solid fa-user-group me-2"></i> Pengguna
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-secondary border-0 bg-transparent" id="ports-tab" data-bs-toggle="tab" data-bs-target="#ports" type="button" role="tab" aria-controls="ports" aria-selected="false">
                    <i class="fa-solid fa-ship me-2"></i> Dataset Pelabuhan
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-secondary border-0 bg-transparent" id="articles-tab" data-bs-toggle="tab" data-bs-target="#articles" type="button" role="tab" aria-controls="articles" aria-selected="false">
                    <i class="fa-solid fa-newspaper me-2"></i> Artikel Analisis
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="adminTabsContent">
            <!-- Tab: Users -->
            <div class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold text-white mb-0">Daftar Pengguna Sistem</h6>
                    <button class="btn btn-primary btn-sm fs-8 py-1.5 px-3"><i class="fa-solid fa-user-plus me-1"></i> Tambah User</button>
                </div>
                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-white fw-semibold">Super Administrator</td>
                                <td>admin@riskintel.local</td>
                                <td><span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-30">Admin</span></td>
                                <td><span class="badge bg-success text-success-light">Aktif</span></td>
                                <td class="text-end">
                                    <button class="btn btn-outline-secondary btn-sm border-0 fs-8"><i class="fa-regular fa-edit text-white"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-white fw-semibold">Ziky Ramadhan</td>
                                <td>ziky@monitoring.net</td>
                                <td><span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-30">Pengguna</span></td>
                                <td><span class="badge bg-success text-success-light">Aktif</span></td>
                                <td class="text-end">
                                    <button class="btn btn-outline-secondary btn-sm border-0 fs-8"><i class="fa-regular fa-edit text-white"></i></button>
                                    <button class="btn btn-outline-danger btn-sm border-0 fs-8"><i class="fa-regular fa-trash-can"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-white fw-semibold">Client SupplyChain Ltd</td>
                                <td>client@supplychain.com</td>
                                <td><span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-30">Pengguna</span></td>
                                <td><span class="badge bg-secondary text-secondary-light">Suspended</span></td>
                                <td class="text-end">
                                    <button class="btn btn-outline-secondary btn-sm border-0 fs-8"><i class="fa-regular fa-edit text-white"></i></button>
                                    <button class="btn btn-outline-danger btn-sm border-0 fs-8"><i class="fa-regular fa-trash-can"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab: Ports -->
            <div class="tab-pane fade" id="ports" role="tabpanel" aria-labelledby="ports-tab">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold text-white mb-0">Dataset Pelabuhan Utama</h6>
                    <button class="btn btn-primary btn-sm fs-8 py-1.5 px-3"><i class="fa-solid fa-plus me-1"></i> Tambah Pelabuhan</button>
                </div>
                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>Nama Pelabuhan</th>
                                <th>Negara</th>
                                <th>Koordinat (Lat, Lng)</th>
                                <th>Tingkat Kemacetan</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-white fw-semibold">Port of Tanjung Priok</td>
                                <td>Indonesia</td>
                                <td><code>-6.1033, 106.8911</code></td>
                                <td><span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-30">Rendah</span></td>
                                <td class="text-end">
                                    <button class="btn btn-outline-secondary btn-sm border-0 fs-8"><i class="fa-regular fa-edit text-white"></i></button>
                                    <button class="btn btn-outline-danger btn-sm border-0 fs-8"><i class="fa-regular fa-trash-can"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-white fw-semibold">Port of Shanghai</td>
                                <td>China</td>
                                <td><code>30.6267, 122.0633</code></td>
                                <td><span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-30">Sangat Tinggi</span></td>
                                <td class="text-end">
                                    <button class="btn btn-outline-secondary btn-sm border-0 fs-8"><i class="fa-regular fa-edit text-white"></i></button>
                                    <button class="btn btn-outline-danger btn-sm border-0 fs-8"><i class="fa-regular fa-trash-can"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-white fw-semibold">Port of Hamburg</td>
                                <td>Jerman</td>
                                <td><code>53.5458, 9.9658</code></td>
                                <td><span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-30">Rendah</span></td>
                                <td class="text-end">
                                    <button class="btn btn-outline-secondary btn-sm border-0 fs-8"><i class="fa-regular fa-edit text-white"></i></button>
                                    <button class="btn btn-outline-danger btn-sm border-0 fs-8"><i class="fa-regular fa-trash-can"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab: Articles -->
            <div class="tab-pane fade" id="articles" role="tabpanel" aria-labelledby="articles-tab">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold text-white mb-0">Kelola Artikel Analisis Risiko</h6>
                    <button class="btn btn-primary btn-sm fs-8 py-1.5 px-3"><i class="fa-solid fa-plus me-1"></i> Tulis Artikel</button>
                </div>
                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>Judul Artikel</th>
                                <th>Kategori</th>
                                <th>Penulis</th>
                                <th>Tanggal Rilis</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-white fw-semibold text-truncate" style="max-width: 250px;">Dampak Badai Tropis terhadap Kecepatan Pengiriman Jalur Asia-Pasifik</td>
                                <td>Logistik</td>
                                <td>Admin Utama</td>
                                <td>08 Juli 2026</td>
                                <td class="text-end">
                                    <button class="btn btn-outline-secondary btn-sm border-0 fs-8"><i class="fa-regular fa-edit text-white"></i></button>
                                    <button class="btn btn-outline-danger btn-sm border-0 fs-8"><i class="fa-regular fa-trash-can"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-white fw-semibold text-truncate" style="max-width: 250px;">Fluktuasi Inflasi Negara Asal Impor dan Implikasinya bagi Importir</td>
                                <td>Ekonomi</td>
                                <td>Ziky Ramadhan</td>
                                <td>05 Juli 2026</td>
                                <td class="text-end">
                                    <button class="btn btn-outline-secondary btn-sm border-0 fs-8"><i class="fa-regular fa-edit text-white"></i></button>
                                    <button class="btn btn-outline-danger btn-sm border-0 fs-8"><i class="fa-regular fa-trash-can"></i></button>
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
    // Tab styling toggle class helper
    document.querySelectorAll('#adminTabs button').forEach(btn => {
        btn.addEventListener('click', function(e) {
            document.querySelectorAll('#adminTabs button').forEach(b => {
                b.classList.remove('text-white');
                b.classList.add('text-secondary');
            });
            e.target.classList.remove('text-secondary');
            e.target.classList.add('text-white');
        });
    });

    // Load admin panel data
    async function loadAdminData() {
        try {
            const response = await fetch('/api/admin/dashboard');
            const data = await response.json();

            // 1. Update quick stats counts
            document.querySelector('.row.g-3.mb-4 > div:nth-child(1) h3').textContent = data.users.length;
            document.querySelector('.row.g-3.mb-4 > div:nth-child(2) h3').textContent = data.ports_count;
            document.querySelector('.row.g-3.mb-4 > div:nth-child(3) h3').textContent = data.articles.length;

            // 2. Render Users Table
            const usersTbody = document.querySelector('#users table tbody');
            usersTbody.innerHTML = '';
            data.users.forEach(user => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="text-white fw-semibold">${user.name}</td>
                    <td>${user.email}</td>
                    <td><span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-30">${user.email === 'admin@riskintel.local' ? 'Admin' : 'Pengguna'}</span></td>
                    <td><span class="badge bg-success text-success-light">Aktif</span></td>
                    <td class="text-end">
                        <button class="btn btn-outline-secondary btn-sm border-0 fs-8"><i class="fa-regular fa-edit text-white"></i></button>
                    </td>
                `;
                usersTbody.appendChild(tr);
            });

            // 3. Render Ports Table (Fetch first 15 ports from database to display)
            const portsResponse = await fetch('/api/ports?country_code=id');
            const ports = await portsResponse.json();
            const portsTbody = document.querySelector('#ports table tbody');
            portsTbody.innerHTML = '';
            ports.forEach(port => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="text-white fw-semibold">${port.name}</td>
                    <td>${port.country.name}</td>
                    <td><code>${parseFloat(port.latitude).toFixed(4)}, ${parseFloat(port.longitude).toFixed(4)}</code></td>
                    <td><span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-30">Normal</span></td>
                    <td class="text-end">
                        <button class="btn btn-outline-danger btn-sm border-0 fs-8" onclick="deletePort(${port.id})"><i class="fa-regular fa-trash-can"></i></button>
                    </td>
                `;
                portsTbody.appendChild(tr);
            });

            // 4. Render Articles Table
            const articlesTbody = document.querySelector('#articles table tbody');
            articlesTbody.innerHTML = '';
            if (data.articles.length === 0) {
                articlesTbody.innerHTML = `<tr><td colspan="5" class="text-center text-secondary py-3 fs-7">Belum ada artikel analisis.</td></tr>`;
            } else {
                data.articles.forEach(art => {
                    const tr = document.createElement('tr');
                    const pubDate = new Date(art.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                    tr.innerHTML = `
                        <td class="text-white fw-semibold text-truncate" style="max-width: 250px;">${art.title}</td>
                        <td>${art.category}</td>
                        <td>${art.author ? art.author.name : 'Unknown'}</td>
                        <td>${pubDate}</td>
                        <td class="text-end">
                            <button class="btn btn-outline-danger btn-sm border-0 fs-8" onclick="deleteArticle(${art.id})"><i class="fa-regular fa-trash-can"></i></button>
                        </td>
                    `;
                    articlesTbody.appendChild(tr);
                });
            }

        } catch (error) {
            console.error('Failed to load admin dashboard data:', error);
        }
    }

    // Delete Port Action
    async function deletePort(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus pelabuhan ini?')) return;
        try {
            const response = await fetch(`/api/admin/ports/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            if (response.ok) {
                loadAdminData();
            }
        } catch (error) {
            console.error('Failed to delete port:', error);
        }
    }

    // Delete Article Action
    async function deleteArticle(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus artikel ini?')) return;
        try {
            const response = await fetch(`/api/admin/articles/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            if (response.ok) {
                loadAdminData();
            }
        } catch (error) {
            console.error('Failed to delete article:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadAdminData();
    });
</script>
@endsection
