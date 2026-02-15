# README.md yang Diperbaiki (Menggunakan OpenStreetMap)

# Sistem Informasi Geografis (SIG) BPN

Sistem pemetaan lokasi kantor Badan Pertanahan Nasional (BPN) seluruh Indonesia menggunakan **OpenStreetMap** dan **Leaflet JS**.

## ✨ Fitur Utama

1. **Peta Interaktif** - Menggunakan OpenStreetMap + Leaflet (gratis, tanpa API Key)
2. **Visualisasi Data** - Menampilkan titik lokasi kantor BPN (marker) seluruh Indonesia dengan kode warna berbeda:
   - 🔴 Merah: Kantor Wilayah (Kanwil)
   - 🔵 Biru: Kantor Pertanahan (Kantah)
   - 🟢 Hijau: Kantor Pusat
3. **Informasi Detail** - Popup info lengkap (nama kantor, alamat, telepon, email)
4. **Pencarian & Filter** - Cari berdasarkan nama, kota, provinsi, atau jenis kantor
5. **Layer Peta** - Pilihan tampilan peta (Standard, Topografi, Satellite)
6. **Export Data** - Download data kantor ke format CSV
7. **Admin Panel** - CRUD data kantor melalui form (dengan login sederhana)
8. **Responsive Design** - Tampilan optimal di desktop, tablet, dan mobile
9. **Data Statis** - Menggunakan file JSON (tanpa database, loading cepat)
10. **Multi Halaman** - Home, About, Admin Panel

## 🏗️ Struktur Proyek

sig-bpn/
├── index.php # Redirect ke home.php
├── home.php # Halaman utama peta
├── about.php # Halaman tentang
├── admin.php # Panel admin (dengan login)
├── config/
│ └── config.php # Konfigurasi aplikasi
├── includes/
│ ├── header.php # Header & navbar
│ └── footer.php # Footer & scripts
├── data/
│ └── offices.json # Data kantor BPN
├── assets/
│ ├── css/
│ │ └── style.css # Custom CSS
│ └── js/
│ └── main.js # Main JavaScript
└── .htaccess # Konfigurasi Apache

## 📋 Format Data JSON

File `data/offices.json` menyimpan data kantor dengan struktur berikut:

```json
{
  "offices": [
    {
      "id": "BPN001",
      "name": "Kantor Wilayah BPN Provinsi Aceh",
      "address": "Jl. Teuku Nyak Arief, Lamgugob",
      "city": "Kota Banda Aceh",
      "province": "Aceh",
      "latitude": 5.576359,
      "longitude": 95.355722,
      "phone": "(0651) 7551708",
      "email": "kanwil.aceh@bpn.go.id",
      "type": "Kanwil",
      "description": "Kantor Wilayah BPN Provinsi Aceh"
    }
  ]
}
```

### Tipe Kantor:

- `Pusat` - Kantor Pusat BPN (warna hijau)
- `Kanwil` - Kantor Wilayah (warna merah)
- `Kantah` - Kantor Pertanahan (warna biru)

## 🚀 Instalasi dengan Docker

### Prerequisites

- Docker dan Docker Compose terinstal di sistem Anda

### Langkah Instalasi

1. Clone atau salin proyek ke folder `sig-bpn`:

   ```bash
   git clone https://github.com/username/sig-bpn.git
   cd sig-bpn
   ```

2. Build dan jalankan container:

   ```bash
   docker-compose up -d --build
   ```

3. Akses aplikasi di browser:
   ```
   http://localhost:8090
   ```

### Dockerfile

```dockerfile
FROM php:8.3-apache
RUN apt-get update && apt-get install -y libzip-dev zip unzip
RUN docker-php-ext-install zip
RUN a2enmod rewrite
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod 666 /var/www/html/data/offices.json
EXPOSE 80
```

### docker-compose.yml

```yaml
version: "3.8"
services:
  sig-bpn:
    build: .
    container_name: sig-bpn
    ports:
      - "8090:80"
    volumes:
      - ./:/var/www/html
```

## 💻 Instalasi Manual (Tanpa Docker)

### Prerequisites

- Web server (Apache/Nginx) dengan PHP 7.4+
- Browser modern (Chrome, Firefox, Edge)

### Langkah Instalasi

1. Copy semua file ke root direktori web server (contoh: `/var/www/html/`)

2. Set permission untuk folder data:

   ```bash
   chmod 755 data/
   chmod 644 data/offices.json
   ```

3. Konfigurasi Apache (pastikan mod_rewrite aktif):

   ```bash
   sudo a2enmod rewrite
   sudo systemctl restart apache2
   ```

4. Akses aplikasi:
   ```
   http://localhost/
   ```

## 🔧 Konfigurasi

### File `config/config.php`

```php
<?php
$config = [
    'site_name' => 'SIG BPN',
    'version' => '2.0.0',
    'data_file' => 'data/offices.json'
];
```

### File `.htaccess`

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?path=$1 [L,QSA]

# Proteksi file sensitif
<Files "*.json">
    Order allow,deny
    Deny from all
</Files>

# Izinkan akses ke halaman utama
<FilesMatch "^(index|home|about|admin)\.php$">
    Order allow,deny
    Allow from all
