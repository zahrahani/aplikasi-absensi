<?php

// Nilai default (fallback jika $user belum diset)

// Buat inisial dari nama (maks 2 huruf)
$namaParts = explode(' ', trim($akun_admin['nama_lengkap']));
$inisial = strtoupper(substr($namaParts[0], 0, 1));
if (count($namaParts) > 1) {
  $inisial .= strtoupper(substr(end($namaParts), 0, 1));
}

?>

<!-- ════════════════════════════════════════
     CSS SPESIFIK HALAMAN PROFIL
════════════════════════════════════════ -->
<link rel="stylesheet" href="<?= pathCss('profile') ?>"/>

<!-- ════════════════════════════════════════
     HEADER HALAMAN
════════════════════════════════════════ -->
<div class="profil-page-header d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">
  <div>
    <h4 class="page-title">Profil Admin</h4>
    <p class="page-subtitle">Kelola Informasi dan Preferensi anda</p>
  </div>
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
     <?php if (!empty($akun_admin['foto_profil'])): ?>
      <img src="<?= BASE_URL . 'uploads/profile/' . \e($akun_admin['foto_profil']) ?>"
      alt="Foto Profil"/>
    <?php else: ?>
      <?= \e($inisial) ?>
    <?php endif; ?>
  </div>

  <!-- Form foto dengan enctype yang benar -->
  <form action="<?= BASE_URL . 'profile/update-foto-profile' ?>"
    method="post"
    enctype="multipart/form-data"
    id="formFotoProfil">
    <input type="file" id="inputFotoProfil" name="foto_profil"
    accept="image/*" class="d-none"/>

    <!-- Tombol pilih foto -->
    <label class="ubah-foto-label" for="inputFotoProfil" title="Ubah foto profil">
      <i class="bi bi-camera-fill"></i> Ubah Foto
    </label>

    <!-- Tombol simpan foto — hanya muncul setelah foto dipilih -->
    <button type="submit" class="btn-simpan-foto d-none" id="btnSimpanFoto">
      <i class="bi bi-check-lg"></i> Simpan
    </button>

    <!-- Tombol batal — hanya muncul setelah foto dipilih -->
    <button type="button" class="btn-batal-foto d-none" id="btnBatalFoto">
      Batal
    </button>
  </form>
</div>

<!-- Kanan: Nama, Role, NIK -->
<div class="identity-info">
  <div class="identity-name">
    <?= \e($akun_admin['nama_lengkap']) ?>
  </div>
  <div class="identity-nik">USER_ID. <?= \e($akun_admin['user_id']) ?></div>
</div>

  <?= \messageError('foto_profil'); ?>
</div>

<?= \headerError('general') ?>
<?= \displayFlashMessage() ?>


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
          'value'       =>  $akun_admin['nama_lengkap'],
          'input_type'  => 'text',
          'name'        => 'nama_lengkap',
          'placeholder' => 'Masukkan nama lengkap',
        ],
        [
          'key'         => 'email',
          'label'       => 'Email',
          'value'       => $akun_admin['email'],
          'input_type'  => 'email',
          'placeholder' => 'Masukkan email',
          'name'        => 'email'
        ],
        [
          'key'         => 'username',
          'label'       => 'Username',
          'value'       => $akun_admin['username'],
          'input_type'  => 'text',
          'placeholder' => 'Contoh: admin',
          'name'        => 'username'
        ]
      ];
      ?>
      <form action="<?= BASE_URL . 'profile' ?>" method="post">
        <?php foreach ($fieldsDiri as $idx => $field): ?>
          <div class="profil-field<?= $idx < count($fieldsDiri) - 1 ? ' has-divider' : '' ?>"
           id="field-<?= $field['key'] ?>">

           <div class="profil-field-label"><?= $field['label'] ?></div>

           <!-- Tampilan nilai (read mode) -->
           <div class="profil-field-row" id="row-<?= $field['key'] ?>">
            <div class="profil-field-value" id="val-<?= $field['key'] ?>">
              <?= \e($field['value']) ?>
              <?= \messageError($field['name']); ?>
            </div>
            <button class="btn-ubah" type="button" data-field="<?= $field['key'] ?>">Ubah</button>
          </div>

          <!-- Form edit (hidden default) -->
          <?php if ($field['input_type'] !== 'file_trigger'): ?>
            <div class="profil-field-edit d-none" id="edit-<?= $field['key'] ?>">
              <?php if ($field['input_type'] === 'textarea'): ?>
                <textarea
                class="profil-input"
                id="input-<?= $field['key'] ?>"
                rows="2"
                name=<?= \e($field['name']) ?>
                placeholder="<?= $field['placeholder'] ?>"><?= \e($field['value']) ?></textarea>
              <?php else: ?>
                <input
                type="<?= $field['input_type'] ?>"
                class="profil-input"
                name=<?= \e($field['name']) ?>
                id="input-<?= $field['key'] ?>"
                value="<?= \e($field['value']) ?>"
                placeholder="<?= $field['placeholder'] ?>
                "/>
              <?php endif; ?>
              <div class="profil-edit-actions">
                <button class="btn-batal-field" type="button" data-field="<?= $field['key'] ?>">Batal</button>
                <button class="btn-simpan-field" type="submit" data-field="<?= $field['key'] ?>">Simpan</button>
              </div>
            </div>
          <?php endif; ?>

        </div>
      <?php endforeach; ?>
    </form>

  </div>
</div>

  <!-- ──────────────────────────────────
       KOLOM KANAN — Kepegawaian + Keamanan
  ─────────────────────────────────── -->
  <div class="col-12 col-lg-6 d-flex flex-column gap-4">


    <!-- ── Keamanan Akun ── -->
    <div class="profil-card">
      <div class="profil-card-heading">Keamanan Akun</div>

      <form action="<?= BASE_URL . 'profile/change-password' ?>" method="post">
        <!-- Ganti Password -->
        <div class="profil-field has-divider" id="field-password">
          <div class="profil-field-label">Password</div>
          <div class="profil-field-row" id="row-password">
            <div class="profil-field-value password-dots">••••••••</div>
            <button class="btn-ubah" type="button" data-field="password">Ubah</button>
          </div>
          <?= \messageError('current_password'); ?>
          <?= \messageError('new_password'); ?>
          <?= \messageError('confirm_password'); ?>
          <div class="profil-field-edit d-none" id="edit-password">
            <input type="password" class="profil-input mb-2" id="input-pw-lama"
            placeholder="Password lama" name="current_password" />
            <input type="password" class="profil-input mb-2" id="input-pw-baru"
            placeholder="Password baru (min. 8 karakter)" name="new_password" />
            <input type="password" class="profil-input"      id="input-pw-konfirm"
            placeholder="Konfirmasi password baru" name="confirm_password" />
            <div class="profil-edit-actions">
              <button class="btn-batal-field" type="button" data-field="password">Batal</button>
              <button class="btn-simpan-field" type="submit" data-field="password">Simpan</button>
            </div>
          </div>
        </div>
      </form>


    </div><!-- /keamanan -->

  </div><!-- /col-kanan -->

</div><!-- /row -->

<!-- ════════════════════════════════════════
     JS SPESIFIK HALAMAN PROFIL
════════════════════════════════════════ -->
<script src="<?= pathJs('profile') ?>"></script>