<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $config['site_name']; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.7/css/dataTables.bootstrap5.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --light-color: #ecf0f1;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            z-index: 999;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }
        
        .navbar-brand i {
            color: var(--secondary-color);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/" style="color: #f8f9fa !important;">
                <i class="bi bi-geo-alt-fill me-2"></i>
                <?= $config['site_name']; ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/" data-page="home">
                        <i class="bi bi-map me-1"></i> Peta
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php" data-page="about">
                        <i class="bi bi-info-circle me-1"></i> Tentang
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#helpModal" data-page="help">
                        <i class="bi bi-question-circle me-1"></i> Bantuan
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" 
                    data-bs-toggle="dropdown" data-page="admin">
                        <i class="bi bi-gear me-1"></i>Admin
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/admin.php" data-page="admin-kelola"><i class="bi bi-file-earmark-text me-2"></i>Kelola Data Kantor</a></li>
                        <li><a class="dropdown-item" href="#" onclick="exportToCSV()" data-page="admin-export"><i class="bi bi-box-arrow-up me-2"></i>Export CSV</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/admin.php?logout=1" data-page="admin-logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
            </div>
        </div>
    </nav>

    <!-- Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-question-circle me-2"></i>Panduan Penggunaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6>Fitur-fitur WebGIS BPN:</h6>
                    <ul>
                        <li><strong>Pencarian:</strong> Cari kantor berdasarkan nama, alamat, atau kota</li>
                        <li><strong>Filter:</strong> Filter kantor berdasarkan provinsi dan jenis kantor</li>
                        <li><strong>Layer Peta:</strong> Ganti tampilan peta dasar sesuai kebutuhan</li>
                        <li><strong>Lokasi Saat Ini:</strong> Tampilkan lokasi Anda saat ini di peta</li>
                        <li><strong>Export Data:</strong> Ekspor data kantor ke format CSV</li>
                        <li><strong>Rute:</strong> Cari rute dari lokasi Anda ke kantor BPN</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>