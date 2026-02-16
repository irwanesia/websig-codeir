// Main JavaScript for BPN WebGIS
// ============================================
// PASTIKAN VARIABEL GLOBAL - LETAKKAN DI PALING ATAS
// ============================================
window.selectedOfficeForRoute = null;
window.miniMap = null;

let map;
let officeMarkers = [];
let currentLocationMarker;
let officeData = [];
let visibleOffices = [];
let mapLayers = {};

// ============================================
// FUNGSI MENAMPILKAN DETAIL KANTOR DI MODAL
// ============================================
window.showOfficeDetails = function (office) {
  console.log("showOfficeDetails dipanggil dengan:", office);
  console.log("Office name:", office?.name);
  console.log("Office address:", office?.address);

  // 🔴 YANG PALING PENTING: SIMPAN OFFICE YANG DIPILIH
  window.selectedOfficeForRoute = office;

  // Verifikasi penyimpanan
  console.log(
    "✅ selectedOfficeForRoute setelah disimpan:",
    window.selectedOfficeForRoute,
  );

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
      console.log("Membuat mini map untuk:", office.latitude, office.longitude);
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
    } else {
      console.warn("Mini map gagal dibuat:", { miniMapDiv, office });
    }
  }, 200);

  // Tampilkan modal
  const modalElement = document.getElementById("officeModal");
  if (modalElement) {
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
  } else {
    console.error("Modal element tidak ditemukan!");
  }
};

// ============================================
// FUNGSI HANDLE RUTE
// ============================================
// window.handleModalRouteClick = function () {
//   console.log("handleModalRouteClick dipanggil");
//   console.log("Isi selectedOfficeForRoute:", window.selectedOfficeForRoute);

//   // Cek apakah ada office yang dipilih
//   if (!window.selectedOfficeForRoute) {
//     alert("❌ Pilih kantor terlebih dahulu dengan mengklik MARKER di peta!");
//     return;
//   }

//   const office = window.selectedOfficeForRoute;
//   console.log("✅ Membuka rute ke:", office.name);

//   // Cek geolocation
//   if (!navigator.geolocation) {
//     alert("Geolocation tidak didukung");
//     window.open(
//       `https://www.openstreetmap.org/?mlat=${office.latitude}&mlon=${office.longitude}#map=15/${office.latitude}/${office.longitude}`,
//       "_blank",
//     );
//     return;
//   }

//   navigator.geolocation.getCurrentPosition(
//     function (position) {
//       const userLat = position.coords.latitude;
//       const userLng = position.coords.longitude;

//       const url = `https://www.openstreetmap.org/directions?engine=graphhopper_foot&route=${userLat},${userLng};${office.latitude},${office.longitude}`;
//       console.log("Membuka URL:", url);
//       window.open(url, "_blank");
//     },
//     function (error) {
//       console.error("Geolocation error:", error);
//       alert("Gagal mendapatkan lokasi. Membuka peta kantor.");
//       window.open(
//         `https://www.openstreetmap.org/?mlat=${office.latitude}&mlon=${office.longitude}#map=15/${office.latitude}/${office.longitude}`,
//         "_blank",
//       );
//     },
//   );
// };

// ============================================
// PASTIKAN MARKER MENGGUNAKAN FUNGSI GLOBAL
// ============================================
// Fungsi addOfficeMarker yang sudah benar (tidak perlu diubah)
function addOfficeMarker(office) {
  // ... kode yang sudah ada ...

  marker.on("click", function () {
    console.log("Marker diklik:", office.name);
    window.showOfficeDetails(office);
  });

  return marker;
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
  console.log("DOM loaded, initializing...");

  // Check if map container exists
  const mapContainer = document.getElementById("map");
  if (!mapContainer) {
    console.error("Map container not found!");
    showError("Container peta tidak ditemukan");
    return;
  }

  // Initialize with timeout to catch errors
  setTimeout(() => {
    try {
      initializeMap();
      loadOfficeData();
      setupEventListeners();
    } catch (error) {
      console.error("Initialization error:", error);
      showError("Gagal menginisialisasi aplikasi: " + error.message);
    }
  }, 100);
});

// Show error message to user
function showError(message) {
  const loadingEl = document.getElementById("mapLoading");
  if (loadingEl) {
    loadingEl.innerHTML = `
            <div class="alert alert-danger m-3">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Error:</strong> ${message}
                <button class="btn btn-sm btn-outline-danger mt-2" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Muat Ulang
                </button>
            </div>
        `;
  }

  const statusEl = document.getElementById("loadingStatus");
  if (statusEl) {
    statusEl.innerHTML = `<span class="text-danger">Error: ${message}</span>`;
  }
}

