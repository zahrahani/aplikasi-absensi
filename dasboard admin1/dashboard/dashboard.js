// Mengkatifkan mode ECMAScript 5+ agar penulisan kode pada javascript lebih ketat
'use strict';

// 1. REAL-TIME CLOCK & DATE
// Deklarasi konstanta hari untuk menampilkan hari
const HARI   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
// Deklarasi konstanta bulan untuk menampilkan bulan
const BULAN  = [
  'Januari','Februari','Maret','April','Mei','Juni',
  'Juli','Agustus','September','Oktober','November','Desember'
];


// Fungsi untuk update jam atau DateTimePicker
function updateClock() {
  // Deklarasi
  const now  = new Date();
  const pad  = (n) => String(n).padStart(2, '0');

  // Jam
  const jam = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
  document.getElementById('topbar-clock').textContent = jam;

  // Tanggal
  const tgl = `${HARI[now.getDay()]}, ${now.getDate()} ${BULAN[now.getMonth()]} ${now.getFullYear()}`;
  document.getElementById('topbar-date').textContent = tgl;
}

updateClock();
setInterval(updateClock, 1000);


// 2. DATA KEHADIRAN

const dataMingguan = {
  labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
  hadir:   [230, 218, 221, 225, 210],
  lambat:  [28,  32,  36,  22,  18],
  absen:   [10,  14,  12,  8,   16],
};

const dataHariIni = {
  hadir:  221,
  lambat: 36,
  absen:  12,
};



// 3. BAR CHART – Kehadiran Mingguan

(function initBarChart() {
  const ctx = document.getElementById('barChart').getContext('2d');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: dataMingguan.labels,
      datasets: [
        {
          label: 'Hadir',
          data: dataMingguan.hadir,
          backgroundColor: '#3DB562',
          borderRadius: 5,
          barPercentage: 0.6,
          categoryPercentage: 0.75,
        },
        {
          label: 'Terlambat',
          data: dataMingguan.lambat,
          backgroundColor: '#C8B820',
          borderRadius: 5,
          barPercentage: 0.6,
          categoryPercentage: 0.75,
        },
        {
          label: 'Tidak Hadir',
          data: dataMingguan.absen,
          backgroundColor: '#E03B3B',
          borderRadius: 5,
          barPercentage: 0.6,
          categoryPercentage: 0.75,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      animation: {
        duration: 800,
        easing: 'easeOutQuart',
      },
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            font: { family: 'Plus Jakarta Sans', size: 12 },
            padding: 16,
            usePointStyle: true,
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
          ticks: { font: { family: 'Plus Jakarta Sans', size: 12 } },
        },
        y: {
          grid: { color: '#eef1ff' },
          ticks: { font: { family: 'Plus Jakarta Sans', size: 12 } },
          beginAtZero: true,
        },
      },
    },
  });
})();


// 4. DONUT CHART – Kehadiran Hari Ini
(function initDonutChart() {
  const ctx = document.getElementById('donutChart').getContext('2d');

  const total  = dataHariIni.hadir + dataHariIni.lambat + dataHariIni.absen;
  const pctHadir = Math.round((dataHariIni.hadir / total) * 100);

  // Update label tengah donut secara dinamis
  const pctEl = document.querySelector('.donut-pct');
  if (pctEl) pctEl.textContent = `${pctHadir}%`;

  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Hadir', 'Terlambat', 'Tidak Hadir'],
      datasets: [
        {
          data: [dataHariIni.hadir, dataHariIni.lambat, dataHariIni.absen],
          backgroundColor: ['#3DB562', '#C8B820', '#E03B3B'],
          borderWidth: 3,
          borderColor: '#ffffff',
          hoverOffset: 8,
        },
      ],
    },
    options: {
      cutout: '68%',
      animation: {
        animateRotate: true,
        duration: 900,
        easing: 'easeOutQuart',
      },
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


// 5. SIDEBAR ACTIVE STATE (klik navigasi)
document.querySelectorAll('.sidebar-item').forEach((item) => {
  item.addEventListener('click', function (e) {
    e.preventDefault();
    document.querySelectorAll('.sidebar-item').forEach((el) => el.classList.remove('active'));
    this.classList.add('active');
  });
});
