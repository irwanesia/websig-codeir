// OpenStreetMap + Leaflet implementation
let map;
let markers = [];

function initOpenStreetMap() {
  console.log("Initializing OpenStreetMap...");

  // Center on Indonesia
  map = L.map("map").setView([-2.5489, 118.0149], 5);

  // Add OpenStreetMap tiles
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution:
      '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    maxZoom: 19,
    minZoom: 3,
  }).addTo(map);

  // Add scale control
  L.control.scale().addTo(map);

  // Load offices data
  loadOffices();
}

async function loadOffices() {
  try {
    console.log("Loading offices data for OpenStreetMap...");
    const response = await fetch("/data/offices.json");

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    console.log("Data loaded for OpenStreetMap:", data);

    if (data.offices && data.offices.length > 0) {
      displayOffices(data.offices);
      populateOfficeList(data.offices);
    } else {
      console.warn("No offices data found");
      document.getElementById("officeList").innerHTML =
        '<p class="no-data">Data kantor belum tersedia.</p>';
    }
  } catch (error) {
    console.error("Error loading offices for OpenStreetMap:", error);
    document.getElementById("officeList").innerHTML =
      `<p class="error">Error loading data: ${error.message}</p>`;
  }
}

function displayOffices(offices) {
  // Clear existing markers
  markers.forEach((marker) => map.removeLayer(marker));
  markers = [];

  const bounds = [];

  offices.forEach((office) => {
    // Validate coordinates
    if (!office.latitude || !office.longitude) {
      console.error(`Missing coordinates for office ${office.name}`);
      return;
    }

    const lat = parseFloat(office.latitude);
    const lng = parseFloat(office.longitude);

    if (isNaN(lat) || isNaN(lng)) {
      console.error(`Invalid coordinates for office ${office.name}:`, office);
      return;
    }

    // Choose icon color based on type
    let iconColor = "green";
    if (office.type === "Pusat") iconColor = "red";
    if (office.type === "Kanwil") iconColor = "blue";

    // Create custom icon
    const customIcon = L.divIcon({
      className: "custom-marker",
      html: `<div style="background-color: ${iconColor}; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.5);"></div>`,
      iconSize: [20, 20],
      iconAnchor: [10, 10],
    });

    // Create marker
    const marker = L.marker([lat, lng], {
      icon: customIcon,
      title: office.name,
    }).addTo(map);

    // Create popup content
    const popupContent = `
            <div style="min-width: 200px;">
                <h3 style="margin: 0 0 10px 0; color: #2c3e50;">${office.name}</h3>
                <p style="margin: 5px 0;"><strong>Alamat:</strong> ${office.address || "-"}</p>
                <p style="margin: 5px 0;"><strong>Kota:</strong> ${office.city || "-"}</p>
                <p style="margin: 5px 0;"><strong>Provinsi:</strong> ${office.province || "-"}</p>
                <p style="margin: 5px 0;"><strong>Telepon:</strong> ${office.phone || "-"}</p>
                <p style="margin: 5px 0;"><strong>Email:</strong> ${office.email || "-"}</p>
                <p style="margin: 5px 0;"><strong>Tipe:</strong> ${office.type || "Kantah"}</p>
            </div>
        `;

    marker.bindPopup(popupContent);

    markers.push(marker);
    bounds.push([lat, lng]);
  });

  // Auto-zoom to fit all markers
  if (bounds.length > 0) {
    map.fitBounds(bounds);
  }
}

// Populate office list (similar to Google Maps version)
function populateOfficeList(offices) {
  const listContainer = document.getElementById("officeList");
  if (!listContainer) return;

  listContainer.innerHTML = "";

  offices.forEach((office) => {
    const officeItem = document.createElement("div");
    officeItem.className = "office-item";
    officeItem.innerHTML = `
            <h4>${office.name}</h4>
            <p><strong>Alamat:</strong> ${office.address}</p>
            <p><strong>Kota:</strong> ${office.city}, ${office.province}</p>
            <p><strong>Telepon:</strong> ${office.phone}</p>
            <p><strong>Tipe:</strong> <span class="office-type ${office.type || "Kantah"}">${office.type || "Kantah"}</span></p>
            <button onclick="zoomToOfficeOSM(${office.latitude}, ${office.longitude}, '${office.name.replace("'", "\\'")}')" class="view-btn">Lihat di Peta</button>
        `;

    listContainer.appendChild(officeItem);
  });
}

// Zoom to specific office in OpenStreetMap
function zoomToOfficeOSM(lat, lng, name) {
  const position = [parseFloat(lat), parseFloat(lng)];
  map.setView(position, 14);

  // Find and open popup
  markers.forEach((marker) => {
    if (marker.options.title === name) {
      marker.openPopup();
    }
  });
}

// Search offices for OpenStreetMap
function searchOfficeOSM() {
  const searchTerm = document.getElementById("searchInput").value.toLowerCase();

  fetch("/data/offices.json")
    .then((response) => response.json())
    .then((data) => {
      const filtered = data.offices.filter(
        (office) =>
          office.name.toLowerCase().includes(searchTerm) ||
          (office.city && office.city.toLowerCase().includes(searchTerm)) ||
          (office.province &&
            office.province.toLowerCase().includes(searchTerm)) ||
          (office.address && office.address.toLowerCase().includes(searchTerm)),
      );

      displayOffices(filtered);
      populateOfficeList(filtered);
    })
    .catch((error) => {
      console.error("Search error:", error);
    });
}

// Reset OpenStreetMap
function resetMapOSM() {
  document.getElementById("searchInput").value = "";
  loadOffices();
}

// Initialize when page loads
document.addEventListener("DOMContentLoaded", function () {
  console.log("DOM loaded, initializing OpenStreetMap...");
  initOpenStreetMap();
});
