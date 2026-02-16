    <!-- Footer Section -->
    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <!-- About Section -->
                <div class="col-md-4 mb-3">
                    <h5 class="text-uppercase mb-3">
                        <i class="bi bi-geo-alt-fill me-2 text-primary"></i>
                        WebGIS BPN
                    </h5>
                    <p class="small text-white-50">
                        Sistem Informasi Geografis Kantor Badan Pertanahan Nasional 
                        Seluruh Indonesia. Memudahkan pencarian lokasi kantor BPN 
                        dengan peta interaktif.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div class="col-md-2 mb-3">
                    <h5 class="text-uppercase mb-3">Menu</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="/" class="text-white-50 text-decoration-none">
                                <i class="bi bi-house-door me-2"></i>Beranda
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="/about.php" class="text-white-50 text-decoration-none">
                                <i class="bi bi-info-circle me-2"></i>Tentang
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#helpModal" class="text-white-50 text-decoration-none">
                                <i class="bi bi-question-circle me-2"></i>Bantuan
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Statistics -->
                <div class="col-md-3 mb-3">
                    <h5 class="text-uppercase mb-3">Statistik</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-building me-2 text-primary"></i>
                            <span class="text-white-50">Total Kantor: </span>
                            <span class="fw-bold" id="footerTotalOffices">0</span>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-pin-map me-2 text-success"></i>
                            <span class="text-white-50">Provinsi: </span>
                            <span class="fw-bold" id="footerTotalProvinces">0</span>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-arrow-repeat me-2 text-info"></i>
                            <span class="text-white-50">Versi: </span>
                            <span class="fw-bold"><?php echo $config['version']; ?></span>
                        </li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div class="col-md-3 mb-3">
                    <h5 class="text-uppercase mb-3">Kontak</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-envelope me-2 text-warning"></i>
                            <a href="mailto:info@bpn.go.id" class="text-white-50 text-decoration-none">
                                info@bpn.go.id
                            </a>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-globe me-2 text-warning"></i>
                            <a href="https://www.bpn.go.id" target="_blank" class="text-white-50 text-decoration-none">
                                www.bpn.go.id
                            </a>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-telephone me-2 text-warning"></i>
                            <span class="text-white-50">(021) 7393939</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr class="border-secondary">
            
            <!-- Copyright Row -->
            <div class="row">
                <div class="col-md-6">
                    <p class="small text-white-50 mb-0">
                        <i class="bi bi-c-circle me-1"></i>
                        <?php echo date('Y'); ?> <?php echo $config['site_name']; ?>. 
                        Hak Cipta Dilindungi Undang-Undang.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="small text-white-50 mb-0">
                        <i class="bi bi-calendar me-1"></i>
                        Data diperbarui: 
                        <?php
                        $dataFile = $config['data_file'] ?? 'data/offices.json';
                            if (file_exists($dataFile)) {
                                echo date('d F Y', filemtime($dataFile));
                            } else {
                                echo 'Belum tersedia';
                            }
                            ?>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript Files - URUTAN FINAL -->
    <!-- 1. jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <!-- 2. Bootstrap Bundle (include Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- 3. DataTables -->
    <script src="https://cdn.datatables.net/2.3.7/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.7/js/dataTables.bootstrap5.js"></script>

    <!-- 4. Leaflet -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- 5. Custom JS -->
    <script src="/assets/js/main.js"></script>
    <!-- Load stats.js (HARUS setelah main.js) -->
    <script src="/assets/js/stats.js"></script>
    <script src="/assets/js/dataTableCustom.js"></script>
    <script src="/assets/js/exportCSV.js"></script>
    <script src="/assets/js/printTable.js"></script>

    <!-- 6. Inisialisasi -->
    <script>
        // Versi sederhana untuk menandai menu active
        document.addEventListener('DOMContentLoaded', function() {
            // Dapatkan URL saat ini
            const currentUrl = window.location.pathname;
            const currentPage = currentUrl.split('/').pop() || 'index.php';
            
            console.log('Current page:', currentPage); // Debug
        
            // Pilih semua link di navbar
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            
            // Loop melalui setiap link
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                console.log(href);
                
                // Skip jika href kosong atau #
                if (!href || href === '#') return;
                
                // Hapus class active dari semua link
                link.classList.remove('active');
                
                // Cek apakah link ini sesuai dengan halaman saat ini
                if (href === currentPage || 
                    (currentPage === '' || currentPage === '/' || currentPage === 'home.php') && 
                    (href === '/' || href === 'index.php' || href === 'home.php')) {
                    link.classList.add('active');
                }
            });
            
            // Handle khusus untuk halaman admin
            if (currentPage === 'admin.php' || currentPage === 'admin') {
                const adminDropdown = document.getElementById('adminDropdown');
                if (adminDropdown) {
                    adminDropdown.classList.add('active');
                }
            }
        });
    </script>
    
    <!-- Update footer statistics - PERBAIKAN -->
    <script>
        // Fungsi untuk update statistik footer
        function updateFooterStats() {
            console.log('Updating footer stats...'); // Debug
            
            // Coba akses dari window object
            const offices = window.allOffices || [];
            
            // Update total offices
            const footerTotal = document.getElementById('footerTotalOffices');
            if (footerTotal) {
                const total = offices.length || 0;
                footerTotal.textContent = total;
                console.log('Total offices:', total);
            }
            
            // Update total provinces
            const footerProvinces = document.getElementById('footerTotalProvinces');
            if (footerProvinces) {
                let provincesCount = 0;
                if (offices.length > 0) {
                    const provinces = [...new Set(offices.map(office => office.province).filter(p => p))];
                    provincesCount = provinces.length;
                }
                footerProvinces.textContent = provincesCount;
                console.log('Total provinces:', provincesCount);
            }
        }

        // Jalankan saat DOM loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, checking for data...');
            updateFooterStats();
        });

        // Jalankan juga setelah semua script selesai (termasuk main.js)
        window.addEventListener('load', function() {
            console.log('Window loaded, updating footer stats...');
            updateFooterStats();
        });

        // Custom event listener jika data dimuat secara async
        document.addEventListener('officesDataLoaded', function(e) {
            console.log('Custom event received:', e.detail);
            updateFooterStats();
        });
    </script>

    <!-- Footer Stats - Gunakan script global -->
    <script>
        // Data fallback dari PHP
        window.phpOffices = <?php echo json_encode($offices ?? []); ?>;

        // Pastikan window.allOffices tersedia
        window.allOffices = window.allOffices || window.phpOffices || [];
    </script>

    <script>
        // Variabel global untuk menyimpan data
        let currentOffices = <?php echo json_encode($offices ?? null); ?>;

        // Fungsi search tabel
        function searchTable() {
            const searchText = document.getElementById('tableSearch').value.toLowerCase();
            const provinceFilter = document.getElementById('provinceFilter').value;
            const typeFilter = document.getElementById('typeFilter').value;
            
            filterTableData(searchText, provinceFilter, typeFilter);
        }

        // Fungsi filter tabel
        function filterTable() {
            const searchText = document.getElementById('tableSearch').value.toLowerCase();
            const provinceFilter = document.getElementById('provinceFilter').value;
            const typeFilter = document.getElementById('typeFilter').value;
            
            filterTableData(searchText, provinceFilter, typeFilter);
        }

        // Fungsi filter data
        function filterTableData(searchText, province, type) {
            const filtered = currentOffices.filter(office => {
                // Filter pencarian
                const matchesSearch = searchText === '' || 
                    office.name.toLowerCase().includes(searchText) ||
                    office.address.toLowerCase().includes(searchText) ||
                    office.city.toLowerCase().includes(searchText) ||
                    office.province.toLowerCase().includes(searchText);
                
                // Filter provinsi
                const matchesProvince = province === '' || office.province === province;
                
                // Filter tipe
                const matchesType = type === '' || office.type === type;
                
                return matchesSearch && matchesProvince && matchesType;
            });
            
            displayFilteredData(filtered);
        }

        // Tampilkan data yang sudah difilter
        function displayFilteredData(filtered) {
            const tbody = document.getElementById('tableBody');
            const displayCount = document.getElementById('displayCount');
            const totalCount = document.getElementById('totalCount');
            
            if (filtered.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="bi bi-search fs-1 d-block text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada data yang cocok</h5>
                            <p class="text-muted">Coba kata kunci atau filter lain</p>
                        </td>
                    </tr>
                `;
            } else {
                let html = '';
                filtered.forEach((office, index) => {
                    html += `
                        <tr>
                            <td class="text-center">${index + 1}</td>
                            <td><small class="text-muted">${office.id}</small></td>
                            <td>
                                <strong>${escapeHtml(office.name)}</strong>
                                ${office.description ? `<br><small class="text-muted">${escapeHtml(office.description.substring(0, 50))}...</small>` : ''}
                            </td>
                            <td><i class="bi bi-geo-alt me-1 text-primary"></i>${escapeHtml(office.address)}</td>
                            <td>${escapeHtml(office.city)}</td>
                            <td>${escapeHtml(office.province)}</td>
                            <td>${office.phone ? `<i class="bi bi-telephone me-1 text-success"></i>${office.phone}` : '-'}</td>
                            <td>
                                <span class="badge ${getTypeClass(office.type)}">
                                    ${office.type}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" onclick='editOffice(${JSON.stringify(office)})'>
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-outline-info" onclick='viewOffice(${JSON.stringify(office)})'>
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="deleteOffice('${office.id}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                tbody.innerHTML = html;
            }
            
            displayCount.textContent = filtered.length;
            totalCount.textContent = currentOffices.length;
        }

        // Escape HTML untuk keamanan
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Get class untuk badge tipe
        function getTypeClass(type) {
            switch(type) {
                case 'Kanwil': return 'bg-danger';
                case 'Pusat': return 'bg-warning text-dark';
                case 'Kantah': return 'bg-success';
                default: return 'bg-secondary';
            }
        }

        // Reset filter tabel
        function resetTableFilters() {
            document.getElementById('tableSearch').value = '';
            document.getElementById('provinceFilter').value = '';
            document.getElementById('typeFilter').value = '';
            displayFilteredData(currentOffices);
        }

        // Refresh tabel
        function refreshTable() {
            location.reload();
        }


        // View office details
        function viewOffice(office) {
            document.getElementById('viewId').textContent = office.id;
            document.getElementById('viewName').textContent = office.name;
            
            const viewType = document.getElementById('viewType');
            viewType.textContent = office.type;
            viewType.className = `badge ${getTypeClass(office.type)}`;
            
            document.getElementById('viewAddress').textContent = office.address;
            document.getElementById('viewCity').textContent = office.city;
            document.getElementById('viewProvince').textContent = office.province;
            document.getElementById('viewPhone').textContent = office.phone || '-';
            document.getElementById('viewEmail').textContent = office.email || '-';
            document.getElementById('viewLat').textContent = office.latitude;
            document.getElementById('viewLng').textContent = office.longitude;
            document.getElementById('viewDescription').textContent = office.description || '-';
            
            new bootstrap.Modal(document.getElementById('viewOfficeModal')).show();
        }

        // View on map (redirect ke halaman utama)
        function viewOnMap() {
            window.location.href = '/';
        }

        // Inisialisasi tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

    <script>
        // ============================================
        // FUNGSI MENAMPILKAN DETAIL KANTOR DI MODAL
        // ============================================
        window.showOfficeDetails = function (office) {
        console.log("showOfficeDetails dipanggil dengan:", office);

        // 🔴 YANG PALING PENTING: SIMPAN OFFICE YANG DIPILIH
        window.selectedOfficeForRoute = office;

        // Isi data modal
        const titleEl = document.getElementById("officeModalTitle");
        const addressEl = document.getElementById("officeAddress");
        const phoneEl = document.getElementById("officePhone");
        const emailEl = document.getElementById("officeEmail");
        const hoursEl = document.getElementById("officeHours");

        if (titleEl) titleEl.textContent = office.name || "-";
        if (addressEl) addressEl.textContent = office.address || "-";
        if (phoneEl) phoneEl.textContent = office.phone || "-";
        if (emailEl) emailEl.textContent = office.email || "-";
        if (hoursEl) hoursEl.textContent = office.jam_operasional || "08:00 - 16:00";

        // Hapus mini map lama jika ada
        if (window.miniMap) {
            window.miniMap.remove();
            window.miniMap = null;
        }

        // Buat mini map baru
        setTimeout(() => {
            const miniMapDiv = document.getElementById("officeMiniMap");
            if (miniMapDiv && office.latitude && office.longitude) {
            window.miniMap = L.map("officeMiniMap").setView(
                [office.latitude, office.longitude],
                15,
            );
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution: "© OpenStreetMap",
            }).addTo(window.miniMap);

            L.marker([office.latitude, office.longitude])
                .addTo(window.miniMap)
                .bindPopup(office.name)
                .openPopup();
            }
        }, 200);

        // Tampilkan modal
        const modalElement = document.getElementById("officeModal");
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }

        // Log untuk verifikasi
        console.log(
            "✅ selectedOfficeForRoute setelah disimpan:",
            window.selectedOfficeForRoute,
        );
        };

        // ============================================
        // FUNGSI HANDLE RUTE
        // ============================================
        window.handleModalRouteClick = function () {
        console.log("handleModalRouteClick dipanggil");
        console.log("Isi selectedOfficeForRoute:", window.selectedOfficeForRoute);

        // Cek apakah ada office yang dipilih
        if (!window.selectedOfficeForRoute) {
            alert("❌ Pilih kantor terlebih dahulu dengan mengklik MARKER di peta!");
            return;
        }

        const office = window.selectedOfficeForRoute;
        console.log("✅ Membuka rute ke:", office.name);

        // Cek geolocation
        if (!navigator.geolocation) {
            alert("Geolocation tidak didukung");
            window.open(
            `https://www.openstreetmap.org/?mlat=${office.latitude}&mlon=${office.longitude}#map=15/${office.latitude}/${office.longitude}`,
            "_blank",
            );
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function (position) {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;

            const url = `https://www.openstreetmap.org/directions?engine=graphhopper_foot&route=${userLat},${userLng};${office.latitude},${office.longitude}`;
            console.log("Membuka URL:", url);
            window.open(url, "_blank");
            },
            function (error) {
            console.error("Geolocation error:", error);
            alert("Gagal mendapatkan lokasi. Membuka peta kantor.");
            window.open(
                `https://www.openstreetmap.org/?mlat=${office.latitude}&mlon=${office.longitude}#map=15/${office.latitude}/${office.longitude}`,
                "_blank",
            );
            },
        );
        };

        // Di fungsi pembuatan marker
        function addOfficeMarker(office) {
        const marker = L.marker([office.latitude, office.longitude]).addTo(map);

        marker.on("click", function () {
            console.log("Marker diklik:", office.name);
            window.showOfficeDetails(office); // 🔴 PANGGIL FUNGSI INI
        });

        return marker;
        }

    </script>

