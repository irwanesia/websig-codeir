import json
import os
from datetime import datetime

# Data kantor wilayah BPN (contoh lengkap)
offices_data = {
    "offices": [
        {
            "id": "BPN001",
            "name": "Kantor Wilayah BPN Provinsi Aceh",
            "address": "Lamgugob, Jl. Teuku Nyak Arief, Lamgugob, Aceh 24415",
            "city": "Kota Banda Aceh",
            "province": "Aceh",
            "latitude": 5.576359,
            "longitude": 95.355722,
            "phone": "(0651)7551708",
            "email": "-@bpn.go.id",
            "type": "Kanwil",
            "description": "Kantor Wilayah BPN Provinsi Aceh Republik Indonesia"
        },
        {
            "id": "BPN002",
            "name": "Kantor Wilayah BPN Jawa Barat",
            "address": "Jl. LLRE Martadinata No. 153",
            "city": "Bandung",
            "province": "Jawa Barat",
            "latitude": -6.912024,
            "longitude": 107.619125,
            "phone": "(022) 4203328",
            "email": "kanwil.jabar@bpn.go.id",
            "type": "Kanwil",
            "description": "Kantor Wilayah BPN Provinsi Jawa Barat"
        },
        {
            "id": "BPN003",
            "name": "Kantor Wilayah BPN DKI Jakarta",
            "address": "Jl. HR Rasuna Said Kav. 2-3, Kuningan, Jakarta Selatan",
            "city": "Jakarta Selatan",
            "province": "DKI Jakarta",
            "latitude": -6.2178,
            "longitude": 106.8302,
            "phone": "(021) 5229058",
            "email": "kanwildkijakarta@bpn.go.id",
            "type": "Kanwil",
            "description": "Kantor Wilayah BPN Provinsi DKI Jakarta"
        },
        {
            "id": "BPN004",
            "name": "Kantor Wilayah BPN Jawa Tengah",
            "address": "Jl. Pemuda No. 149, Semarang",
            "city": "Semarang",
            "province": "Jawa Tengah",
            "latitude": -6.9824,
            "longitude": 110.4088,
            "phone": "(024) 8311371",
            "email": "kanwiljateng@bpn.go.id",
            "type": "Kanwil",
            "description": "Kantor Wilayah BPN Provinsi Jawa Tengah"
        },
        {
            "id": "BPN005",
            "name": "Kantor Wilayah BPN Jawa Timur",
            "address": "Jl. Jenderal Basuki Rahmat No. 116, Surabaya",
            "city": "Surabaya",
            "province": "Jawa Timur",
            "latitude": -7.2575,
            "longitude": 112.7521,
            "phone": "(031) 5313199",
            "email": "kanwiljatim@bpn.go.id",
            "type": "Kanwil",
            "description": "Kantor Wilayah BPN Provinsi Jawa Timur"
        }
    ],
    "metadata": {
        "total_offices": 5,
        "last_updated": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
        "data_source": "BPN Directory",
        "version": "1.0.0"
    }
}

# Simpan ke file
data_dir = "data"
os.makedirs(data_dir, exist_ok=True)

output_file = os.path.join(data_dir, "offices.json")
with open(output_file, "w", encoding="utf-8") as f:
    json.dump(offices_data, f, indent=2, ensure_ascii=False)

print(f"✅ Data berhasil disimpan ke {output_file}")
print(f"📊 Total data: {len(offices_data['offices'])} kantor")

# Buat juga file versi minimal untuk Leaflet
offices_minimal = []
for office in offices_data["offices"]:
    offices_minimal.append({
        "id": office["id"],
        "name": office["name"],
        "lat": office["latitude"],
        "lng": office["longitude"],
        "city": office["city"],
        "type": office["type"]
    })

minimal_data = {
    "type": "FeatureCollection",
    "features": [
        {
            "type": "Feature",
            "geometry": {
                "type": "Point",
                "coordinates": [office["lng"], office["lat"]]
            },
            "properties": {
                "id": office["id"],
                "name": office["name"],
                "city": office["city"],
                "type": office["type"]
            }
        }
        for office in offices_minimal
    ]
}

minimal_file = os.path.join(data_dir, "offices_minimal.json")
with open(minimal_file, "w", encoding="utf-8") as f:
    json.dump(minimal_data, f, indent=2, ensure_ascii=False)

print(f"✅ Data minimal untuk peta disimpan ke {minimal_file}")