// Initialize OpenStreetMap
// function initializeMap() {
//   console.log("Initializing map...");

//   try {
//     // Check if Leaflet is loaded
//     if (typeof L === "undefined") {
//       throw new Error("Leaflet library not loaded");
//     }

//     // Create base layers
//     mapLayers.osm = L.tileLayer(
//       "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
//       {
//         attribution: "© OpenStreetMap contributors",
//         maxZoom: 19,
//       },
//     );

//     mapLayers.topo = L.tileLayer(
//       "https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png",
//       {
//         attribution: "© OpenStreetMap contributors, SRTM",
//         maxZoom: 17,
//       },
//     );

//     mapLayers.satellite = L.tileLayer(
//       "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
//       {
//         attribution: "© Esri",
//         maxZoom: 19,
//       },
//     );

//     // Initialize map
//     map = L.map("map", {
//       center: [-2.5489, 118.0149], // Center of Indonesia
//       zoom: 5,
//       layers: [mapLayers.osm],
//     });

//     // Add layer control
//     L.control
//       .layers({
//         OpenStreetMap: mapLayers.osm,
//         Topografi: mapLayers.topo,
//         Satellite: mapLayers.satellite,
//       })
//       .addTo(map);

//     // Add scale control
//     L.control.scale().addTo(map);

//     // Map ready event
//     map.whenReady(() => {
//       console.log("Map is ready");

//       // Hide loading overlay
//       const mapLoading = document.getElementById("mapLoading");
//       if (mapLoading) {
//         mapLoading.style.display = "none";
//       }

//       const loadingStatus = document.getElementById("loadingStatus");
//       if (loadingStatus) {
//         loadingStatus.innerHTML =
//           '<span class="text-success"><i class="bi bi-check-circle"></i> Peta siap digunakan</span>';
//       }
//     });
//   } catch (error) {
//     console.error("Map initialization error:", error);
//     showError("Gagal memuat peta: " + error.message);
//   }
// }

// Inisialisasi map layers
function initializeMap() {
  console.log("Initializing map...");

  try {
    // Create base layers
    mapLayers.osm = L.tileLayer(
      "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
      {
        attribution: "© OpenStreetMap contributors",
        maxZoom: 19,
      },
    );

    mapLayers.topo = L.tileLayer(
      "https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png",
      {
        attribution: "© OpenStreetMap contributors, SRTM",
        maxZoom: 17,
      },
    );

    mapLayers.satellite = L.tileLayer(
      "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
      {
        attribution: "© Esri",
        maxZoom: 19,
      },
    );

    // Initialize map dengan layer default (OSM)
    map = L.map("map", {
      center: [-2.5489, 118.0149],
      zoom: 5,
      layers: [mapLayers.osm], // Layer default
    });

    // Tambahkan scale control
    L.control.scale().addTo(map);

    // OPSIONAL: Jika ingin tetap menggunakan layer control bawaan Leaflet
    // TAPI ini bisa dikomentari jika ingin menggunakan radio button di sidebar
    L.control
      .layers({
        OpenStreetMap: mapLayers.osm,
        Topografi: mapLayers.topo,
        Satellite: mapLayers.satellite,
      })
      .addTo(map);

    // Setup event listeners untuk radio button layer
    setupLayerControls();

    // Map ready event
    map.whenReady(() => {
      console.log("Map is ready");

      // Hide loading overlay
      const mapLoading = document.getElementById("mapLoading");
      if (mapLoading) {
        mapLoading.style.display = "none";
      }

      const loadingStatus = document.getElementById("loadingStatus");
      if (loadingStatus) {
        loadingStatus.innerHTML =
          '<span class="text-success"><i class="bi bi-check-circle"></i> Peta siap digunakan</span>';
      }
    });

    // Di dalam fungsi initializeMap(), setelah map siap
    map.whenReady(() => {
      console.log("Map is ready");

      // Setup map controls
      setupMapControls();

      // ... kode lainnya ...
    });
  } catch (error) {
    console.error("Map initialization error:", error);
    showError("Gagal memuat peta: " + error.message);
  }
}

