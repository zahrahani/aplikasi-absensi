/* ═══════════════════════════════════════════
   dashboard.js – CV. NAFIHAKA Creative
   Clock · Bar Chart · Donut Chart · Sidebar
════════════════════════════════════════════ */

'use strict';

// ─────────────────────────────────────────────
// 1. DATA
// ─────────────────────────────────────────────
const dataMingguan = {
  labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
  hadir:  [230, 218, 221, 225, 210],
  lambat: [28,  32,  36,  22,  18],
  absen:  [10,  14,  12,  8,   16],
};

const dataHariIni = {
  hadir:  221,
  lambat: 36,
  absen:  12,
};

// ─────────────────────────────────────────────
// 2. CLOCK & DATE
// ─────────────────────────────────────────────
const HARI  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
const BULAN = ['Januari','Februari','Maret','April','Mei','Juni',
               'Juli','Agustus','September','Oktober','November','Desember'];

function updateClock() {
  const now = new Date();
  const pad = (n) => String(n).padStart(2, '0');

  const clockEl = document.getElementById('topbar-clock');
  const dateEl  = document.getElementById('topbar-date');

  if (clockEl) clockEl.textContent =
    `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;

  if (dateEl) dateEl.textContent =
    `${HARI[now.getDay()]}, ${now.getDate()} ${BULAN[now.getMonth()]} ${now.getFullYear()}`;
}

updateClock();
setInterval(updateClock, 1000);

// ─────────────────────────────────────────────
// 3. UPDATE STAT CARDS
// ─────────────────────────────────────────────
function updateStatCards() {
  const set = (id, val) => {
    const el = document.getElementById(id);
    if (el) el.textContent = val;
  };
  set('stat-hadir', dataHariIni.hadir);
  set('stat-lambat', dataHariIni.lambat);
  set('stat-absen', dataHariIni.absen);
  // izin dari session badge jika ada, fallback 10
  const badge = window.AppBridge ? window.AppBridge.getBadge() : 10;
  set('stat-izin', badge);
}

// ─────────────────────────────────────────────
// 4. BAR CHART – Kehadiran Mingguan
// ─────────────────────────────────────────────
(function initBarChart() {
  const canvas = document.getElementById('barChart');
  if (!canvas) return;

  new Chart(canvas.getContext('2d'), {
    type: 'bar',
    data: {
      labels: dataMingguan.labels,
      datasets: [
        {
          label: 'Hadir',
          data: dataMingguan.hadir,
          backgroundColor: '#3DB562',
          borderRadius: 5,
          barPercentage: 0.55,
          categoryPercentage: 0.8,
        },
        {
          label: 'Terlambat',
          data: dataMingguan.lambat,
          backgroundColor: '#C8B820',
          borderRadius: 5,
          barPercentage: 0.55,
          categoryPercentage: 0.8,
        },
        {
          label: 'Tidak Hadir',
          data: dataMingguan.absen,
          backgroundColor: '#E03B3B',
          borderRadius: 5,
          barPercentage: 0.55,
          categoryPercentage: 0.8,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      animation: { duration: 800, easing: 'easeOutQuart' },
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            font: { family: 'Plus Jakarta Sans', size: 12 },
            padding: 18,
            usePointStyle: true,
            pointStyle: 'circle',
            pointStyleWidth: 10,
          },
        },
        tooltip: {
          backgroundColor: '#1a1a2e',
          titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: '700' },
          bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
          padding: 10,
          cornerRadius: 8,
        },
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: { font: { family: 'Plus Jakarta Sans', size: 12 }, color: '#6b7280' },
          border: { display: false },
        },
        y: {
          grid: { color: '#eef1ff' },
          ticks: { font: { family: 'Plus Jakarta Sans', size: 12 }, color: '#6b7280' },
          border: { display: false },
          beginAtZero: true,
          max: 250,
        },
      },
    },
  });
})();

// ─────────────────────────────────────────────
// 5. DONUT CHART – Kehadiran Hari Ini
// ─────────────────────────────────────────────
(function initDonutChart() {
  const canvas = document.getElementById('donutChart');
  if (!canvas) return;

  const total     = dataHariIni.hadir + dataHariIni.lambat + dataHariIni.absen;
  const pctHadir  = Math.round((dataHariIni.hadir / total) * 100);

  // Update label tengah
  const pctEl = document.getElementById('donut-pct');
  if (pctEl) pctEl.textContent = `${pctHadir}%`;

  // Update legend counts
  const setCount = (id, val) => {
    const el = document.getElementById(id);
    if (el) el.textContent = `${val} Orang`;
  };
  setCount('legend-hadir',  dataHariIni.hadir);
  setCount('legend-lambat', dataHariIni.lambat);
  setCount('legend-absen',  dataHariIni.absen);

  new Chart(canvas.getContext('2d'), {
    type: 'doughnut',
    data: {
      labels: ['Hadir', 'Terlambat', 'Tidak Hadir'],
      datasets: [{
        data: [dataHariIni.hadir, dataHariIni.lambat, dataHariIni.absen],
        backgroundColor: ['#3DB562', '#C8B820', '#E03B3B'],
        borderWidth: 4,
        borderColor: '#ffffff',
        hoverOffset: 10,
      }],
    },
    options: {
      cutout: '70%',
      animation: { animateRotate: true, duration: 900, easing: 'easeOutQuart' },
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#1a1a2e',
          titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: '700' },
          bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
          padding: 10,
          cornerRadius: 8,
          callbacks: {
            label: (ctx) => ` ${ctx.label}: ${ctx.parsed} orang`,
          },
        },
      },
    },
  });
})();

// ─────────────────────────────────────────────
// 6. INIT
// ─────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', updateStatCards);