<!-- get current location -->
    <script>

        // Get current location
        // ============================================
        // FUNGSI GET CURRENT LOCATION - VERSI OPTIMAL
        // ============================================
        window.getCurrentLocation = function () {
          console.log("getCurrentLocation dipanggil");

          if (!navigator.geolocation) {
            alert("Geolocation tidak didukung oleh browser Anda.");
            return;
          }

          // Tampilkan loading indicator
          const locateBtn = document.getElementById("locateBtn");
          const originalHtml = locateBtn ? locateBtn.innerHTML : "";
          if (locateBtn) {
            locateBtn.innerHTML =
              '<span class="spinner-border spinner-border-sm"></span>';
            locateBtn.disabled = true;
          }

          // Opsi untuk mendapatkan lokasi AKURAT
          const options = {
            enableHighAccuracy: true, // PAKSA GPS, bukan WiFi/IP [citation:1][citation:8]
            timeout: 10000, // Tunggu maksimal 10 detik [citation:4]
            maximumAge: 0, // Jangan pakai cache, selalu baru [citation:2]
          };

          navigator.geolocation.getCurrentPosition(
            // SUCCESS CALLBACK
            function (position) {
              console.log("Lokasi ditemukan:", position);

              const latitude = position.coords.latitude;
              const longitude = position.coords.longitude;
              const accuracy = position.coords.accuracy; // Akurasi dalam meter

              console.log(
                `Koordinat: ${latitude}, ${longitude}, Akurasi: ${accuracy}m`,
              );

              // Reset button
              if (locateBtn) {
                locateBtn.innerHTML = originalHtml;
                locateBtn.disabled = false;
              }

              // Hapus marker lama
              if (window.currentLocationMarker) {
                map.removeLayer(window.currentLocationMarker);
              }
              if (window.currentAccuracyCircle) {
                map.removeLayer(window.currentAccuracyCircle);
              }

              // BUAT LINGKARAN AKURASI - biar tahu seberapa akurat [citation:1]
              window.currentAccuracyCircle = L.circle([latitude, longitude], {
                radius: accuracy, // Radius = akurasi dalam meter
                color: "#3388ff",
                weight: 1,
                fillColor: "#3388ff",
                fillOpacity: 0.1,
              }).addTo(map);

              // BUAT MARKER LOKASI
              window.currentLocationMarker = L.marker([latitude, longitude], {
                icon: L.divIcon({
                  className: "current-location-marker",
                  html: '<div style="width: 20px; height: 20px; background-color: #4285F4; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 10px rgba(66,133,244,0.5);"></div>',
                  iconSize: [20, 20],
                  iconAnchor: [10, 10],
                }),
              })
                .addTo(map)
                .bindPopup(
                  `Lokasi Anda Saat Ini<br><small>Akurasi: ${accuracy} meter</small>`,
                )
                .openPopup();

              // TAMPILKAN PERINGATAN JIKA AKURASI BURUK [citation:1]
              if (accuracy > 100) {
                alert(
                  `Akurasi lokasi rendah (${accuracy}m). Pastikan GPS aktif dan Anda di luar ruangan.`,
                );
              }

              // Pusatkan peta ke lokasi
              map.setView([latitude, longitude], 15);
            },

            // ERROR CALLBACK
            function (error) {
              console.error("Geolocation error:", error);

              // Reset button
              if (locateBtn) {
                locateBtn.innerHTML = originalHtml;
                locateBtn.disabled = false;
              }

              let errorMessage = "Tidak dapat mendapatkan lokasi Anda: ";
              switch (error.code) {
                case error.PERMISSION_DENIED:
                  errorMessage +=
                    "Izin lokasi ditolak. Izinkan akses lokasi di browser.";
                  break;
                case error.POSITION_UNAVAILABLE:
                  errorMessage += "Informasi lokasi tidak tersedia. Nyalakan GPS.";
                  break;
                case error.TIMEOUT:
                  errorMessage +=
                    "Waktu permintaan lokasi habis. Coba di tempat dengan sinyal lebih baik.";
                  break;
                default:
                  errorMessage += error.message;
              }

              alert(errorMessage);
            },
            options, // PAKAI OPTIONS YANG SUDAH DISET
          );
        };

    </script>
</body>
</html>