// Setup layer controls dari sidebar
function setupLayerControls() {
  const layerOSM = document.getElementById("layerOSM");
  const layerTopo = document.getElementById("layerTopo");
  const layerSatellite = document.getElementById("layerSatellite");

  if (layerOSM) {
    layerOSM.addEventListener("change", function (e) {
      if (e.target.checked) {
        switchLayer("osm");
      }
    });
  }

  if (layerTopo) {
    layerTopo.addEventListener("change", function (e) {
      if (e.target.checked) {
        switchLayer("topo");
      }
    });
  }

  if (layerSatellite) {
    layerSatellite.addEventListener("change", function (e) {
      if (e.target.checked) {
        switchLayer("satellite");
      }
    });
  }
}

// Fungsi untuk mengganti layer
function switchLayer(layerName) {
  console.log("Switching to layer:", layerName);

  // Hapus semua layer dari map
  map.eachLayer(function (layer) {
    // Hapus hanya tile layers, bukan markers
    if (layer instanceof L.TileLayer) {
      map.removeLayer(layer);
    }
  });

  // Tambahkan layer yang dipilih
  switch (layerName) {
    case "osm":
      mapLayers.osm.addTo(map);
      break;
    case "topo":
      mapLayers.topo.addTo(map);
      break;
    case "satellite":
      mapLayers.satellite.addTo(map);
      break;
    default:
      mapLayers.osm.addTo(map);
  }

  // Simpan layer aktif (opsional)
  localStorage.setItem("activeMapLayer", layerName);
}

// Muat layer tersimpan (opsional)
function loadSavedLayer() {
  const savedLayer = localStorage.getItem("activeMapLayer");
  if (savedLayer) {
    // Set radio button
    const radio = document.getElementById(
      `layer${savedLayer.charAt(0).toUpperCase() + savedLayer.slice(1)}`,
    );
    if (radio) {
      radio.checked = true;
      switchLayer(savedLayer);
    }
  }
}

// Sinkronkan radio button dengan layer control bawaan
function syncLayerControls() {
  // Saat layer berubah via control bawaan
  map.on("baselayerchange", function (e) {
    console.log("Base layer changed to:", e.name);

    // Update radio button sesuai layer yang dipilih
    if (e.name === "OpenStreetMap") {
      document.getElementById("layerOSM").checked = true;
    } else if (e.name === "Topografi") {
      document.getElementById("layerTopo").checked = true;
    } else if (e.name === "Satellite") {
      document.getElementById("layerSatellite").checked = true;
    }
  });

  // Panggil fungsi ini setelah map diinisialisasi
  syncLayerControls();
}

// Setup map controls (tombol di atas peta)
function setupMapControls() {
  console.log("Setting up map controls...");

  // 1. Tombol Lokasi Saya (di atas peta)
  const locateBtn = document.getElementById("locateBtn");
  if (locateBtn) {
    locateBtn.addEventListener("click", function (e) {
      e.preventDefault();
      console.log("Locate button clicked");
      getCurrentLocation();
    });
  }

  // 2. Tombol Rute
  const routeBtn = document.getElementById("routeBtn");
  if (routeBtn) {
    routeBtn.addEventListener("click", function (e) {
      e.preventDefault();
      console.log("Route button clicked");
      showRouteModal();
    });
  }

  // 3. Tombol Legenda
  const legendToggle = document.getElementById("legendToggle");
  const legend = document.getElementById("mapLegend");

  if (legendToggle && legend) {
    legendToggle.addEventListener("click", function (e) {
      e.preventDefault();
      console.log("Legend toggle clicked");

      // Toggle legend visibility
      if (legend.style.display === "none") {
        legend.style.display = "block";
        legendToggle.classList.add("active");
      } else {
        legend.style.display = "none";
        legendToggle.classList.remove("active");
      }
    });
  }
}

// tombol route modal
// Variabel global untuk menyimpan office yang dipilih
let selectedOfficeForRoute = null;

// Fungsi untuk menampilkan detail office (update bagian ini)
function showOfficeDetails(office) {
  // Simpan office yang dipilih
  selectedOfficeForRoute = office;

  // Isi data di modal
  document.getElementById("officeModalTitle").textContent = office.name;
  document.getElementById("officeAddress").textContent = office.address || "-";
  document.getElementById("officePhone").textContent = office.phone || "-";
  document.getElementById("officeEmail").textContent = office.email || "-";
  document.getElementById("officeHours").textContent =
    office.jam_operasional || "08:00 - 16:00";

  // Initialize mini map
  setTimeout(() => {
    if (window.miniMap) {
      window.miniMap.remove();
    }

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
  }, 200);

  // Show modal
  const modal = new bootstrap.Modal(document.getElementById("officeModal"));
  modal.show();
}

