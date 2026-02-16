<?php
require_once 'config/config.php';
require_once 'includes/header.php';
?>

<!-- Main Content -->
<main class="container-fluid px-4 py-3">
    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-filter me-2"></i>Filter & Pencarian</h5>
                </div>
                <div class="card-body">
                    <!-- Search Box -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Cari Kantor BPN:</label>
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control" 
                                   placeholder="Nama, alamat, kota...">
                            <button class="btn btn-primary" id="searchBtn">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Filter Provinsi -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Provinsi:</label>
                        <select id="provinceFilter" class="form-select">
                            <option value="">Semua Provinsi</option>
                            <!-- Options akan diisi oleh JavaScript -->
                        </select>
                    </div>
                    
                    <!-- Filter Jenis Kantor -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Kantor:</label>
                        <select id="officeTypeFilter" class="form-select">
                            <option value="">Semua Jenis</option>
                            <option value="Kantor Wilayah">Kantor Wilayah</option>
                            <option value="Kantor Pertanahan">Kantor Pertanahan</option>
                            <option value="Kantor Pelayanan">Kantor Pelayanan</option>
                        </select>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 mb-3">
                        <button class="btn btn-success" id="resetBtn">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset Filter
                        </button>
                        <button class="btn btn-outline-primary" id="currentLocationBtn">
                            <i class="bi bi-geo-alt me-2"></i>Lokasi Saya
                        </button>
                        <button class="btn btn-outline-success" id="exportBtn">
                            <i class="bi bi-download me-2"></i>Export CSV
                        </button>
                    </div>
                    
                    <!-- Statistics -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="fw-semibold"><i class="bi bi-bar-chart me-2"></i>Statistik</h6>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="stat-box bg-light p-2 rounded">
                                    <h4 class="mb-0" id="totalOffices">0</h4>
                                    <small class="text-muted">Total Kantor</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-box bg-light p-2 rounded">
                                    <h4 class="mb-0" id="visibleOffices">0</h4>
                                    <small class="text-muted">Tampil</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Map Layers -->
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-layers me-2"></i>Layer Peta</h6>
                </div>
                <div class="card-body">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="mapLayer" 
                               id="layerOSM" value="osm" checked>
                        <label class="form-check-label" for="layerOSM">
                            OpenStreetMap Standard
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="mapLayer" 
                               id="layerTopo" value="topo">
                        <label class="form-check-label" for="layerTopo">
                            Topografi
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="mapLayer" 
                               id="layerSatellite" value="satellite">
                        <label class="form-check-label" for="layerSatellite">
                            Satellite
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Map Area -->
        <div class="col-lg-9">
            <!-- Map Header -->
            <div class="card shadow-sm mb-3">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-0 text-primary">
                                <i class="bi bi-geo-alt me-2"></i>
                                Peta Kantor BPN Seluruh Indonesia
                            </h4>
                            <p class="text-muted mb-0" id="mapInfo">
                                <span class="badge bg-success">
                                    <i class="bi bi-circle-fill me-1"></i>
                                    OpenStreetMap
                                </span>
                                <span class="ms-2" id="loadingStatus">Memuat data...</span>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="btn-group" role="group">
                                <button class="btn btn-outline-primary" id="zoomInBtn">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                                <button class="btn btn-outline-primary" id="zoomOutBtn">
                                    <i class="bi bi-dash-lg"></i>
                                </button>
                                <button class="btn btn-outline-primary" id="fullExtentBtn">
                                    <i class="bi bi-fullscreen"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Map Container -->
            <div class="card shadow-sm">
                <div class="card-body p-0 position-relative">
                    <div id="map" style="height: 600px; border-radius: 0.375rem;"></div>
                    
                    <!-- Loading Overlay -->
                    <div id="mapLoading" class="map-loading-overlay">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat peta...</p>
                    </div>
                    
                    <!-- Map Controls -->
                    <div class="map-controls" style="margin-top: 50px;">
                        <div class="btn-group-vertical shadow-sm">
                            <button class="btn btn-light" id="locateBtn" title="Lokasi Saya">
                                <i class="bi bi-geo"></i>
                            </button>
                            <button class="btn btn-light" id="routeBtn" title="Cari Rute">
                                <i class="bi bi-signpost"></i>
                            </button>
                            <button class="btn btn-light" id="legendToggle" title="Legenda">
                                <i class="bi bi-list-ul"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Legend -->
                    <div id="mapLegend" class="map-legend card shadow-sm" style="display: block;">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-palette me-2"></i>Legenda</h6>
                        </div>
                        <div class="card-body">
                            <div class="legend-item mb-2" style="font-size: 12px;">
                                <span class="legend-color" style="background-color: #e74c3c;"></span>
                                <span>Kantor Wilayah (Kanwil)</span>
                            </div>
                            <div class="legend-item mb-2" style="font-size: 12px;">
                                <span class="legend-color" style="background-color: #3498db;"></span>
                                <span>Kantor Pertanahan (Kantah)</span>
                            </div>
                            <div class="legend-item" style="font-size: 12px;">
                                <span class="legend-color" style="background-color: #2ecc71;"></span>
                                <span>Kantor Pusat</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Office List -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-building me-2"></i>
                        Daftar Kantor BPN
                        <span class="badge bg-light text-dark ms-2" id="officeCount">0</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-hover table-striped table-bordered mb-0" id="officesTable">
                            <thead class="table-dark sticky-top">
                                <tr>
                                    <th>Nama Kantor</th>
                                    <th>Alamat</th>
                                    <th>Kota/Kab</th>
                                    <th>Telepon</th>
                                    <th>Jenis</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="officeTableBody">
                                <!-- Data akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    <div id="officeLoading" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data kantor...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Office Detail Modal -->
<div class="modal fade" id="officeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="officeModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-geo-alt me-2"></i>Informasi Kantor</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td id="officeAddress"></td>
                            </tr>
                            <tr>
                                <td><strong>Telepon</strong></td>
                                <td id="officePhone"></td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td id="officeEmail"></td>
                            </tr>
                            <tr>
                                <td><strong>Jam Operasional</strong></td>
                                <td id="officeHours"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-map me-2"></i>Lokasi</h6>
                        <div id="officeMiniMap" style="height: 200px; border-radius: 0.375rem;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Pastikan tombol di modal menggunakan onclick dengan benar -->
                <!-- Di modal, pastikan ID-nya modalRouteBtn -->
                <button class="btn btn-primary" id="modalRouteBtn" onclick="window.handleModalRouteClick()">
                    <i class="bi bi-signpost me-2"></i>Cari Rute
                </button>
                <!-- <button class="btn btn-primary" onclick="window.handleModalRouteClick()">
                    <i class="bi bi-signpost me-2"></i>Cari Rute
                </button> -->
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>