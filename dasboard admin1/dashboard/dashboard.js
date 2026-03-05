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
  // instansi objek Date untuk mendapatkan tanggal
  const now  = new Date();

  // Mulai menambahkan angka 0 setiap 1 digit dengan batas maksimal 2 karakter. Contoh 8 => 08 atau 22 => 22
  const pad  = (n) => String(n).padStart(2, '0');

  // Menambahkan Jam
  const jam = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
  document.getElementById('topbar-clock').textContent = jam;

  // Menambahkan Tanggal
  const tgl = `${HARI[now.getDay()]}, ${now.getDate()} ${BULAN[now.getMonth()]} ${now.getFullYear()}`;
  document.getElementById('topbar-date').textContent = tgl;
}

// Jalankan fungsi updateClock
updateClock();

// Menjalankan fungsi updateClock setiap 1000 milidetik sekali
setInterval(updateClock, 1000);


// 2. DATA KEHADIRAN
// Menyiapkan data dummy mingguan dalam bentuk objek
const dataMingguan = {
  labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
  hadir:   [230, 218, 221, 225, 210],
  lambat:  [28,  32,  36,  22,  18],
  absen:   [10,  14,  12,  8,   16],
};
// Menyiapkan data dummy harian dalam bentuk objek
const dataHariIni = {
  hadir:  221,
  lambat: 36,
  absen:  12,
};



// 3. BAR CHART – Kehadiran Mingguan
// Deklarasi sekaligus menjalankan barchart
(function initBarChart() {
  // Mendapatkan objek canvas
  const ctx = document.getElementById('barChart').getContext('2d');
  // Memasukan chart kedalam canvas tersebut
  new Chart(ctx, {
    // Tipe chart nya bar
    type: 'bar',
    data: {
      // Memasukan labels data mingguan
      labels: dataMingguan.labels,
      // Memasukan isi datasets
      datasets: [
        // Memasukan data dan customisasi barchartnya dengan tipe hadir
        {
          label: 'Hadir',
          data: dataMingguan.hadir,
          backgroundColor: '#3DB562',
          borderRadius: 5,
          barPercentage: 0.6,
          categoryPercentage: 0.75,
        },
        // Memasukan data dan customisasi barchartnya dengan tipe terlambat
        {
          label: 'Terlambat',
          data: dataMingguan.lambat,
          backgroundColor: '#C8B820',
          borderRadius: 5,
          barPercentage: 0.6,
          categoryPercentage: 0.75,
        },
        // Memasukan data dan customisasi barchartnya dengan tipe tidak hadir
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
    // Menambahkan pengaturan seperti membuat tampilan responsive dan menambahkan animationnya
    options: {
      responsive: true,
      maintainAspectRatio: true,
      animation: {
        duration: 800,
        easing: 'easeOutQuart',
      },
      // Menambahkan plugginnya seperti ukuran tulisan, jarak padding, dan font yang digunakan
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
        // Pengaturan sumbu x nya
        x: {
          grid: { display: false },
          ticks: { font: { family: 'Plus Jakarta Sans', size: 12 } },
        },
        // Pengaturan sumbu x nya
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
  // Mendapatkan objek canvas
  const ctx = document.getElementById('donutChart').getContext('2d');

  // Menghitung total baik dari hadir dan lambat dan absen
  const total  = dataHariIni.hadir + dataHariIni.lambat + dataHariIni.absen;
  // Menghitung persentase kehadiran
  const pctHadir = Math.round((dataHariIni.hadir / total) * 100);

  // Update label tengah donut secara dinamis
  // Mendapatkan element text persentasenya
  const pctEl = document.querySelector('.donut-pct');
  // Jika persentasenya sudah terisi langsung tampilkan
  if (pctEl) pctEl.textContent = `${pctHadir}%`;

  // Memasukan chart kedalam canvas tersebut
  new Chart(ctx, {
    // Tipe chartnya dougnut
    type: 'doughnut',
    data: {
      // Memasukan labels jenis kehadiran
      labels: ['Hadir', 'Terlambat', 'Tidak Hadir'],
      datasets: [
        // Memasukan data jenis kehadiran dengan beberapa customisasi
        {
          data: [dataHariIni.hadir, dataHariIni.lambat, dataHariIni.absen],
          backgroundColor: ['#3DB562', '#C8B820', '#E03B3B'],
          borderWidth: 3,
          borderColor: '#ffffff',
          hoverOffset: 8,
        },
      ],
    },
    // menambahkan pengaturan seperti memotong chartnya dan memberikan animasi
    options: {
      cutout: '68%',
      animation: {
        animateRotate: true,
        duration: 900,
        easing: 'easeOutQuart',
      },
      // Menambahkan plugginnya seperti ukuran tulisan, jarak padding, dan font yang digunakan
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#1a1a2e',
          titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: '700' },
          bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
          padding: 10,
          cornerRadius: 8,
          // Edit tulisan kehadiran
          callbacks: {
            label: (ctx) => ` ${ctx.label}: ${ctx.parsed} orang`,
          },
        },
      },
    },
  });
})();