// Fungsi untuk tombol route di modal
function handleModalRoute() {
  if (!selectedOfficeForRoute) {
    alert("Pilih kantor terlebih dahulu!");
    return;
  }

  // Dapatkan lokasi user
  if (!navigator.geolocation) {
    alert("Geolocation tidak didukung browser Anda");
    return;
  }

  navigator.geolocation.getCurrentPosition(
    (position) => {
      const userLat = position.coords.latitude;
      const userLng = position.coords.longitude;

      // Buka Google Maps atau OpenStreetMap untuk navigasi
      const office = selectedOfficeForRoute;
      const url = `https://www.openstreetmap.org/directions?engine=graphhopper_foot&route=${userLat},${userLng};${office.latitude},${office.longitude}`;

      // Buka di tab baru
      window.open(url, "_blank");
    },
    (error) => {
      alert("Tidak dapat mendapatkan lokasi Anda: " + error.message);
      // Fallback: buka rute tanpa lokasi user
      const office = selectedOfficeForRoute;
      const url = `https://www.openstreetmap.org/?mlat=${office.latitude}&mlon=${office.longitude}#map=15/${office.latitude}/${office.longitude}`;
      window.open(url, "_blank");
    },
  );
}

// Fungsi untuk tombol route di map controls
function handleMapRoute() {
  if (!selectedOfficeForRoute) {
    alert("Pilih kantor terlebih dahulu dengan mengklik marker di peta!");
    return;
  }

  // Sama seperti di atas
  if (!navigator.geolocation) {
    alert("Geolocation tidak didukung browser Anda");
    return;
  }

  navigator.geolocation.getCurrentPosition(
    (position) => {
      const userLat = position.coords.latitude;
      const userLng = position.coords.longitude;
      const office = selectedOfficeForRoute;
      const url = `https://www.openstreetmap.org/directions?engine=graphhopper_foot&route=${userLat},${userLng};${office.latitude},${office.longitude}`;
      window.open(url, "_blank");
    },
    (error) => {
      alert("Tidak dapat mendapatkan lokasi Anda: " + error.message);
      const office = selectedOfficeForRoute;
      const url = `https://www.openstreetmap.org/?mlat=${office.latitude}&mlon=${office.longitude}#map=15/${office.latitude}/${office.longitude}`;
      window.open(url, "_blank");
    },
  );
}

// Setup event listeners
document.addEventListener("DOMContentLoaded", function () {
  // Tombol route di map controls
  const mapRouteBtn = document.getElementById("routeBtn");
  if (mapRouteBtn) {
    mapRouteBtn.addEventListener("click", handleMapRoute);
  }

  // Tombol route di modal
  const modalRouteBtn = document.getElementById("modalRouteBtn");
  if (modalRouteBtn) {
    modalRouteBtn.addEventListener("click", handleModalRoute);
  }

  // Clean up mini map saat modal ditutup
  const officeModal = document.getElementById("officeModal");
  if (officeModal) {
    officeModal.addEventListener("hidden.bs.modal", function () {
      if (window.miniMap) {
        window.miniMap.remove();
        window.miniMap = null;
      }
    });
  }
});

// Fungsi untuk menampilkan modal rute
function showRouteModal() {
  // Cek apakah ada marker yang dipilih
  if (!window.selectedOffice) {
    alert("Pilih kantor terlebih dahulu dengan mengklik marker di peta!");
    return;
  }

  // Buat modal rute sederhana
  const routeModal = new bootstrap.Modal(
    document.getElementById("routeModal") || createRouteModal(),
  );
  routeModal.show();
}

// Buat modal rute jika belum ada
function createRouteModal() {
  const modalHtml = `
        <div class="modal fade" id="routeModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-signpost me-2"></i>Rute ke Kantor BPN
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="routeInfo">
                            <p class="text-center text-muted">
                                <i class="bi bi-geo fs-1 d-block mb-2"></i>
                                Pilih kantor di peta untuk melihat rute
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    `;

  document.body.insertAdjacentHTML("beforeend", modalHtml);
  return document.getElementById("routeModal");
}

// Toggle legend
function toggleLegend() {
  const legend = document.getElementById("mapLegend");
  const legendToggle = document.getElementById("legendToggle");

  if (legend) {
    if (legend.style.display === "none") {
      legend.style.display = "block";
      if (legendToggle) legendToggle.classList.add("active");
    } else {
      legend.style.display = "none";
      if (legendToggle) legendToggle.classList.remove("active");
    }
  }
}

