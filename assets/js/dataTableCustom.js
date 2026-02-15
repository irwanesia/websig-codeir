$(document).ready(function () {
  // Fungsi untuk inisialisasi DataTables
  function initHomeDataTable() {
    // Hancurkan DataTables yang sudah ada (jika ada)
    if ($.fn.DataTable.isDataTable("#officesTable")) {
      $("#officesTable").DataTable().destroy();
    }

    $("#officesTable").DataTable({});

    // Inisialisasi DataTables dengan konfigurasi khusus untuk home
    $("#officesTable").DataTable({
      // default
      language: {
        url: "https://cdn.datatables.net/plug-ins/2.3.7/i18n/id.json", // Versi sesuai DataTables Anda
      },
      // custom menggunakan bahasa indonesia
      // language: {
      //     "processing": "Sedang diproses...",
      //     "lengthMenu": "Tampilkan _MENU_ data per halaman",
      //     "zeroRecords": "Data tidak ditemukan",
      //     "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
      //     "infoEmpty": "Tidak ada data yang tersedia",
      //     "infoFiltered": "(difilter dari _MAX_ total data)",
      //     "search": "Cari:",
      //     "paginate": {
      //         "first": "Pertama",
      //         "last": "Terakhir",
      //         "next": "Selanjutnya",
      //         "previous": "Sebelumnya"
      //     },
      //     "emptyTable": "Tidak ada data di tabel"
      // },
      pageLength: 10,
      lengthMenu: [5, 10, 25, 50, 100],
      destroy: true, // Izinkan destroy dan re-init
      order: [[0, "asc"]], // Urutkan berdasarkan kolom pertama
      columnDefs: [
        { targets: -1, orderable: false }, // Kolom aksi tidak bisa diurutkan
      ],
    });

    console.log("DataTables initialized for home page");
  }

  // Fungsi untuk memuat data ke tabel
  function loadHomeTableData(offices) {
    const tbody = $("#officeTableBody");
    tbody.empty();

    if (!offices || offices.length === 0) {
      tbody.html(`
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 d-block text-muted mb-2"></i>
                                Tidak ada data kantor
                            </td>
                        </tr>
                    `);

      // Update office count
      $("#officeCount").text("0");
      return;
    }

    // Update count
    $("#officeCount").text(offices.length);

    // Populate table
    offices.forEach((office) => {
      const row = `
                        <tr>
                            <td>
                                <strong>${escapeHtml(office.name)}</strong>
                                <br><small class="text-muted">${office.type || ""}</small>
                            </td>
                            <td>${escapeHtml(office.address || "-")}</td>
                            <td>${escapeHtml(office.city || "-")}</td>
                            <td>${office.phone || "-"}</td>
                            <td>
                                <span class="badge ${getTypeBadgeClass(office.type)}">
                                    ${office.type || "-"}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary view-office" 
                                        onclick='showOfficeDetails(${JSON.stringify(office).replace(/'/g, "\\'")})'>
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info locate-office" 
                                        onclick='locateOffice(${JSON.stringify(office).replace(/'/g, "\\'")})'>
                                    <i class="bi bi-geo-alt"></i>
                                </button>
                            </td>
                        </tr>
                    `;
      tbody.append(row);
    });

    // Inisialisasi atau update DataTables
    initHomeDataTable();
  }

  // Escape HTML untuk keamanan
  function escapeHtml(text) {
    if (!text) return "";
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
  }

  // Get badge class
  function getTypeBadgeClass(type) {
    switch (type) {
      case "Kanwil":
        return "bg-danger";
      case "Pusat":
        return "bg-warning text-dark";
      case "Kantah":
        return "bg-success";
      default:
        return "bg-secondary";
    }
  }

  // Tunggu data dari main.js
  function waitForData() {
    if (window.allOffices && window.allOffices.length > 0) {
      console.log("Data found, loading table...");
      loadHomeTableData(window.allOffices);
    } else {
      console.log("Waiting for data...");
      setTimeout(waitForData, 500);
    }
  }

  // Mulai menunggu data
  waitForData();

  // Listen untuk custom event
  document.addEventListener("officesDataLoaded", function (e) {
    console.log("Data loaded event received:", e.detail);
    loadHomeTableData(window.allOffices);
  });
});
