// Print tabel
// function printTable() {
//     const printWindow = window.open('', '_blank');
//     const styles = document.querySelectorAll('style, link[rel="stylesheet"]');
//     let stylesHtml = '';

//     styles.forEach(style => {
//         if (style.tagName === 'LINK') {
//             stylesHtml += `<link rel="stylesheet" href="${style.href}">`;
//         } else {
//             stylesHtml += style.outerHTML;
//         }
//     });

//     printWindow.document.write(`
//         <!DOCTYPE html>
//         <html>
//         <head>
//             <title>Data Kantor BPN</title>
//             ${stylesHtml}
//             <style>
//                 body { padding: 20px; }
//                 @media print {
//                     .btn, .btn-group { display: none; }
//                 }
//             </style>
//         </head>
//         <body>
//             <h2 class="mb-4">Data Kantor BPN Seluruh Indonesia</h2>
//             <p class="text-muted">Tanggal: ${new Date().toLocaleDateString('id-ID')}</p>
//             ${document.getElementById('officesTable').outerHTML}
//         </body>
//         </html>
//     `);

//     printWindow.document.close();
//     printWindow.focus();
//     printWindow.print();
// }

// Print tabel - VERSI PERBAIKAN
function printTable() {
  try {
    // Dapatkan tabel
    const originalTable = document.getElementById("officesTable");
    if (!originalTable) {
      alert("Tabel tidak ditemukan!");
      return;
    }

    // Clone tabel untuk dimodifikasi
    const tableClone = originalTable.cloneNode(true);

    // Hapus kolom terakhir (kolom Aksi) dari clone
    // Metode 1: Hapus dari thead dan tbody
    const thead = tableClone.querySelector("thead");
    const tbody = tableClone.querySelector("tbody");
    const tfoot = tableClone.querySelector("tfoot");

    if (thead) {
      const headerRows = thead.querySelectorAll("tr");
      headerRows.forEach((row) => {
        if (row.cells.length > 0) {
          // Hapus cell terakhir (kolom Aksi)
          row.deleteCell(-1);
        }
      });
    }

    if (tbody) {
      const bodyRows = tbody.querySelectorAll("tr");
      bodyRows.forEach((row) => {
        if (row.cells.length > 0) {
          // Hapus cell terakhir (kolom Aksi)
          row.deleteCell(-1);
        }
      });
    }

    if (tfoot) {
      const footerRows = tfoot.querySelectorAll("tr");
      footerRows.forEach((row) => {
        if (row.cells.length > 0) {
          row.deleteCell(-1);
        }
      });
    }

    // Metode 2: Alternatif dengan CSS (jika metode 1 tidak berhasil)
    // Tambahkan style untuk hidden kolom terakhir saat print

    // Dapatkan semua styles dari dokumen asli
    const styles = document.querySelectorAll('style, link[rel="stylesheet"]');
    let stylesHtml = "";

    styles.forEach((style) => {
      if (style.tagName === "LINK") {
        stylesHtml += `<link rel="stylesheet" href="${style.href}">`;
      } else {
        stylesHtml += style.outerHTML;
      }
    });

    // Format tanggal dengan aman
    const today = new Date();
    const formattedDate = today.toLocaleDateString("id-ID", {
      day: "2-digit",
      month: "long",
      year: "numeric",
    });

    const formattedTime = today.toLocaleTimeString("id-ID", {
      hour: "2-digit",
      minute: "2-digit",
    });

    // Buat window print
    const printWindow = window.open("", "_blank");
    if (!printWindow) {
      alert(
        "Popup blocker mungkin mencegah pembukaan jendela print. Izinkan popup untuk website ini.",
      );
      return;
    }

    printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Data Kantor BPN Seluruh Indonesia</title>
                        ${stylesHtml}
                        <style>
                            /* Style untuk print */
                            body { 
                                padding: 20px; 
                                font-family: Arial, sans-serif;
                                background: white;
                                color: black;
                            }
                            
                            /* Header print */
                            .print-header {
                                text-align: center;
                                margin-bottom: 30px;
                                padding-bottom: 20px;
                                border-bottom: 2px solid #333;
                            }
                            
                            .print-header h1 {
                                font-size: 24px;
                                margin-bottom: 5px;
                                color: #333;
                            }
                            
                            .print-header h2 {
                                font-size: 18px;
                                font-weight: normal;
                                margin-bottom: 10px;
                                color: #666;
                            }
                            
                            .print-header .date-info {
                                font-size: 14px;
                                color: #666;
                                margin-bottom: 5px;
                            }
                            
                            /* Style tabel */
                            table {
                                width: 100%;
                                border-collapse: collapse;
                                margin: 20px 0;
                                font-size: 12px;
                            }
                            
                            th {
                                font-weight: bold;
                                text-align: left;
                                padding: 8px;
                                border: 1px solid #ddd;
                            }
                            
                            td {
                                padding: 8px;
                                border: 1px solid #ddd;
                            }
                            
                            /* Hilangkan elemen yang tidak perlu saat print */
                            .btn, 
                            .btn-group, 
                            .no-print,
                            button,
                            .dropdown,
                            .action-buttons {
                                display: none !important;
                            }
                            
                            /* Style untuk DataTables elements */
                            .dataTables_length,
                            .dataTables_filter,
                            .dataTables_info,
                            .dataTables_paginate,
                            .dataTables_processing {
                                display: none !important;
                            }
                            
                            /* Aturan print */
                            @media print {
                                @page {
                                    size: A4 landscape;
                                    margin: 1cm;
                                }
                                
                                body {
                                    padding: 0;
                                    margin: 0;
                                }
                                
                                th {
                                    -webkit-print-color-adjust: exact;
                                    print-color-adjust: exact;
                                }
                                
                                tr {
                                    page-break-inside: avoid;
                                }
                            }
                            
                            /* Style untuk badge/tag */
                            .badge {
                                padding: 3px 6px;
                                border-radius: 3px;
                                font-size: 11px;
                                font-weight: bold;
                            }
                            
                            .bg-success { background-color: #d4edda !important; color: #155724 !important; }
                            .bg-danger { background-color: #f8d7da !important; color: #721c24 !important; }
                            .bg-primary { background-color: #cce5ff !important; color: #004085 !important; }
                            .bg-warning { background-color: #fff3cd !important; color: #856404 !important; }
                            .bg-secondary { background-color: #e2e3e5 !important; color: #383d41 !important; }
                            
                            /* Footer print */
                            .print-footer {
                                margin-top: 30px;
                                padding-top: 10px;
                                border-top: 1px solid #ccc;
                                text-align: center;
                                font-size: 11px;
                                color: #666;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="print-header">
                            <h1>KEMENTERIAN AGRARIA DAN TATA RUANG / BADAN PERTANAHAN NASIONAL</h1>
                            <h2>Data Kantor BPN Seluruh Indonesia</h2>
                            <div class="date-info">
                                Tanggal Cetak: ${formattedDate} ${formattedTime}
                            </div>
                        </div>
                        
                        <!-- Tabel yang sudah dimodifikasi -->
                        ${tableClone.outerHTML}
                        
                        <div class="print-footer">
                            <p>Dokumen ini dicetak dari Sistem Informasi Geografis (SIG) BPN</p>
                            <p>Data ini bersifat informatif dan dapat berubah sewaktu-waktu</p>
                        </div>
                        
                        <script>
                            // Trigger print otomatis setelah halaman siap
                            window.onload = function() {
                                window.print();
                                // Opsional: window.close() setelah print
                                // window.onafterprint = function() { window.close(); };
                            };
                        </script>
                    </body>
                    </html>
                `);

    printWindow.document.close();
  } catch (error) {
    console.error("Error saat print:", error);
    alert("Terjadi kesalahan saat mencetak: " + error.message);
  }
}

// Versi alternatif: Print hanya kolom tertentu (jika metode di atas tidak berhasil)
function printTableAlternate() {
  try {
    // Dapatkan data dari tabel
    const headers = [];
    const rows = [];

    // Ambil header (kecuali kolom terakhir)
    $("#officesTable thead th").each(function (index) {
      if (index < $("#officesTable thead th").length - 1) {
        headers.push($(this).text());
      }
    });

    // Ambil data baris (kecuali kolom terakhir)
    $("#officesTable tbody tr").each(function () {
      const row = [];
      $(this)
        .find("td")
        .each(function (index) {
          if (index < $(this).siblings().length) {
            // Ambil semua kecuali terakhir
            row.push($(this).text());
          }
        });
      rows.push(row);
    });

    // Buat tabel HTML baru
    let tableHtml = '<table class="table table-bordered">';

    // Header
    tableHtml += "<thead><tr>";
    headers.forEach((header) => {
      tableHtml += `<th>${header}</th>`;
    });
    tableHtml += "</tr></thead>";

    // Body
    tableHtml += "<tbody>";
    rows.forEach((row) => {
      tableHtml += "<tr>";
      row.forEach((cell) => {
        tableHtml += `<td>${cell}</td>`;
      });
      tableHtml += "</tr>";
    });
    tableHtml += "</tbody></table>";

    // Format tanggal
    const today = new Date();
    const formattedDate = today.toLocaleDateString("id-ID", {
      day: "2-digit",
      month: "long",
      year: "numeric",
    });

    // Buka window print
    const printWindow = window.open("", "_blank");
    printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Data Kantor BPN</title>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
                        <style>
                            body { padding: 20px; }
                            @media print {
                                .no-print { display: none; }
                                @page { size: A4 landscape; }
                            }
                        </style>
                    </head>
                    <body>
                        <h2 class="text-center mb-4">Data Kantor BPN Seluruh Indonesia</h2>
                        <p class="text-center">Tanggal Cetak: ${formattedDate}</p>
                        ${tableHtml}
                        <p class="text-muted mt-4"><small>Dicetak dari SIG BPN</small></p>
                    </body>
                    </html>
                `);
    printWindow.document.close();
    printWindow.print();
  } catch (error) {
    console.error("Error:", error);
    alert("Terjadi kesalahan: " + error.message);
  }
}