// Simpan office yang dipilih untuk rute
window.selectedOffice = null;

// Load office data from JSON
async function loadOfficeData() {
  console.log("Loading office data...");

  try {
    // Try multiple possible paths
    const possiblePaths = [
      "/data/offices.json",
      "./data/offices.json",
      "data/offices.json",
      "../data/offices.json",
    ];

    let response = null;
    let usedPath = "";

    for (const path of possiblePaths) {
      try {
        console.log(`Trying path: ${path}`);
        const testResponse = await fetch(path, { method: "HEAD" });
        if (testResponse.ok) {
          response = await fetch(path);
          usedPath = path;
          console.log(`Found data at: ${path}`);
          break;
        }
      } catch (e) {
        console.log(`Path ${path} failed:`, e.message);
      }
    }

    if (!response) {
      throw new Error("Data file tidak ditemukan di semua path yang dicoba");
    }

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    window.allOffices = data.offices || [];
    console.log("Data loaded:", data);

    // Validate data structure
    if (!data.offices || !Array.isArray(data.offices)) {
      console.warn("Data structure unexpected:", data);
      officeData = data.offices || [];
    } else {
      officeData = data.offices;
    }

    // Update statistics
    updateStatistics();

    // Populate province filter
    populateProvinceFilter();

    // Display all offices
    displayOffices(officeData);

    // Hide loading indicator
    const officeLoading = document.getElementById("officeLoading");
    if (officeLoading) {
      officeLoading.style.display = "none";
    }

    // Dispatch custom event untuk memberi tahu footer
    const event = new CustomEvent("officesDataLoaded", {
      detail: {
        count: window.allOffices.length,
        provinces: [...new Set(window.allOffices.map((o) => o.province))]
          .length,
      },
    });
    document.dispatchEvent(event);

    console.log("Data loaded, event dispatched");

    console.log(`Successfully loaded ${officeData.length} offices`);
  } catch (error) {
    console.error("Error loading office data:", error);

    // Jika error, tetap dispatch event dengan data kosong
    window.allOffices = [];
    const event = new CustomEvent("officesDataLoaded", {
      detail: { count: 0 },
    });
    document.dispatchEvent(event);
    // Create sample data as fallback
    console.log("Creating sample data as fallback...");
    createSampleData();
  }
}

// Create sample data as fallback
function createSampleData() {
  // Gunakan data sample dari format asli Anda
  officeData = [
    {
      id: "BPN001",
      name: "Kantor Wilayah BPN Provinsi Aceh",
      address: "Lamgugob, Jl. Teuku Nyak Arief, Lamgugob, Aceh 24415",
      city: "Kota Banda Aceh",
      province: "Aceh",
      latitude: 5.576359,
      longitude: 95.355722,
      phone: "(0651)7551708",
      email: "-@bpn.go.id",
      type: "Kanwil",
      description: "Kantor Wilayah BPN Provinsi Aceh Republik Indonesia",
    },
    {
      id: "BPN002",
      name: "Kantor Wilayah BPN Jawa Barat",
      address: "Jl. LLRE Martadinata No. 153",
      city: "Bandung",
      province: "Jawa Barat",
      latitude: -6.912024,
      longitude: 107.619125,
      phone: "(022) 4203328",
      email: "kanwil.jabar@bpn.go.id",
      type: "Kanwil",
      description: "Kantor Wilayah BPN Provinsi Jawa Barat",
    },
    // {
    //   id: "BPN003",
    //   name: "Kantor Pertanahan Kota Bandung",
    //   address: "Jl. Aceh No. 53",
    //   city: "Bandung",
    //   province: "Jawa Barat",
    //   latitude: -6.9125,
    //   longitude: 107.6206,
    //   phone: "(022) 4205678",
    //   email: "kantah.bandung@bpn.go.id",
    //   type: "Kantah",
    //   description: "Kantor Pertanahan Kota Bandung",
    // },
    // {
    //   id: "BPN004",
    //   name: "Kantor Pertanahan Kota Jakarta Pusat",
    //   address: "Jl. Kramat Raya No. 132",
    //   city: "Jakarta Pusat",
    //   province: "DKI Jakarta",
    //   latitude: -6.186486,
    //   longitude: 106.834091,
    //   phone: "(021) 31907445",
    //   email: "kantah.jakpus@bpn.go.id",
    //   type: "Kantah",
    //   description: "Kantor Pertanahan Kota Jakarta Pusat",
    // },
    // {
    //   id: "BPN006",
    //   name: "Kantor Pusat BPN",
    //   address: "Jl. Sisingamangaraja No. 2, Kebayoran Baru",
    //   city: "Jakarta Selatan",
    //   province: "DKI Jakarta",
    //   latitude: -6.226595,
    //   longitude: 106.802216,
    //   phone: "(021) 7393939",
    //   email: "pusat@bpn.go.id",
    //   type: "Pusat",
    //   description: "Kantor Pusat Badan Pertanahan Nasional Republik Indonesia",
    // },
  ];

  // Update statistics
  updateStatistics();

  // Populate province filter
  populateProvinceFilter();

  // Display all offices
  displayOffices(officeData);

  // Hide loading indicator
  const officeLoading = document.getElementById("officeLoading");
  if (officeLoading) {
    officeLoading.innerHTML = `
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Menggunakan data sample (file JSON tidak ditemukan)
            </div>
        `;
  }
}

