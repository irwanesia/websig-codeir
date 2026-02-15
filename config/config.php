<?php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Use OpenStreetMap by default (free, no API key needed)
$config = [
    'site_name' => 'WebGIS Kantor BPN Seluruh Indonesia',
    'use_google_maps' => false, // Set to false for OpenStreetMap
    'google_maps_api' => 'https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap&loading=async',
    'data_file' => __DIR__ . '/../data/offices.json',
    'version' => '1.0.0'
];

// Cek apakah file data ada
if (!file_exists($config['data_file'])) {
    // Create sample data file
    $initialData = [
        'offices' => [
            [
                'id' => 'BPN001',
                'name' => 'Kantor Contoh',
                'address' => 'Jl. Contoh No. 123',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'latitude' => -6.2088,
                'longitude' => 106.8456,
                'phone' => '(021) 1234567',
                'email' => 'contoh@bpn.go.id',
                'type' => 'Kantah'
            ]
        ]
    ];

    $dir = dirname($config['data_file']);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    file_put_contents($config['data_file'], json_encode($initialData, JSON_PRETTY_PRINT));
}

// Helper functions
function getOfficesData()
{
    global $config;
    if (!file_exists($config['data_file'])) {
        return ['offices' => []];
    }
    $jsonData = file_get_contents($config['data_file']);
    return json_decode($jsonData, true);
}

function saveOfficesData($data)
{
    global $config;
    $jsonData = json_encode($data, JSON_PRETTY_PRINT);
    return file_put_contents($config['data_file'], $jsonData);
}

// Untuk testing
if (isset($_GET['test'])) {
    echo "Config loaded successfully!<br>";
    echo "Data file: " . $config['data_file'] . "<br>";
    echo "Data file exists: " . (file_exists($config['data_file']) ? 'Yes' : 'No') . "<br>";
    echo "Using map: " . ($config['use_google_maps'] ? 'Google Maps' : 'OpenStreetMap') . "<br>";

    $data = getOfficesData();
    echo "Office count: " . count($data['offices']) . "<br>";
}
