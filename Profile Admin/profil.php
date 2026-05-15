<?php
/**
 * profil.php – CV. NAFIHAKA Creative
 * View  : Halaman Profil Admin
 * Layout: dipanggil sebagai $content di main template
 *
 * Variabel yang diharapkan dari controller:
 *   $user  – array data admin yang sedang login
 *            keys: nama, email, hp, tanggal_lahir, tanggal_lahir_raw,
 *                  alamat, foto, nik, jabatan, divisi, mulai_kerja, last_login
 *   $active – string untuk active state sidebar ('profil')
 */

// Nilai default (fallback jika $user belum diset)
$user = $user ?? [];
$default = [
  'nama'            => 'Akari Suida',
  'email'           => 'Akari.Suida@nafihaka.com',
  'hp'              => '0814-5678-9012',
  'tanggal_lahir'   => '12-Agustus-1998',
  'tanggal_lahir_raw'=> '1998-08-12',
  'alamat'          => 'Jl. Slamet Riyadi No. 3 Yogyakarta',
  'foto'            => 'Default_443.img',
  'nik'             => 'ADM-001',
  'jabatan'         => 'Administrator',
  'divisi'          => 'Human Resource',
  'mulai_kerja'     => '1 Juni 2025',
  'last_login'      => 'Rabu, 25 Februari 2026 – 07:28',
];
$user = array_merge($default, $user);

// Buat inisial dari nama (maks 2 huruf)
$namaParts = explode(' ', trim($user['nama']));
$inisial = strtoupper(substr($namaParts[0], 0, 1));
if (count($namaParts) > 1) {
  $inisial .= strtoupper(substr(end($namaParts), 0, 1));
}
?>

<!-- ════════════════════════════════════════
     CSS SPESIFIK HALAMAN PROFIL
════════════════════════════════════════ -->
<link rel="stylesheet" href="<?= pathCss('profil') ?>"/>

<!-- ════════════════════════════════════════
     HEADER HALAMAN
════════════════════════════════════════ -->
<div class="profil-page-header d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">
  <div>
    <h4 class="page-title">Profil Admin</h4>
    <p class="page-subtitle">Kelola Informasi dan Preferensi anda</p>
  </div>
  <button class="btn-simpan-profil" id="btnSimpanProfil">
    <i class="bi bi-floppy-fill"></i>&nbsp; Simpan Perubahan
  </button>
</div>

<!-- ════════════════════════════════════════
     ALERT NOTIFIKASI
════════════════════════════════════════ -->
<div id="profilAlertSuccess" class="profil-alert profil-alert-success d-none" role="alert">
  <i class="bi bi-check-circle-fill"></i>
  <span id="profilAlertMsg">Perubahan berhasil disimpan.</span>
</div>
<div id="profilAlertError" class="profil-alert profil-alert-error d-none" role="alert">
  <i class="bi bi-exclamation-triangle-fill"></i>
  <span id="profilAlertErrorMsg">Terjadi kesalahan, coba lagi.</span>
</div>

<!-- ════════════════════════════════════════
     KARTU IDENTITAS ADMIN
════════════════════════════════════════ -->
<div class="profil-identity-card mb-4">

  <!-- Kiri: Avatar + Ubah Foto -->
  <div class="identity-avatar-wrap">
    <div class="identity-avatar" id="avatarDisplay">
      <?= htmlspecialchars($inisial) ?>
    </div>
    <label class="ubah-foto-label" for="inputFotoProfil" title="Ubah foto profil">
      <i class="bi bi-camera-fill"></i> Ubah Foto
    </label>
    <input type="file" id="inputFotoProfil" name="foto_profil" accept="image/*" class="d-none"/>
  </div>

  <!-- Kanan: Nama, Role, NIK -->
  <div class="identity-info">
    <div class="identity-name">
      <?= htmlspecialchars($user['nama']) ?>
      <button class="btn-edit-inline" id="btnEditNamaInline" title="Edit nama">
        <i class="bi bi-pencil"></i>
      </button>
    </div>
    <div class="identity-meta">
      <span class="identity-role">
        <i class="bi bi-shield-fill-check"></i>
        <?= htmlspecialchars($user['jabatan']) ?>
      </span>
      <span class="identity-divider">◆</span>
      <span class="identity-divisi"><?= htmlspecialchars($user['divisi']) ?></span>
    </div>
    <div class="identity-nik">NIK. <?= htmlspecialchars($user['nik']) ?></div>
  </div>

</div>

<!-- ════════════════════════════════════════
     SECTION LABEL
════════════════════════════════════════ -->
<div class="profil-section-label mb-3">Informasi Pribadi</div>

<!-- ════════════════════════════════════════
     GRID DUA KOLOM
