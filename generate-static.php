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

// 2. Buat folder output
$outputDir = __DIR__ . '/_site';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

// 3. Copy assets (CSS, JS, images)
echo "Mengcopy assets...\n";
function copyDir($src, $dst)
{
    $dir = opendir($src);
    @mkdir($dst, 0755, true);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..') && ($file != '_site')) {
            if (is_dir($src . '/' . $file)) {
                copyDir($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

// Copy folder assets
copyDir(__DIR__ . '/assets', $outputDir . '/assets');

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
file_put_contents($outputDir . '/index.html', $homeContent);

// 5. Generate about.html
echo "Generate about.html...\n";
ob_start();
include __DIR__ . '/about.php';
$aboutContent = ob_get_clean();
$aboutContent = str_replace(
    'window.allOffices = window.allOffices || [];',
    'window.allOffices = ' . json_encode($offices, JSON_PRETTY_PRINT) . ';',
    $aboutContent
);
file_put_contents($outputDir . '/about.html', $aboutContent);

// 6. Copy data JSON untuk akses via JavaScript
echo "Copy data JSON...\n";
copy($jsonFile, $outputDir . '/data/offices.json');

echo "✅ Static site generation selesai! File tersedia di folder _site/\n";
echo "🚀 Siap di-deploy ke Netlify!\n";