</FilesMatch>
```

## 📱 Fitur Aplikasi

### Halaman Home (`home.php`)

- Peta interaktif dengan marker kantor BPN
- Sidebar filter dan pencarian
- Daftar kantor dalam bentuk tabel
- Layer switcher (OSM, Topografi, Satellite)
- Map controls (zoom, lokasi saya, legenda)

### Halaman About (`about.php`)

- Informasi tentang aplikasi
- Statistik data (total kantor, provinsi, jenis)
- Teknologi yang digunakan
- Informasi kontak

### Halaman Admin (`admin.php`)

- Login sederhana (username: `admin`, password: `bpn2026`)
- CRUD data kantor (Tambah, Edit, Hapus)
- Tabel DataTables dengan fitur search dan pagination
- Export data ke CSV
- Print tabel

## 🌍 Teknologi yang Digunakan

| Teknologi         | Kegunaan                         |
| ----------------- | -------------------------------- |
| **PHP 8.3**       | Backend & templating             |
| **OpenStreetMap** | Peta dasar                       |
| **Leaflet JS**    | Library peta interaktif          |
| **Bootstrap 5**   | UI Framework & responsive design |
| **jQuery**        | DOM manipulation                 |
| **DataTables**    | Tabel interaktif                 |
| **JSON**          | Penyimpanan data                 |
| **Docker**        | Containerization                 |

## 🔒 Keamanan

- File JSON tidak dapat diakses langsung (diblokir .htaccess)
- Login sederhana untuk admin panel
- Input validation untuk form CRUD
- XSS protection dengan escaping HTML

## 📊 Statistik Data

- **Total Kantor**: 5+ data sample
- **Provinsi**: Aceh, Jawa Barat, DKI Jakarta
- **Jenis Kantor**: Pusat, Kanwil, Kantah

## 🎯 Penggunaan

### User Umum

1. Buka halaman Home
2. Navigasi peta untuk melihat lokasi kantor
3. Klik marker untuk melihat detail
4. Gunakan fitur pencarian dan filter
5. Export data jika diperlukan

### Admin

1. Buka halaman Admin (`/admin.php`)
2. Login dengan credential default
3. Kelola data kantor (tambah/edit/hapus)
4. Export/print data

## 🚢 Deployment ke Production

### Opsi Hosting:

1. **VPS** (DigitalOcean, Linode, Vultr)
2. **Shared Hosting** dengan PHP support
3. **Cloud Run** atau **AWS ECS** (dengan container)
4. **Free Hosting** atau **Netlify** (langsung dengan folder)

### Langkah Deployment:

1. Set environment variables untuk production
2. Ganti password default admin
3. Aktifkan HTTPS
4. Backup data secara berkala

## 📝 To Do / Pengembangan Selanjutnya

- [ ] Integrasi dengan API BPN untuk data real-time
- [ ] Fitur pencarian rute antar lokasi
- [ ] Ekspor ke PDF dengan template
- [ ] Multiple admin users dengan role
- [ ] Logging aktivitas admin
- [ ] Dark mode
- [ ] Peta offline mode

## 🤝 Kontribusi

Kontribusi selalu diterima! Silakan:

1. Fork repository
2. Buat branch baru
3. Commit perubahan
4. Pull request

## 📄 Lisensi

Hak Cipta © 2026 Code n Support.
Dikembangkan untuk keperluan latihan dan tugas mahasiswa.

## 📞 Kontak

- **Email**: irwan.codeir@gmail.com
- **Website**: [https://https://cobextechsupport.netlify.app/](https://https://cobextechsupport.netlify.app/)
- **GitHub**: [https://github.com/bpn/sig-bpn](https://github.com/bpn/sig-bpn)

## 🙏 Credits

- [OpenStreetMap](https://www.openstreetmap.org/) - Peta gratis
- [Leaflet](https://leafletjs.com/) - Library peta interaktif
- [Bootstrap](https://getbootstrap.com/) - Framework CSS
- [DataTables](https://datatables.net/) - Tabel interaktif
- [Bootstrap Icons](https://icons.getbootstrap.com/) - Icon set

---

**Catatan Penting:**

- Aplikasi ini **GRATIS** karena menggunakan OpenStreetMap (tidak perlu API key)
- Data bersifat statis dalam file JSON untuk kecepatan akses
- Untuk production, segera ganti password default admin!

```

## Perubahan Utama yang Dilakukan:

1. ✅ **Google Maps API** → **OpenStreetMap + Leaflet**
2. ✅ Menambahkan informasi **teknologi yang digunakan**
3. ✅ Menambahkan **struktur proyek** yang jelas
4. ✅ Menambahkan **fitur-fitur** yang sudah diimplementasikan
5. ✅ Menambahkan **tabel warna marker** sesuai tipe kantor
6. ✅ Menambahkan **section To Do** untuk pengembangan
7. ✅ Memperbaiki **format markdown** agar lebih rapi
8. ✅ Menambahkan **badge** dan emoji untuk visualisasi
9. ✅ Menambahkan **informasi login** admin
10. ✅ Menambahkan **credits** untuk library yang digunakan

README sekarang lebih informatif, profesional, dan sesuai dengan implementasi OpenStreetMap!

## Deployment

### Aplikasi dapat dideploy ke hosting statis gratis seperti:

1. GitHub Pages
2. Netlify
3. Vercel
4. Firebase Hosting
```