// async function loadOfficeData() {
//   try {
//     const response = await fetch("data/offices.json");

//     if (!response.ok) {
//       throw new Error("File JSON tidak ditemukan");
//     }

//     const data = await response.json();

//     // ambil array offices
//     officeData = data.offices;

//     updateStatistics();
//     populateProvinceFilter();
//     displayOffices(officeData);
//   } catch (error) {
//     console.error(error);
//     createSampleData(); // fallback
//   }
// }

// Update statistics
function updateStatistics() {
  const totalEl = document.getElementById("totalOffices");
  const visibleEl = document.getElementById("visibleOffices");
  const countEl = document.getElementById("officeCount");

  if (totalEl) totalEl.textContent = officeData.length;
  if (visibleEl) visibleEl.textContent = officeData.length;
  if (countEl) countEl.textContent = officeData.length;
}

// Populate province filter dropdown
function populateProvinceFilter() {
  const filter = document.getElementById("provinceFilter");
  if (!filter) return;

  // Clear existing options except first
  while (filter.options.length > 1) {
    filter.remove(1);
  }

  // Get unique provinces (gunakan field 'province' sesuai format asli)
  const provinces = [
    ...new Set(
      officeData
        .map((office) => office.province || "")
        .filter((p) => p && p.trim() !== ""),
    ),
  ].sort();

  provinces.forEach((province) => {
    const option = document.createElement("option");
    option.value = province;
    option.textContent = province;
    filter.appendChild(option);
  });

  console.log(`Populated ${provinces.length} provinces`);
}

// Display offices on map and table
function displayOffices(offices) {
  console.log(`Displaying ${offices.length} offices`);

  // Clear existing markers
  clearMarkers();

  // Update visible offices count
  visibleOffices = offices;
  const visibleEl = document.getElementById("visibleOffices");
  if (visibleEl) visibleEl.textContent = offices.length;

  // Add markers to map
  let markersAdded = 0;
  offices.forEach((office) => {
    if (office.latitude && office.longitude) {
      try {
        const marker = addOfficeMarker(office);
        officeMarkers.push(marker);
        markersAdded++;
      } catch (e) {
        console.warn("Failed to add marker for office:", office.name, e);
      }
    }
  });

  console.log(`Added ${markersAdded} markers to map`);

  // Update table
  updateOfficeTable(offices);

  // Fit map bounds if there are markers
  if (officeMarkers.length > 0 && map) {
    try {
      const group = L.featureGroup(officeMarkers);
      map.fitBounds(group.getBounds().pad(0.1));
    } catch (e) {
      console.warn("Could not fit bounds:", e);
    }
  }
}

// Clear all markers
function clearMarkers() {
  if (officeMarkers.length > 0 && map) {
    officeMarkers.forEach((marker) => {
      if (marker && map) map.removeLayer(marker);
    });
  }
  officeMarkers = [];
}

// Add office marker to map
function addOfficeMarker(office) {
  // Determine marker color based on office type
  let markerColor = "#e74c3c"; // Default red

  // Sesuaikan dengan format type asli (Kanwil, Kantah, Pusat)
  if (office.type === "Kantah") markerColor = "#3498db";
  if (office.type === "Kanwil") markerColor = "#e74c3c";
  if (office.type === "Pusat") markerColor = "#f39c12";

  // Create custom icon
  const icon = L.divIcon({
    className: "office-marker",
    html: `<div style="background-color: ${markerColor}; width: 100%; height: 100%; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>`,
    iconSize: [24, 24],
    iconAnchor: [12, 12],
    popupAnchor: [0, -12],
  });

  // Create marker
  const marker = L.marker([office.latitude, office.longitude], { icon })
    .addTo(map)
    .bindPopup(createOfficePopup(office));

  // Add click event
  marker.on("click", function () {
    console.log("Marker diklik:", office.name);
    window.showOfficeDetails(office); // 🔴 PANGGIL FUNGSI INI
  });

  return marker;
}

