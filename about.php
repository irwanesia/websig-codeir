<?php
require_once 'config/config.php';
require_once 'includes/header.php';
?>

<!-- About Page Content -->
<main class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="bi bi-house-door me-1"></i>Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tentang Kami</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Left Column - Main Info -->
        <div class="col-lg-8">
            <!-- About Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-info-circle me-2"></i>Tentang Sistem Informasi Geografis (SIG) BPN</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="/assets/images/logo-bpn.png" alt="Logo BPN" class="img-fluid" style="max-height: 100px;" onerror="this.style.display='none'">
                        <h2 class="mt-3">WebGIS Kantor BPN Seluruh Indonesia</h2>
                        <p class="lead text-muted">Memudahkan Pencarian dan Informasi Lokasi Kantor Badan Pertanahan Nasional</p>
                    </div>
                    
                    <div class="about-description">
                        <p>Sistem Informasi Geografis (SIG) ini dikembangkan untuk memetakan dan menyediakan informasi lengkap mengenai lokasi kantor Badan Pertanahan Nasional (BPN) di seluruh Indonesia. Dengan sistem ini, masyarakat dapat dengan mudah menemukan kantor BPN terdekat beserta informasi kontak dan layanan yang tersedia.</p>
                        
                        <p>Aplikasi ini menggunakan teknologi open-source OpenStreetMap untuk menampilkan peta interaktif, sehingga dapat diakses secara gratis tanpa biaya lisensi.</p>
                    </div>
                </div>
            </div>
            
            <!-- Features Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-stars me-2"></i>Fitur Utama</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-map fs-1 text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6>Peta Interaktif</h6>
                                    <p class="small text-muted">Peta Indonesia menggunakan OpenStreetMap dengan berbagai layer tampilan</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-geo-alt fs-1 text-danger"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6>Lokasi Kantor</h6>
                                    <p class="small text-muted">Menampilkan titik lokasi kantor BPN seluruh Indonesia</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-info-circle fs-1 text-info"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6>Informasi Detail</h6>
                                    <p class="small text-muted">Informasi lengkap setiap kantor (nama, alamat, telepon, email)</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-search fs-1 text-warning"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6>Pencarian & Filter</h6>
                                    <p class="small text-muted">Cari kantor berdasarkan nama, kota, provinsi, atau jenis kantor</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-download fs-1 text-success"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6>Export Data</h6>
                                    <p class="small text-muted">Ekspor data kantor ke format CSV untuk keperluan analisis</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-phone fs-1 text-secondary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6>Responsif</h6>
                                    <p class="small text-muted">Tampilan yang responsif dan user-friendly di semua perangkat</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Technology Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-cpu me-2"></i>Teknologi yang Digunakan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-transparent">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    PHP Native (Tanpa Database)
                                </li>
                                <li class="list-group-item bg-transparent">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    OpenStreetMap + Leaflet JS
                                </li>
                                <li class="list-group-item bg-transparent">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Bootstrap 5
                                </li>
                                <li class="list-group-item bg-transparent">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    JSON untuk Penyimpanan Data
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-transparent">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    HTML5, CSS3, JavaScript
                                </li>
                                <li class="list-group-item bg-transparent">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Bootstrap Icons
                                </li>
                                <li class="list-group-item bg-transparent">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Git Version Control
                                </li>
                                <li class="list-group-item bg-transparent">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Apache / Nginx Web Server
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Sidebar -->
        <div class="col-lg-4">
            <!-- Statistics Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Statistik Data</h5>
                </div>
                <div class="card-body">
                    <!-- Di bagian statistik about.php, pastikan ID-nya benar -->
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-primary mb-0" id="aboutTotalOffices">0</h3>
                                <small class="text-muted">Total Kantor</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-success mb-0" id="aboutTotalProvinces">0</h3>
                                <small class="text-muted">Provinsi</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-info mb-0" id="aboutTotalTypes">0</h3>
                                <small class="text-muted">Jenis Kantor</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-warning mb-0"><?php echo $config['version']; ?></h3>
                                <small class="text-muted">Versi</small>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <p class="small text-muted mb-0">
                        <i class="bi bi-calendar me-1"></i>
                        Data diperbarui: 
                        <?php
                        $dataFile = $config['data_file'] ?? 'data/offices.json';
if (file_exists($dataFile)) {
    echo date('d F Y H:i', filemtime($dataFile));
} else {
    echo 'Belum tersedia';
}
?>
                    </p>
                </div>
            </div>
            
            <!-- Contact Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-telephone me-2"></i>Kontak</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-envelope-fill fs-4 me-3 text-primary"></i>
                        <div>
                            <small class="text-muted">Email</small>
                            <br>
                            <a href="mailto:info@bpn.go.id" class="text-decoration-none">info@bpn.go.id</a>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-telephone-fill fs-4 me-3 text-success"></i>
                        <div>
                            <small class="text-muted">Telepon</small>
                            <br>
                            <span>(021) 7393939</span>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-globe fs-4 me-3 text-info"></i>
                        <div>
                            <small class="text-muted">Website</small>
                            <br>
                            <a href="https://www.bpn.go.id" target="_blank" class="text-decoration-none">www.bpn.go.id</a>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <i class="bi bi-geo-alt-fill fs-4 me-3 text-danger"></i>
                        <div>
                            <small class="text-muted">Kantor Pusat</small>
                            <br>
                            <span class="small">Jl. Sisingamangaraja No. 2, Kebayoran Baru, Jakarta Selatan</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Call to Action -->
            <div class="card shadow-sm bg-primary text-white">
                <div class="card-body text-center">
                    <i class="bi bi-map fs-1 mb-3 d-block"></i>
                    <h5>Temukan Kantor BPN Terdekat</h5>
                    <p class="small">Gunakan fitur pencarian dan filter untuk menemukan kantor BPN di wilayah Anda.</p>
                    <a href="/" class="btn btn-light">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Peta
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Update about page statistics
document.addEventListener('DOMContentLoaded', function() {
    function updateAboutStats() {
        if (typeof allOffices !== 'undefined' && allOffices && allOffices.length > 0) {
            // Total offices
            const totalEl = document.getElementById('aboutTotalOffices');
            if (totalEl) totalEl.textContent = allOffices.length;
            
            // Total provinces
            const provinces = [...new Set(allOffices.map(office => office.province).filter(p => p))];
            const provincesEl = document.getElementById('aboutTotalProvinces');
            if (provincesEl) provincesEl.textContent = provinces.length;
            
            // Total types
            const types = [...new Set(allOffices.map(office => office.type).filter(t => t))];
            const typesEl = document.getElementById('aboutTotalTypes');
            if (typesEl) typesEl.textContent = types.length;
        } else {
            setTimeout(updateAboutStats, 1000);
        }
    }
    
    updateAboutStats();
});
</script>

<?php require_once 'includes/footer.php'; ?>