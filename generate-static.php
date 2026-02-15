<?php

/**
 * Static Site Generator untuk WebGIS BPN
 * Jalankan dengan: php generate-static.php
 */

echo "Memulai static site generation...\n";

// 1. Baca data JSON
$jsonFile = __DIR__ . '/data/offices.json';
if (!file_exists($jsonFile)) {
    die("Error: File data/offices.json tidak ditemukan!\n");
}

$data = json_decode(file_get_contents($jsonFile), true);
$offices = $data['offices'] ?? [];

echo "Membaca " . count($offices) . " data kantor...\n";

// 2. Buat folder output dan subfolder yang diperlukan
$outputDir = __DIR__ . '/_site';
$dataDir = $outputDir . '/data';
$assetsDir = $outputDir . '/assets';

// Buat folder utama jika belum ada
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
    echo "Membuat folder _site...\n";
}

// Buat subfolder data
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
    echo "Membuat folder _site/data...\n";
}

// Buat subfolder assets
if (!is_dir($assetsDir)) {
    mkdir($assetsDir, 0755, true);
    echo "Membuat folder _site/assets...\n";
}

// 3. Copy assets (CSS, JS, images) - dengan pengecekan folder
echo "Mengcopy assets...\n";
function copyDir($src, $dst)
{
    if (!is_dir($src)) {
        echo "Warning: Source folder $src tidak ditemukan, melewati...\n";
        return;
    }

    $dir = opendir($src);
    if (!is_dir($dst)) {
        mkdir($dst, 0755, true);
    }

    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..') && ($file != '_site')) {
            $srcFile = $src . '/' . $file;
            $dstFile = $dst . '/' . $file;

            if (is_dir($srcFile)) {
                copyDir($srcFile, $dstFile);
            } else {
                copy($srcFile, $dstFile);
                echo "  Copy: $file\n";
            }
        }
    }
    closedir($dir);
}

// Copy folder assets jika ada
if (is_dir(__DIR__ . '/assets')) {
    copyDir(__DIR__ . '/assets', $outputDir . '/assets');
} else {
    echo "Warning: Folder assets tidak ditemukan, membuat folder kosong...\n";
    mkdir($outputDir . '/assets', 0755, true);
}

// 4. Generate file index.html (home)
echo "Generate home.html...\n";
ob_start();
include __DIR__ . '/home.php';
$homeContent = ob_get_clean();

// Embed data JSON ke JavaScript
$homeContent = str_replace(
    'window.allOffices = window.allOffices || [];',
    'window.allOffices = ' . json_encode($offices, JSON_PRETTY_PRINT) . ';',
    $homeContent
);

// Fix path assets untuk Netlify
$homeContent = str_replace(
    'href="/assets/',
    'href="assets/',
    $homeContent
);
$homeContent = str_replace(
    'src="/assets/',
    'src="assets/',
    $homeContent
);

file_put_contents($outputDir . '/index.html', $homeContent);
echo "  home.html berhasil dibuat\n";

// 5. Generate about.html
echo "Generate about.html...\n";
if (file_exists(__DIR__ . '/about.php')) {
    ob_start();
    include __DIR__ . '/about.php';
    $aboutContent = ob_get_clean();

    // Embed data JSON ke JavaScript
    $aboutContent = str_replace(
        'window.allOffices = window.allOffices || [];',
        'window.allOffices = ' . json_encode($offices, JSON_PRETTY_PRINT) . ';',
        $aboutContent
    );

    // Fix path assets
    $aboutContent = str_replace(
        'href="/assets/',
        'href="assets/',
        $aboutContent
    );
    $aboutContent = str_replace(
        'src="/assets/',
        'src="assets/',
        $aboutContent
    );

    file_put_contents($outputDir . '/about.html', $aboutContent);
    echo "  about.html berhasil dibuat\n";
} else {
    echo "Warning: about.php tidak ditemukan\n";
}

// 6. Generate admin.html (jika ada)
echo "Generate admin.html...\n";
if (file_exists(__DIR__ . '/admin.php')) {
    ob_start();
    include __DIR__ . '/admin.php';
    $adminContent = ob_get_clean();

    // Embed data JSON ke JavaScript
    $adminContent = str_replace(
        'window.allOffices = window.allOffices || [];',
        'window.allOffices = ' . json_encode($offices, JSON_PRETTY_PRINT) . ';',
        $adminContent
    );

    // Fix path assets
    $adminContent = str_replace(
        'href="/assets/',
        'href="assets/',
        $adminContent
    );
    $adminContent = str_replace(
        'src="/assets/',
        'src="assets/',
        $adminContent
    );

    file_put_contents($outputDir . '/admin.html', $adminContent);
    echo "  admin.html berhasil dibuat\n";
} else {
    echo "Warning: admin.php tidak ditemukan\n";
}

// 7. Copy data JSON untuk akses via JavaScript
echo "Copy data JSON...\n";
copy($jsonFile, $dataDir . '/offices.json');
echo "  offices.json berhasil disalin\n";

// 8. Buat file .htaccess untuk Netlify (redirects)
echo "Membuat file konfigurasi Netlify...\n";
$netlifyConfig = <<<EOT
# Redirect rules untuk Netlify
/ /index.html 200
/* /index.html 200

# Caching headers
/assets/*
  Cache-Control: public, max-age=31536000, immutable

/*.html
  Cache-Control: public, max-age=0, must-revalidate

/data/*.json
  Cache-Control: public, max-age=0, must-revalidate
EOT;

file_put_contents($outputDir . '/_redirects', $netlifyConfig);
echo "  _redirects berhasil dibuat\n";

// 9. Buat file 404.html
echo "Membuat file 404.html...\n";
$notFound = <<<EOT
<!DOCTYPE html>
<html>
<head>
    <title>Halaman Tidak Ditemukan - SIG BPN</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container text-center py-5">
        <h1 class="display-1">404</h1>
        <h2 class="mb-4">Halaman Tidak Ditemukan</h2>
        <p class="lead mb-4">Maaf, halaman yang Anda cari tidak ada atau telah dipindahkan.</p>
        <a href="/" class="btn btn-primary">Kembali ke Beranda</a>
    </div>
</body>
</html>
EOT;
file_put_contents($outputDir . '/404.html', $notFound);

echo "\n✅ Static site generation selesai!\n";
echo "📁 File tersedia di folder: " . realpath($outputDir) . "\n";
echo "🚀 Siap di-deploy ke Netlify!\n";
echo "\n📊 Statistik:\n";
echo "   - Total kantor: " . count($offices) . "\n";
echo "   - File HTML: index.html, about.html, admin.html\n";
echo "   - Assets: CSS, JS, images\n";
echo "   - Data: offices.json\n";
echo "\n💡 Untuk deploy:\n";
echo "   1. Upload folder _site ke Netlify Drop\n";
echo "   2. Atau commit folder _site ke GitHub\n";