// Create office popup content
function createOfficePopup(office) {
  return `
        <div class="office-popup" style="min-width: 200px;">
            <h6 class="mb-1 fw-bold">${office.name || "Unknown"}</h6>
            <p class="mb-1 small">${office.address || "Alamat tidak tersedia"}</p>
            <p class="mb-1 small"><i class="bi bi-telephone"></i> ${office.phone || "-"}</p>
            <p class="mb-0">
                <span class="badge ${getOfficeTypeBadge(office.type)}">${office.type || "Unknown"}</span>
            </p>
        </div>
    `;
}

// Get badge class for office type
function getOfficeTypeBadge(type) {
  switch (type) {
    case "Kanwil":
      return "bg-danger";
    case "Kantah":
      return "bg-primary";
    case "Pusat":
      return "bg-warning text-dark";
    default:
      return "bg-secondary";
  }
}

// Update office table
function updateOfficeTable(offices) {
  const tbody = document.getElementById("officeTableBody");
  if (!tbody) return;

  if (offices.length === 0) {
    tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4">
                    <i class="bi bi-inbox fs-1 d-block text-muted mb-2"></i>
                    Tidak ada data kantor
                </td>
            </tr>
        `;
    return;
  }

  tbody.innerHTML = "";

  offices.forEach((office) => {
    const row = document.createElement("tr");
    row.innerHTML = `
            <td>
                <strong>${office.name || "-"}</strong>
                <br><small class="text-muted">${office.type || ""}</small>
            </td>
            <td>${office.address || "-"}</td>
            <td>${office.city || "-"}</td>
            <td>${office.phone || "-"}</td>
            <td><span class="badge ${getOfficeTypeBadge(office.type)}">${office.type || "-"}</span></td>
            <td>
                <button class="btn btn-sm btn-outline-primary view-office" data-id="${office.id || ""}">
                    <i class="bi bi-eye"></i>
                </button>
                <button class="btn btn-sm btn-outline-info locate-office" data-id="${office.id || ""}">
                    <i class="bi bi-geo-alt"></i>
                </button>
            </td>
        `;

    // Add click events
    const viewBtn = row.querySelector(".view-office");
    const locateBtn = row.querySelector(".locate-office");

    if (viewBtn) {
      viewBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        showOfficeDetails(office);
      });
    }

    if (locateBtn) {
      locateBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        locateOffice(office);
      });
    }

    row.addEventListener("click", () => locateOffice(office));

    tbody.appendChild(row);
  });
}

// Show office details in modal
// function showOfficeDetails(office) {
//   document.getElementById("officeModalTitle").textContent = office.name;
//   document.getElementById("officeAddress").textContent = office.address || "-";
//   document.getElementById("officePhone").textContent = office.phone || "-";
//   document.getElementById("officeEmail").textContent = office.email || "-";
//   document.getElementById("officeHours").textContent =
//     office.jam_operasional || "08:00 - 16:00";

//   // Initialize mini map
//   const miniMap = L.map("officeMiniMap").setView(
//     [office.latitude, office.longitude],
//     15,
//   );
//   L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(
//     miniMap,
//   );
//   L.marker([office.latitude, office.longitude]).addTo(miniMap);

//   // Show modal
//   const modal = new bootstrap.Modal(document.getElementById("officeModal"));
//   modal.show();

//   // Clean up mini map when modal is hidden
//   document.getElementById("officeModal").addEventListener(
//     "hidden.bs.modal",
//     function () {
//       miniMap.remove();
//     },
//     { once: true },
//   );
// }

// Locate office on main map
function locateOffice(office) {
  if (office.latitude && office.longitude) {
    map.setView([office.latitude, office.longitude], 15);

    // Open popup if marker exists
    officeMarkers.forEach((marker) => {
      const latLng = marker.getLatLng();
      if (latLng.lat === office.latitude && latLng.lng === office.longitude) {
        marker.openPopup();
      }
    });
  }
}

// Setup event listeners
function setupEventListeners() {
  console.log("Setting up event listeners...");

  // Search functionality
  const searchBtn = document.getElementById("searchBtn");
  const searchInput = document.getElementById("searchInput");

  if (searchBtn) {
    searchBtn.addEventListener("click", performSearch);
  }

  if (searchInput) {
    searchInput.addEventListener("keyup", function (e) {
      if (e.key === "Enter") performSearch();
    });
  }

  // Filter functionality
  const provinceFilter = document.getElementById("provinceFilter");
  const typeFilter = document.getElementById("officeTypeFilter");

  if (provinceFilter) {
    provinceFilter.addEventListener("change", applyFilters);
  }

  if (typeFilter) {
    typeFilter.addEventListener("change", applyFilters);
  }

  // Reset button
  const resetBtn = document.getElementById("resetBtn");
  if (resetBtn) {
    resetBtn.addEventListener("click", resetFilters);
  }

  // Current location button
  const locationBtn = document.getElementById("currentLocationBtn");
  if (locationBtn) {
    locationBtn.addEventListener("click", getCurrentLocation);
  }

  // Export button
  const exportBtn = document.getElementById("exportBtn");
  if (exportBtn) {
    exportBtn.addEventListener("click", exportToCSV);
  }

  // Map controls
  const zoomIn = document.getElementById("zoomInBtn");
  const zoomOut = document.getElementById("zoomOutBtn");
  const fullExtent = document.getElementById("fullExtentBtn");

  if (zoomIn) zoomIn.addEventListener("click", () => map?.zoomIn());
  if (zoomOut) zoomOut.addEventListener("click", () => map?.zoomOut());
  if (fullExtent) fullExtent.addEventListener("click", zoomToAllOffices);

  console.log("Event listeners setup complete");
}

// Perform search
function performSearch() {
  const query = document.getElementById("searchInput").value.toLowerCase();

  if (!query) {
    displayOffices(officeData);
    return;
  }

  const results = officeData.filter((office) => {
    return (
      (office.name && office.name.toLowerCase().includes(query)) ||
      (office.address && office.address.toLowerCase().includes(query)) ||
      (office.city && office.city.toLowerCase().includes(query)) ||
      (office.province && office.province.toLowerCase().includes(query)) ||
      (office.description && office.description.toLowerCase().includes(query))
    );
  });

  displayOffices(results);
}

// Apply filters
function applyFilters() {
  const province = document.getElementById("provinceFilter").value;
  const type = document.getElementById("officeTypeFilter").value;

  let filtered = officeData;

  if (province) {
    filtered = filtered.filter((office) => office.province === province);
  }

  if (type) {
    filtered = filtered.filter((office) => office.type === type);
  }

  displayOffices(filtered);
}

// Reset filters
function resetFilters() {
  document.getElementById("searchInput").value = "";
  document.getElementById("provinceFilter").value = "";
  document.getElementById("officeTypeFilter").value = "";
  displayOffices(officeData);
}

// Zoom to show all offices
function zoomToAllOffices() {
  if (officeMarkers.length > 0) {
    const group = L.featureGroup(officeMarkers);
    map.fitBounds(group.getBounds().pad(0.1));
  } else {
    map.setView([-2.5489, 118.0149], 5); // Center of Indonesia
  }
}

// Di main.js - PASTIKAN INI
var allOffices = []; // Gunakan var, bukan let/const untuk global scope
// ATAU
window.allOffices = []; // Eksplisit sebagai global

// Saat data dimuat
window.allOffices = data.offices; // atau allOffices = data.offices;

// Export to CSV
function exportToCSV() {
  if (visibleOffices.length === 0) {
    alert("Tidak ada data untuk diexport.");
    return;
  }

  // Create CSV header sesuai format asli
  let csv =
    "ID,Nama Kantor,Alamat,Kota,Provinsi,Telepon,Email,Tipe,Deskripsi\n";

  // Add data rows
  visibleOffices.forEach((office) => {
    csv += `"${office.id || ""}","${office.name || ""}","${office.address || ""}","${office.city || ""}",`;
    csv += `"${office.province || ""}","${office.phone || ""}","${office.email || ""}",`;
    csv += `"${office.type || ""}","${office.description || ""}"\n`;
  });

  // Create download link
  const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
  const link = document.createElement("a");
  const url = URL.createObjectURL(blob);

  link.setAttribute("href", url);
  link.setAttribute(
    "download",
    `kantor-bpn-${new Date().toISOString().split("T")[0]}.csv`,
  );
  link.style.visibility = "hidden";

  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}