════════════════════════════════════════ -->
<div class="row g-4">

  <!-- ──────────────────────────────────
       KOLOM KIRI — Data Diri
  ─────────────────────────────────── -->
  <div class="col-12 col-lg-6">
    <div class="profil-card h-100">
      <div class="profil-card-heading">Data Diri</div>

      <?php
      // Definisi field data diri agar mudah di-loop
      $fieldsDiri = [
        [
          'key'         => 'nama',
          'label'       => 'Nama Lengkap',
          'value'       => $user['nama'],
          'input_type'  => 'text',
          'placeholder' => 'Masukkan nama lengkap',
        ],
        [
          'key'         => 'email',
          'label'       => 'Email',
          'value'       => $user['email'],
          'input_type'  => 'email',
          'placeholder' => 'Masukkan email',
        ],
        [
          'key'         => 'hp',
          'label'       => 'Nomor HP',
          'value'       => $user['hp'],
          'input_type'  => 'text',
          'placeholder' => 'Contoh: 0812-3456-7890',
        ],
        [
          'key'         => 'lahir',
          'label'       => 'Tanggal Lahir',
          'value'       => $user['tanggal_lahir'],
          'input_type'  => 'date',
          'input_value' => $user['tanggal_lahir_raw'],
          'placeholder' => '',
        ],
        [
          'key'         => 'alamat',
          'label'       => 'Alamat',
          'value'       => $user['alamat'],
          'input_type'  => 'textarea',
          'placeholder' => 'Masukkan alamat lengkap',
        ],
        [
          'key'         => 'foto',
          'label'       => 'Foto Profil',
          'value'       => $user['foto'],
          'input_type'  => 'file_trigger', // Klik tombol Ubah → buka input file
          'placeholder' => '',
        ],
      ];
      ?>

      <?php foreach ($fieldsDiri as $idx => $field): ?>
        <div class="profil-field<?= $idx < count($fieldsDiri) - 1 ? ' has-divider' : '' ?>"
             id="field-<?= $field['key'] ?>">

          <div class="profil-field-label"><?= $field['label'] ?></div>

          <!-- Tampilan nilai (read mode) -->
          <div class="profil-field-row" id="row-<?= $field['key'] ?>">
            <div class="profil-field-value" id="val-<?= $field['key'] ?>">
              <?= htmlspecialchars($field['value']) ?>
            </div>
            <button class="btn-ubah" data-field="<?= $field['key'] ?>">Ubah</button>
          </div>

          <!-- Form edit (hidden default) -->
          <?php if ($field['input_type'] !== 'file_trigger'): ?>
          <div class="profil-field-edit d-none" id="edit-<?= $field['key'] ?>">
            <?php if ($field['input_type'] === 'textarea'): ?>
              <textarea
                class="profil-input"
                id="input-<?= $field['key'] ?>"
                rows="2"
                placeholder="<?= $field['placeholder'] ?>"><?= htmlspecialchars($field['value']) ?></textarea>
            <?php else: ?>
              <input
                type="<?= $field['input_type'] ?>"
                class="profil-input"
                id="input-<?= $field['key'] ?>"
                value="<?= htmlspecialchars($field['input_value'] ?? $field['value']) ?>"
                placeholder="<?= $field['placeholder'] ?>"/>
            <?php endif; ?>
            <div class="profil-edit-actions">
              <button class="btn-batal-field" data-field="<?= $field['key'] ?>">Batal</button>
              <button class="btn-simpan-field" data-field="<?= $field['key'] ?>">Simpan</button>
            </div>
          </div>
          <?php endif; ?>

        </div>
      <?php endforeach; ?>

    </div>
  </div>

  <!-- ──────────────────────────────────
       KOLOM KANAN — Kepegawaian + Keamanan
  ─────────────────────────────────── -->
  <div class="col-12 col-lg-6 d-flex flex-column gap-4">

    <!-- ── Data Kepegawaian (read-only) ── -->
    <div class="profil-card">
      <div class="profil-card-heading">Data Kepegawaian</div>

      <div class="kepegawaian-grid">
        <div class="kepegawaian-item">
          <div class="kepegawaian-label">NIK</div>
          <div class="kepegawaian-value"><?= htmlspecialchars($user['nik']) ?></div>
        </div>
        <div class="kepegawaian-item">
          <div class="kepegawaian-label">Jabatan</div>
          <div class="kepegawaian-value"><?= htmlspecialchars($user['jabatan']) ?></div>
        </div>
        <div class="kepegawaian-item">
          <div class="kepegawaian-label">Divisi</div>
          <div class="kepegawaian-value"><?= htmlspecialchars($user['divisi']) ?></div>
        </div>
        <div class="kepegawaian-item">
          <div class="kepegawaian-label">Tanggal Mulai Kerja</div>
          <div class="kepegawaian-value"><?= htmlspecialchars($user['mulai_kerja']) ?></div>
        </div>
      </div>
    </div>

    <!-- ── Keamanan Akun ── -->
    <div class="profil-card">
      <div class="profil-card-heading">Keamanan Akun</div>

      <!-- Ganti Password -->
      <div class="profil-field has-divider" id="field-password">
        <div class="profil-field-label">Password</div>
        <div class="profil-field-row" id="row-password">
          <div class="profil-field-value password-dots">••••••••</div>
          <button class="btn-ubah" data-field="password">Ubah</button>
        </div>
        <div class="profil-field-edit d-none" id="edit-password">
          <input type="password" class="profil-input mb-2" id="input-pw-lama"
            placeholder="Password lama"/>
          <input type="password" class="profil-input mb-2" id="input-pw-baru"
            placeholder="Password baru (min. 8 karakter)"/>
          <input type="password" class="profil-input"      id="input-pw-konfirm"
            placeholder="Konfirmasi password baru"/>
          <div class="profil-edit-actions">
            <button class="btn-batal-field" data-field="password">Batal</button>
            <button class="btn-simpan-field" data-field="password">Simpan</button>
          </div>
        </div>
      </div>

      <!-- Login Terakhir -->
      <div class="last-login-row">
        <div class="last-login-icon">
          <i class="bi bi-shield-check"></i>
        </div>
        <div>
          <div class="last-login-label">Login Terakhir</div>
          <div class="last-login-value">
            <?= htmlspecialchars($user['last_login']) ?>
          </div>
        </div>
      </div>

    </div><!-- /keamanan -->

  </div><!-- /col-kanan -->

</div><!-- /row -->

<!-- ════════════════════════════════════════
     JS SPESIFIK HALAMAN PROFIL
════════════════════════════════════════ -->
<script src="<?= pathJs('profil') ?>"></script>
