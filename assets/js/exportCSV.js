// Export ke CSV
function exportTableToCSV() {
  const filtered = currentOffices; // Atau bisa ambil dari data yang sedang ditampilkan

  if (filtered.length === 0) {
    alert("Tidak ada data untuk diexport");
    return;
  }

  // Header CSV
  let csv =
    "No,ID,Nama Kantor,Alamat,Kota,Provinsi,Telepon,Email,Tipe,Deskripsi\n";

  // Data
  filtered.forEach((office, index) => {
    csv += `${index + 1},"${office.id}","${office.name}","${office.address}","${office.city}",`;
    csv += `"${office.province}","${office.phone || ""}","${office.email || ""}",`;
    csv += `"${office.type}","${office.description || ""}"\n`;
  });

  // Download
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
