<?php 

// Buat inisial dari nama (maks 2 huruf)
$namaParts = explode(' ', trim($_SESSION['nama_lengkap']));
$inisial = strtoupper(substr($namaParts[0], 0, 1));
if (count($namaParts) > 1) {
  $inisial .= strtoupper(substr(end($namaParts), 0, 1));
}
?>

<!-- Navigasi Utama -->
<div class="sidebar-section">
  <div class="sidebar-section-label">Utama</div>
  <a href="<?= BASE_URL . 'dashboard' ?>" class="sidebar-item <?= ($active == 'dashboard')? 'active':''?>">
    <i class="bi bi-house-door-fill"></i>
    <span>Dashboard</span>
  </a>
</div>

<hr class="sidebar-divider"/>

<!-- Navigasi Manajemen -->
<div class="sidebar-section">
  <div class="sidebar-section-label">Manajemen</div>
  <a href="<?= BASE_URL . 'karyawan' ?>" class="sidebar-item <?= ($active == 'karyawan')? 'active':''?>">
    <i class="bi bi-people"></i>
    <span>Karyawan</span>
  </a>
  <a href="<?= BASE_URL . 'jadwal' ?>" class="sidebar-item <?= ($active == 'jadwalKaryawan')? 'active':''?>">
    <i class="bi bi-calendar-date"></i>
    <span>Jadwal Karyawan</span>
  </a>

  <a href="<?= BASE_URL . 'divisi-jabatan' ?>" class="sidebar-item <?= ($active == 'divisi-jabatan') ? 'active' : '' ?>">
    <i class="bi bi-diagram-3"></i>
    <span>Divisi & Jabatan</span>
  </a>


  <a href="<?= BASE_URL . 'validasi-izin' ?>" class="sidebar-item <?= ($active == 'validasiIzin')? 'active':''?>">
    <i class="bi bi-check2-circle"></i>
    <span>Validasi Izin</span>
    <span class="sidebar-badge ms-auto " id="badge-count">0</span>
  </a>
  <a href="<?= BASE_URL . 'rekap-laporan' ?>" class="sidebar-item <?= ($active == 'rekapLaporan')? 'active':''?>">
    <i class="bi bi-bar-chart-line"></i>
    <span>Rekap Laporan</span>
  </a>

  
</div>

<hr class="sidebar-divider"/>

<!-- Logout -->
<div class="sidebar-section">
  <div class="sidebar-section-label">Akun</div>
  <a href="<?= BASE_URL . 'profile' ?>" class="sidebar-item <?= ($active == 'profile')? 'active':''?>">
    <i class="bi bi-person-circle"></i>
    <span>Profil</span>
  </a>
  <a href="<?= BASE_URL . 'logout' ?>" class="sidebar-item <?= ($active == 'logout')? 'active':''?>">
    <i class="bi bi-arrow-bar-left"></i>
    <span>Logout</span>
  </a>
</div>

<!-- User Profile (bawah sidebar) -->
<div class="sidebar-user-wrap mt-auto">
  <div class="sidebar-user-avatar">
    <?php if (!empty($_SESSION['foto_profil'])): ?>
      <img src="<?= BASE_URL . 'uploads/profile/' . \e($_SESSION['foto_profil']) ?>"
      alt="Foto Profil"/>
    <?php else: ?>
      <?= \e($inisial) ?>
    <?php endif; ?>
  </div>
  <div class="user-info">
    <div class="user-name"><?= $user['username'] ?></div>
    <div class="user-role"><?= $user['nama_lengkap'] ?></div>
    <div class="user-company"><?= APP_NAME ?></div>
  </div>
</div>

<script>

  async function updateBadgeCountSidebar() {

    try {

      const res = await fetch(document
        .querySelector('meta[name="base-url"]')
        .getAttribute('content') + 'api/validasi-izin', {
          credentials: 'include'
        });

      if (!res.ok) {
        throw new Error(`HTTP ${res.status}`);
      }

      const json = await res.json();

      if (!json.data) {
        throw new Error(
          json.errors_messages ||
          'Gagal memuat badge'
          );
      }

        // Ambil semua pengajuan
      const pengajuan = json.data.pengajuan || [];

        // Hitung status pending
      const pending = pengajuan.filter(
        item => item.status === 'pending'
        ).length;

        // Badge element
      const badgeValidasiIzin = document.getElementById('badge-count');

        // Update badge
      if (badgeValidasiIzin) {

        badgeValidasiIzin.textContent = pending;

            // Optional: sembunyikan jika 0
        if (pending <= 0) {
          badgeValidasiIzin.style.display = 'none';
        } else {
          badgeValidasiIzin.style.display = 'inline-flex';
        }
      }

    } catch (err) {

      console.error(
        '[updateBadgeCount]',
        err.message
        );
    }
  }

// ─────────────────────────────────────────────
// AUTO LOAD
// ─────────────────────────────────────────────
  document.addEventListener('DOMContentLoaded', () => {
    updateBadgeCountSidebar();
  });
</script>