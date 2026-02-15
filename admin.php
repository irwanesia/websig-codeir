<?php
// Cek apakah user sudah login (sederhana)
session_start();
if (!isset($_SESSION['logged_in'])) {
    $_SESSION['logged_in'] = false;
}

require_once 'config/config.php';
require_once 'includes/header.php';


// Proses form submission
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Login sederhana
        if ($_POST['username'] === 'admin' && $_POST['password'] === 'bpn2026') {
            $_SESSION['logged_in'] = true;
            $message = 'Login berhasil!';
            $messageType = 'success';
        } else {
            $message = 'Username atau password salah!';
            $messageType = 'danger';
        }
    } elseif (isset($_POST['logout'])) {
        $_SESSION['logged_in'] = false;
        $message = 'Logout berhasil!';
        $messageType = 'success';
    } elseif ($_SESSION['logged_in']) {
        // Handle CRUD operations
        $action = $_POST['action'] ?? '';

        // Baca file JSON
        $jsonFile = 'data/offices.json';
        $jsonString = file_get_contents($jsonFile);
        $data = json_decode($jsonString, true);

        switch ($action) {
            case 'add':
                // Tambah data baru
                $newOffice = [
                    'id' => 'BPN' . str_pad(count($data['offices']) + 1, 3, '0', STR_PAD_LEFT),
                    'name' => $_POST['name'],
                    'address' => $_POST['address'],
                    'city' => $_POST['city'],
                    'province' => $_POST['province'],
                    'latitude' => floatval($_POST['latitude']),
                    'longitude' => floatval($_POST['longitude']),
                    'phone' => $_POST['phone'],
                    'email' => $_POST['email'],
                    'type' => $_POST['type'],
                    'description' => $_POST['description']
                ];

                $data['offices'][] = $newOffice;
                $message = 'Data kantor berhasil ditambahkan!';
                $messageType = 'success';
                break;

            case 'edit':
                // Edit data yang ada
                $id = $_POST['id'];
                foreach ($data['offices'] as &$office) {
                    if ($office['id'] === $id) {
                        $office['name'] = $_POST['name'];
                        $office['address'] = $_POST['address'];
                        $office['city'] = $_POST['city'];
                        $office['province'] = $_POST['province'];
                        $office['latitude'] = floatval($_POST['latitude']);
                        $office['longitude'] = floatval($_POST['longitude']);
                        $office['phone'] = $_POST['phone'];
                        $office['email'] = $_POST['email'];
                        $office['type'] = $_POST['type'];
                        $office['description'] = $_POST['description'];
                        break;
                    }
                }
                $message = 'Data kantor berhasil diupdate!';
                $messageType = 'success';
                break;

            case 'delete':
                // Hapus data
                $id = $_POST['id'];
                $data['offices'] = array_values(array_filter($data['offices'], function ($office) use ($id) {
                    return $office['id'] !== $id;
                }));
                $message = 'Data kantor berhasil dihapus!';
                $messageType = 'success';
                break;
        }

        // Simpan kembali ke file JSON
        file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
    }
}

