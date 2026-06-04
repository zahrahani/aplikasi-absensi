'use strict';

// 1. KONFIGURASI API
const WEB_URI      = document.querySelector('meta[name="base-url"]').getAttribute('content');
const API_URI      = WEB_URI + 'api';


// Fetch data dari API─
async function fetchDashboardChart() {
  try {
    const response = await fetch(`${API_URI}/admin/dashboard/chart`, {
      method     : 'POST',
      headers    : {'Content-Type': 'application/json'},
      credentials: 'include',
    });

    if (!response.ok) throw new Error('Gagal fetch data chart');

    const json = await response.json();
    const data = json['data'];

    // Update stat box hari ini
    document.getElementById('stat-hadir').textContent  = data['hari_ini']['hadir'];
    document.getElementById('stat-lambat').textContent = data['hari_ini']['lambat'];
    document.getElementById('stat-absen').textContent  = data['hari_ini']['absen'];

    // Update izin pending─
    document.getElementById('stat-izin').textContent   = data['izin_pending'];

    // Update legend donut─
    const legendItems = document.querySelectorAll('.legend-count');
    if (legendItems.length >= 3) {
      legendItems[0].textContent = `${data['hari_ini']['hadir']} Orang`;
      legendItems[1].textContent = `${data['hari_ini']['lambat']} Orang`;
      legendItems[2].textContent = `${data['hari_ini']['absen']} Orang`;
    }

    // Init chart
    initBarChart(data['mingguan']);
    initDonutChart(data['hari_ini']);

  } catch (err) {
    console.error('Error fetch chart:', err);
  }
}

// Bar Chart─
function initBarChart(dataMingguan) {
  const ctx = document.getElementById('barChart').getContext('2d');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels  : dataMingguan.labels,
      datasets: [
        {
          label             : 'Hadir',
          data              : dataMingguan.hadir,
          backgroundColor   : '#3DB562',
          borderRadius      : 5,
          barPercentage     : 0.6,
          categoryPercentage: 0.75,
        },
        {
          label             : 'Terlambat',
          data              : dataMingguan.lambat,
          backgroundColor   : '#C8B820',
          borderRadius      : 5,
          barPercentage     : 0.6,
          categoryPercentage: 0.75,
        },
        {
          label             : 'Tidak Hadir',
          data              : dataMingguan.absen,
          backgroundColor   : '#E03B3B',
          borderRadius      : 5,
          barPercentage     : 0.6,
          categoryPercentage: 0.75,
        },
      ],
    },
    options: {
      responsive         : true,
      maintainAspectRatio: true,
      animation: {
        duration: 800,
        easing  : 'easeOutQuart',
      },
      plugins: {
        legend: {
          position: 'bottom',
          labels  : {
            font           : {family: 'Plus Jakarta Sans', size: 12},
            padding        : 16,
            usePointStyle  : true,
            pointStyleWidth: 10,
          },
        },
        tooltip: {
          backgroundColor: '#1a1a2e',
          titleFont      : {family: 'Plus Jakarta Sans', size: 12, weight: '700'},
          bodyFont       : {family: 'Plus Jakarta Sans', size: 12},
          padding        : 10,
          cornerRadius   : 8,
        },
      },
      scales: {
        x: {
          grid : {display: false},
          ticks: {font: {family: 'Plus Jakarta Sans', size: 12}},
        },
        y: {
          grid       : {color: '#eef1ff'},
          ticks      : {font: {family: 'Plus Jakarta Sans', size: 12}},
          beginAtZero: true,
        },
      },
    },
  });
}

// Donut Chart─
function initDonutChart(dataHariIni) {
  const ctx = document.getElementById('donutChart').getContext('2d');

  const total    = dataHariIni.hadir + dataHariIni.lambat + dataHariIni.absen;
  const pctHadir = total > 0 ? Math.round((dataHariIni.hadir / total) * 100) : 0;

  // Update persentase di tengah donut─
  const pctEl = document.querySelector('.donut-pct');
  if (pctEl) pctEl.textContent = `${pctHadir}%`;

  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels  : ['Hadir', 'Terlambat', 'Tidak Hadir'],
      datasets: [
        {
          data           : [dataHariIni.hadir, dataHariIni.lambat, dataHariIni.absen],
          backgroundColor: ['#3DB562', '#C8B820', '#E03B3B'],
          borderWidth    : 3,
          borderColor    : '#ffffff',
          hoverOffset    : 8,
        },
      ],
    },
    options: {
      cutout   : '68%',
      animation: {
        animateRotate: true,
        duration     : 900,
        easing       : 'easeOutQuart',
      },
      plugins: {
        legend : {display: false},
        tooltip: {
          backgroundColor: '#1a1a2e',
          titleFont      : {family: 'Plus Jakarta Sans', size: 12, weight: '700'},
          bodyFont       : {family: 'Plus Jakarta Sans', size: 12},
          padding        : 10,
          cornerRadius   : 8,
          callbacks      : {
            label: (ctx) => ` ${ctx.label}: ${ctx.parsed} orang`,
          },
        },
      },
    },
  });
}

// Jalankan saat halaman siap
document.addEventListener('DOMContentLoaded', () => {
  fetchDashboardChart();
});