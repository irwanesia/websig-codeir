// stats.js - Script untuk mengelola statistik di semua halaman
(function () {
  "use strict";

  // Konfigurasi
  const STATS_CONFIG = {
    checkInterval: 500, // Cek setiap 500ms
    maxAttempts: 20, // Maksimal 10 detik (20 * 500ms)
    debug: true, // Aktifkan logging
  };

  // Inisialisasi
  function initStats() {
    if (STATS_CONFIG.debug) console.log("Stats.js initialized");

    // Coba update statistik segera
    updateAllStats();

    // Polling untuk menunggu data
    let attempts = 0;
    const intervalId = setInterval(function () {
      attempts++;

      if (STATS_CONFIG.debug) console.log(`Stats check attempt ${attempts}`);

      // Update statistik
      const updated = updateAllStats();

      // Berhenti jika data sudah ada atau mencapai batas
      if (updated || attempts >= STATS_CONFIG.maxAttempts) {
        clearInterval(intervalId);
        if (STATS_CONFIG.debug) {
          console.log(`Stats polling stopped after ${attempts} attempts`);
        }
      }
    }, STATS_CONFIG.checkInterval);

    // Listen untuk custom event
    document.addEventListener("officesDataLoaded", function (e) {
      if (STATS_CONFIG.debug) console.log("Custom event received:", e.detail);
      updateAllStats();
    });
  }

  // Update semua statistik di halaman
  function updateAllStats() {
    // Dapatkan data dari global variable
    const offices = window.allOffices || [];
    const hasData = offices && offices.length > 0;

    if (STATS_CONFIG.debug) {
      console.log("Updating stats with", offices.length, "offices");
    }

    // Update statistik di semua elemen
    let updated = false;

    // 1. About page stats
    updated = updateAboutStats(offices) || updated;

    // 2. Admin page stats
    updated = updateAdminStats(offices) || updated;

    // 3. Footer stats
    updated = updateFooterStats(offices) || updated;

    return updated;
  }

  // Update statistik di halaman about
  function updateAboutStats(offices) {
    let updated = false;

    // Total offices
    const totalEl = document.getElementById("aboutTotalOffices");
    if (totalEl) {
      totalEl.textContent = offices.length;
      updated = true;
    }

    // Total provinces
    const provincesEl = document.getElementById("aboutTotalProvinces");
    if (provincesEl && offices.length > 0) {
      const provinces = [
        ...new Set(offices.map((o) => o.province).filter((p) => p)),
      ];
      provincesEl.textContent = provinces.length;
      updated = true;
    }

    // Total types
    const typesEl = document.getElementById("aboutTotalTypes");
    if (typesEl && offices.length > 0) {
      const types = [...new Set(offices.map((o) => o.type).filter((t) => t))];
      typesEl.textContent = types.length;
      updated = true;
    }

    return updated;
  }

  // Update statistik di halaman admin
  function updateAdminStats(offices) {
    let updated = false;

    // Admin total offices
    const adminTotalEl = document.getElementById("adminTotalOffices");
    if (adminTotalEl) {
      adminTotalEl.textContent = offices.length;
      updated = true;
    }

    // Admin total provinces
    const adminProvincesEl = document.getElementById("adminTotalProvinces");
    if (adminProvincesEl && offices.length > 0) {
      const provinces = [
        ...new Set(offices.map((o) => o.province).filter((p) => p)),
      ];
      adminProvincesEl.textContent = provinces.length;
      updated = true;
    }

    // Admin last update
    const adminUpdateEl = document.getElementById("adminLastUpdate");
    if (adminUpdateEl) {
      const now = new Date();
      adminUpdateEl.textContent =
        now.toLocaleDateString("id-ID") + " " + now.toLocaleTimeString("id-ID");
      updated = true;
    }

    return updated;
  }

  // Update statistik di footer (dari semua halaman)
  function updateFooterStats(offices) {
    let updated = false;

    // Footer total offices
    const footerTotal = document.getElementById("footerTotalOffices");
    if (footerTotal) {
      footerTotal.textContent = offices.length;
      updated = true;
    }

    // Footer total provinces
    const footerProvinces = document.getElementById("footerTotalProvinces");
    if (footerProvinces && offices.length > 0) {
      const provinces = [
        ...new Set(offices.map((o) => o.province).filter((p) => p)),
      ];
      footerProvinces.textContent = provinces.length;
      updated = true;
    }

    return updated;
  }

  // Load data dari server jika belum ada
  async function loadStatsData() {
    if (window.allOffices && window.allOffices.length > 0) {
      return window.allOffices;
    }

    try {
      const response = await fetch("/data/offices.json");
      if (!response.ok) throw new Error("Failed to load data");

      const data = await response.json();
      window.allOffices = data.offices || [];

      if (STATS_CONFIG.debug) {
        console.log("Stats data loaded:", window.allOffices.length, "offices");
      }

      // Trigger update
      updateAllStats();

      return window.allOffices;
    } catch (error) {
      console.error("Error loading stats data:", error);
      return [];
    }
  }

  // Auto-load data jika diperlukan
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", function () {
      initStats();
      loadStatsData(); // Coba load data sendiri
    });
  } else {
    initStats();
    loadStatsData();
  }
})();