// Ambil data untuk ditampilkan
$offices = [];
if (file_exists('data/offices.json')) {
    $jsonString = file_get_contents('data/offices.json');
    $data = json_decode($jsonString, true);
    $offices = $data['offices'] ?? [];
}
?>

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="bi bi-house-door me-1"></i>Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Admin Data Kantor</li>
        </ol>
    </nav>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
            <i class="bi bi-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!$_SESSION['logged_in']): ?>
        <!-- Login Form -->
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Login Admin</h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-person me-2"></i>Username</label>
                                <input type="text" name="username" class="form-control" required placeholder="admin">
                            </div>
                            <div class="mb-4">
                                <label class="form-label"><i class="bi bi-key me-2"></i>Password</label>
                                <input type="password" name="password" class="form-control" required placeholder="bpn2026">
                                <small class="text-muted">Gunakan username: admin, password: bpn2026</small>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary w-100 py-2">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Admin Panel -->
        <div class="row mb-4">
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Panel Admin</h5>
                        <form method="POST" class="d-inline">
                            <button type="submit" name="logout" class="btn btn-light btn-sm">
                                <i class="bi bi-box-arrow-right me-1"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Tambah/Edit -->
        <div class="row mb-4">
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Tambah Kantor Baru</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="" id="officeForm">
                            <input type="hidden" name="action" value="add" id="formAction">
                            <input type="hidden" name="id" id="officeId" value="">
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Kantor <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tipe Kantor <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-select" required>
                                        <option value="">Pilih Tipe</option>
                                        <option value="Kanwil">Kanwil (Kantor Wilayah)</option>
                                        <option value="Kantah">Kantah (Kantor Pertanahan)</option>
                                        <option value="Pusat">Pusat</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Alamat <span class="text-danger">*</span></label>
                                    <textarea name="address" id="address" class="form-control" rows="2" required></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Kota <span class="text-danger">*</span></label>
                                    <input type="text" name="city" id="city" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Provinsi <span class="text-danger">*</span></label>
                                    <input type="text" name="province" id="province" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Latitude <span class="text-danger">*</span></label>
                                    <input type="number" step="0.000001" name="latitude" id="latitude" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Longitude <span class="text-danger">*</span></label>
                                    <input type="number" step="0.000001" name="longitude" id="longitude" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Telepon</label>
                                    <input type="text" name="phone" id="phone" class="form-control">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea name="description" id="description" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" id="email" class="form-control">
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-success" id="submitBtn">
                                        <i class="bi bi-save me-2"></i>Simpan Data
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="cancelEdit" onclick="resetForm()" style="display: none;">
                                        <i class="bi bi-x-circle me-2"></i>Batal Edit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="row mt-4">
            <div class="col">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-table me-2"></i>
                            Data Kantor BPN 
                            <span class="badge bg-light text-dark ms-2" id="totalOffices"><?php echo count($offices); ?></span>
                        </h5>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-light btn-sm" onclick="refreshTable()" title="Refresh data">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                            <button type="button" class="btn btn-light btn-sm" onclick="exportTableToCSV()" title="Export ke CSV">
                                <i class="bi bi-download"></i>
                            </button>
                            <button type="button" class="btn btn-light btn-sm" onclick="printTable()" title="Print tabel">
                                <i class="bi bi-printer"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Search and Filter Bar -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" class="form-control" id="tableSearch" 
                                        placeholder="Cari nama kantor, alamat, kota..." 
                                        onkeyup="searchTable()">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="provinceFilter" onchange="filterTable()">
                                    <option value="">Semua Provinsi</option>
                                    <?php
                                    $provinces = array_unique(array_column($offices, 'province'));
        sort($provinces);
        foreach ($provinces as $province):
            ?>
                                        <option value="<?php echo htmlspecialchars($province); ?>">
                                            <?php echo htmlspecialchars($province); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="typeFilter" onchange="filterTable()">
                                    <option value="">Semua Tipe</option>
                                    <option value="Kanwil">Kanwil</option>
                                    <option value="Kantah">Kantah</option>
                                    <option value="Pusat">Pusat</option>
                                </select>
                            </div>
                        </div>

                        <!-- Table with responsive wrapper -->
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-hover table-striped table-bordered mb-0" id="officesTable">
                                <thead class="table-dark sticky-top">
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th width="10%">ID</th>
                                        <th width="20%">Nama Kantor</th>
                                        <th width="25%">Alamat</th>
                                        <th width="10%">Kota</th>
                                        <th width="10%">Provinsi</th>
                                        <th width="10%">Telepon</th>
                                        <th width="5%">Tipe</th>
                                        <th width="5%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <?php if (empty($offices)): ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <i class="bi bi-inbox fs-1 d-block text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum ada data kantor</h5>
                                                <p class="text-muted">Gunakan form di atas untuk menambah data baru</p>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php $no = 1; ?>
                                        <?php foreach ($offices as $office): ?>
                                        <tr>
                                            <td class="text-center"><?php echo $no++; ?></td>
                                            <td><small class="text-muted"><?php echo htmlspecialchars($office['id']); ?></small></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($office['name']); ?></strong>
                                                <?php if (!empty($office['description'])): ?>
                                                    <br>
                                                    <small class="text-muted"><?php echo htmlspecialchars(substr($office['description'], 0, 50)) . '...'; ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <i class="bi bi-geo-alt me-1 text-primary"></i>
                                                <?php echo htmlspecialchars($office['address']); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($office['city']); ?></td>
                                            <td><?php echo htmlspecialchars($office['province']); ?></td>
                                            <td>
                                                <?php if (!empty($office['phone'])): ?>
                                                    <i class="bi bi-telephone me-1 text-success"></i>
                                                    <?php echo htmlspecialchars($office['phone']); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge <?php
                            echo $office['type'] === 'Kanwil' ? 'bg-danger' :
                                ($office['type'] === 'Pusat' ? 'bg-warning text-dark' : 'bg-success');
                                            ?>">
                                                    <?php echo $office['type']; ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button class="btn btn-outline-primary" 
                                                            onclick="editOffice(<?php echo htmlspecialchars(json_encode($office)); ?>)"
                                                            title="Edit data">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <!-- <button class="btn btn-outline-info" 
                                                            onclick="viewOffice(<?php // echo htmlspecialchars(json_encode($office));?>)"
                                                            title="Lihat detail">
                                                        <i class="bi bi-eye"></i>
                                                    </button> -->
                                                    <button class="btn btn-outline-danger" 
                                                            onclick="deleteOffice('<?php echo $office['id']; ?>')"
                                                            title="Hapus data">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="9" class="text-end">
                                            <small class="text-muted">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Total: <span id="rowCount"><?php echo count($offices); ?></span> data
                                            </small>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Table Info -->
                        <div class="row mt-3 align-items-center">
                            <div class="col-md-6">
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-layers me-1"></i>
                                    Menampilkan <span id="displayCount"><?php echo count($offices); ?></span> dari 
                                    <span id="totalCount"><?php echo count($offices); ?></span> data
                                </p>
                            </div>
                            <div class="col-md-6 text-end">
                                <button class="btn btn-sm btn-outline-secondary" onclick="resetTableFilters()">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Reset Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Delete Form (hidden) -->
