// Google Maps implementation
let map;
let markers = [];
let infoWindow;

// Initialize Google Map
function initMap() {
  // Default center: Indonesia
  const center = { lat: -2.5489, lng: 118.0149 };

  // Create map
  map = new google.maps.Map(document.getElementById("map"), {
    zoom: 5,
    center: center,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    mapTypeControl: true,
    streetViewControl: false,
    fullscreenControl: true,
  });

  infoWindow = new google.maps.InfoWindow();

  // Load offices data
  loadOffices();
}

// Load offices from JSON
async function loadOffices() {
  try {
    console.log("Loading offices data...");
    const response = await fetch("/data/offices.json");

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    console.log("Data loaded:", data);

    if (data.offices && data.offices.length > 0) {
      displayOffices(data.offices);
      populateOfficeList(data.offices);
    } else {
      console.warn("No offices data found");
      document.getElementById("officeList").innerHTML =
        '<p class="no-data">Data kantor belum tersedia.</p>';
    }
  } catch (error) {
    console.error("Error loading offices:", error);
    document.getElementById("officeList").innerHTML =
      `<p class="error">Error loading data: ${error.message}</p>`;
  }
}

// Display offices on map
function displayOffices(offices) {
  // Clear existing markers
  clearMarkers();

  // Create bounds for auto-zoom
  const bounds = new google.maps.LatLngBounds();

  offices.forEach((office) => {
    const position = {
      lat: parseFloat(office.latitude),
      lng: parseFloat(office.longitude),
    };

    // Validate coordinates
    if (isNaN(position.lat) || isNaN(position.lng)) {
      console.error(`Invalid coordinates for office ${office.name}:`, office);
      return;
    }

    // Create marker
    const marker = new google.maps.Marker({
      position: position,
      map: map,
      title: office.name,
      icon: getMarkerIcon(office.type || "Kantah"),
      animation: google.maps.Animation.DROP,
    });

    // Add click listener
    marker.addListener("click", () => {
      showOfficeInfo(office, marker);
    });

    markers.push(marker);
    bounds.extend(position);
  });

  // Auto-zoom to fit all markers
  if (offices.length > 0) {
    map.fitBounds(bounds);

    // Don't zoom in too close if only one marker
    if (offices.length === 1) {
      map.setZoom(12);
    }
  }
}

// Get marker icon based on office type
function getMarkerIcon(type) {
  const icons = {
    Pusat: {
      url: "http://maps.google.com/mapfiles/ms/icons/red-dot.png",
      scaledSize: new google.maps.Size(40, 40),
    },
    Kanwil: {
      url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png",
      scaledSize: new google.maps.Size(40, 40),
    },
    Kantah: {
      url: "http://maps.google.com/mapfiles/ms/icons/green-dot.png",
      scaledSize: new google.maps.Size(40, 40),
    },
  };

  return (
    icons[type] || {
      url: "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png",
      scaledSize: new google.maps.Size(40, 40),
    }
  );
}

// Show office info in popup
function showOfficeInfo(office, marker) {
  const content = `
        <div class="info-window" style="min-width: 200px;">
            <h3 style="margin: 0 0 10px 0; color: #2c3e50;">${office.name}</h3>
            <p style="margin: 5px 0;"><strong>Alamat:</strong> ${office.address || "-"}</p>
            <p style="margin: 5px 0;"><strong>Kota:</strong> ${office.city || "-"}</p>
            <p style="margin: 5px 0;"><strong>Provinsi:</strong> ${office.province || "-"}</p>
            <p style="margin: 5px 0;"><strong>Telepon:</strong> ${office.phone || "-"}</p>
            <p style="margin: 5px 0;"><strong>Email:</strong> ${office.email || "-"}</p>
            <p style="margin: 5px 0;"><strong>Tipe:</strong> ${office.type || "Kantah"}</p>
            <p style="margin: 5px 0;"><strong>Keterangan:</strong> ${office.description || "-"}</p>
        </div>
    `;

  infoWindow.setContent(content);
  infoWindow.open(map, marker);
}

// Clear all markers
function clearMarkers() {
  markers.forEach((marker) => marker.setMap(null));
  markers = [];
}

// Populate office list
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
            <button onclick="zoomToOffice(${office.latitude}, ${office.longitude}, '${office.name.replace("'", "\\'")}')" class="view-btn">Lihat di Peta</button>
        `;

    listContainer.appendChild(officeItem);
  });
}

// Zoom to specific office
function zoomToOffice(lat, lng, name) {
  const position = { lat: parseFloat(lat), lng: parseFloat(lng) };
  map.setCenter(position);
  map.setZoom(14);

  // Find and trigger marker click
  markers.forEach((marker) => {
    if (marker.getTitle() === name) {
      google.maps.event.trigger(marker, "click");
    }
  });
}

// Search offices
function searchOffice() {
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

// Reset map
function resetMap() {
  document.getElementById("searchInput").value = "";
  loadOffices();
}

// Error handler for Google Maps
window.gm_authFailure = function () {
  console.error("Google Maps authentication failed");
  document.getElementById("map").innerHTML =
    '<div class="map-error"><h3>Error: Google Maps tidak dapat dimuat</h3><p>Silakan periksa API Key atau koneksi internet.</p></div>';
};

// Initialize when Google Maps is loaded
if (typeof google !== "undefined") {
  console.log("Google Maps API loaded");
} else {
  console.log("Google Maps API not loaded yet");
}
