let map;
let markers = [];
let infoWindow;

// Initialize map
function initMap() {
  // Default center: Indonesia
  const center = { lat: -2.5489, lng: 118.0149 };

  // Create map
  map = new google.maps.Map(document.getElementById("map"), {
    zoom: 5,
    center: center,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    styles: [
      {
        featureType: "administrative",
        elementType: "labels.text.fill",
        stylers: [{ color: "#444444" }],
      },
      {
        featureType: "landscape",
        elementType: "all",
        stylers: [{ color: "#f2f2f2" }],
      },
    ],
  });

  infoWindow = new google.maps.InfoWindow();

  // Load offices data
  loadOffices();
}

// Load offices from JSON
async function loadOffices() {
  try {
    const response = await fetch("data/offices.json");
    const data = await response.json();
    displayOffices(data.offices);
    populateOfficeList(data.offices);
  } catch (error) {
    console.error("Error loading offices:", error);
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

    // Create marker
    const marker = new google.maps.Marker({
      position: position,
      map: map,
      title: office.name,
      icon: getMarkerIcon(office.type),
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
      scaledSize: new google.maps.Size(32, 32),
    },
    Kanwil: {
      url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png",
      scaledSize: new google.maps.Size(32, 32),
    },
    Kantah: {
      url: "http://maps.google.com/mapfiles/ms/icons/green-dot.png",
      scaledSize: new google.maps.Size(32, 32),
    },
  };

  return (
    icons[type] || {
      url: "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png",
      scaledSize: new google.maps.Size(32, 32),
    }
  );
}

// Show office info in popup
function showOfficeInfo(office, marker) {
  const content = `
        <div class="info-window">
            <h3>${office.name}</h3>
            <p><strong>Alamat:</strong> ${office.address}</p>
            <p><strong>Kota:</strong> ${office.city}</p>
            <p><strong>Provinsi:</strong> ${office.province}</p>
            <p><strong>Telepon:</strong> ${office.phone}</p>
            <p><strong>Email:</strong> ${office.email}</p>
            <p><strong>Keterangan:</strong> ${office.description}</p>
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
  listContainer.innerHTML = "";

  offices.forEach((office) => {
    const officeItem = document.createElement("div");
    officeItem.className = "office-item";
    officeItem.innerHTML = `
            <h4>${office.name}</h4>
            <p><strong>Alamat:</strong> ${office.address}</p>
            <p><strong>Kota:</strong> ${office.city}, ${office.province}</p>
            <p><strong>Telepon:</strong> ${office.phone}</p>
            <p><strong>Tipe:</strong> ${office.type}</p>
        `;

    // Add click listener to center map on office
    officeItem.addEventListener("click", () => {
      const position = {
        lat: parseFloat(office.latitude),
        lng: parseFloat(office.longitude),
      };
      map.setCenter(position);
      map.setZoom(14);

      // Find and trigger marker click
      markers.forEach((marker) => {
        if (marker.getTitle() === office.name) {
          google.maps.event.trigger(marker, "click");
        }
      });
    });

    listContainer.appendChild(officeItem);
  });
}

// Search offices
function searchOffice() {
  const searchTerm = document.getElementById("searchInput").value.toLowerCase();

  fetch("data/offices.json")
    .then((response) => response.json())
    .then((data) => {
      const filtered = data.offices.filter(
        (office) =>
          office.name.toLowerCase().includes(searchTerm) ||
          office.city.toLowerCase().includes(searchTerm) ||
          office.province.toLowerCase().includes(searchTerm) ||
          office.address.toLowerCase().includes(searchTerm),
      );

      displayOffices(filtered);
      populateOfficeList(filtered);
    });
}

// Reset map
function resetMap() {
  document.getElementById("searchInput").value = "";
  loadOffices();
}

// Initialize when page loads
window.onload = function () {
  // Google Maps script is loaded async, initMap will be called by callback
};