<form method="POST" id="deleteForm" style="display: none;">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" id="deleteId">
</form>

<script>
function editOffice(office) {
    // Ubah action menjadi edit
    document.getElementById('formAction').value = 'edit';
    document.getElementById('officeId').value = office.id;
    
    // Isi form dengan data yang ada
    document.getElementById('name').value = office.name;
    document.getElementById('type').value = office.type;
    document.getElementById('address').value = office.address;
    document.getElementById('city').value = office.city;
    document.getElementById('province').value = office.province;
    document.getElementById('latitude').value = office.latitude;
    document.getElementById('longitude').value = office.longitude;
    document.getElementById('phone').value = office.phone || '';
    document.getElementById('email').value = office.email || '';
    document.getElementById('description').value = office.description || '';
    
    // Ubah tombol submit
    document.getElementById('submitBtn').innerHTML = '<i class="bi bi-pencil-square me-2"></i>Update Data';
    document.getElementById('submitBtn').className = 'btn btn-warning';
    
    // Tampilkan tombol batal
    document.getElementById('cancelEdit').style.display = 'inline-block';
    
    // Scroll ke form
    document.getElementById('officeForm').scrollIntoView({ behavior: 'smooth' });
}

function resetForm() {
    // Reset form ke mode tambah
    document.getElementById('formAction').value = 'add';
    document.getElementById('officeId').value = '';
    document.getElementById('officeForm').reset();
    
    // Reset tombol
    document.getElementById('submitBtn').innerHTML = '<i class="bi bi-save me-2"></i>Simpan Data';
    document.getElementById('submitBtn').className = 'btn btn-success';
    document.getElementById('cancelEdit').style.display = 'none';
}

function deleteOffice(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').submit();
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>