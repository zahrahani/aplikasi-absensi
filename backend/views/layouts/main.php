<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= APP_NAME ?> – Dashboard Admin</title>

  <!-- Bootstrap 5 CSS -->
  <link rel="stylesheet" href="<?= pathCss('bootstrap') ?>"/>
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"/>
  <!-- ALL CSS -->
  <link rel="stylesheet" href="<?= pathCss('all') ?>"/>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>

  <meta name="base-url" content="<?= BASE_URL ?>">

</head>
<body>
  <!-- Token csrf -->
  <input type="hidden" value="<?= $_SESSION['csrf_token'] ?>" id="token_csrf">


  <!-- Modals -->
  <?= includesWithUri('/validasi-izin','layouts/modals/modalValidasiIzin') ?>
  <?= includesWithUri('/rekap-laporan','layouts/modals/modalRekapLaporan') ?>
  <?= includesWithUri('/karyawan','layouts/modals/modalEditKaryawan') ?>
  <?= includesWithUri('/karyawan','layouts/modals/modalKonfirmasiHapusKaryawan') ?>
  <?= includesWithUri('/karyawan','layouts/modals/modalKonfirmasiLogoutKaryawan') ?>
  <?= includesWithUri('/karyawan','layouts/modals/modalDetailKaryawan') ?>
  <?= includesWithUri('/jadwal','layouts/modals/modalAturShift') ?>
  <?= includesWithUri('/divisi-jabatan','layouts/modals/modalKonfirmasiHapusJabatan') ?>
  <?= includesWithUri('/divisi-jabatan','layouts/modals/modalKonfirmasiHapusDivisi') ?>



  <!-- 
       TOPBAR
  -->
  <header id="topbar" class="topbar d-flex align-items-center justify-content-between px-4">
    <div class="topbar-brand"><?= APP_NAME ?></div>
    <div class="d-flex align-items-center gap-3">
      <span id="topbar-date" class="topbar-date"></span>
      <div id="topbar-clock" class="topbar-clock">--:--:--</div>
    </div>
  </header>

  <!-- LAYOUT WRAPPER -->
  <div class="layout-wrapper d-flex">

    <!-- SIDEBAR -->
    <aside id="sidebar" class="sidebar d-flex flex-column">
      <?= includes('layouts/sidebar', ['active' => $active, 'user' => \getCurrentUser()]) ?>      
    </aside>
    <!-- /sidebar -->

    <!-- MAIN CONTENT -->
    <main class="main-content flex-grow-1">
      <?= $content ?>
    </main>

    <!-- /main-content -->

  </div>
  <!-- /layout-wrapper -->

<script>
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

</script>


</body>
</html>
