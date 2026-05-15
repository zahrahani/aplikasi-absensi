/* ═══════════════════════════════════════════
   profil.js – CV. NAFIHAKA Creative
   Fitur: Inline Edit per Field, Ganti Password,
          Ubah Foto Profil, Alert Notifikasi,
          Tombol Simpan Semua, CSRF Token
════════════════════════════════════════════ */

'use strict';

/* ─────────────────────────────────────────────
   1. HELPER: AMBIL CSRF TOKEN
───────────────────────────────────────────── */
function getCsrfToken() {
  const el = document.getElementById('token_csrf');
  return el ? el.value : '';
}

/* ─────────────────────────────────────────────
   2. HELPER: TAMPILKAN ALERT
───────────────────────────────────────────── */
function showAlert(type, msg) {
  // type: 'success' | 'error'
  const alertSuccess = document.getElementById('profilAlertSuccess');
  const alertError   = document.getElementById('profilAlertError');

  // Sembunyikan keduanya dulu
  alertSuccess.classList.add('d-none');
  alertError.classList.add('d-none');

  if (type === 'success') {
    document.getElementById('profilAlertMsg').textContent = msg;
    alertSuccess.classList.remove('d-none');
  } else {
    document.getElementById('profilAlertErrorMsg').textContent = msg;
    alertError.classList.remove('d-none');
  }

  // Auto-hide setelah 4 detik
  setTimeout(() => {
    alertSuccess.classList.add('d-none');
    alertError.classList.add('d-none');
  }, 4000);

  // Scroll ke atas agar alert terlihat
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

/* ─────────────────────────────────────────────
   3. HELPER: FORMAT TANGGAL (untuk input date → display)
───────────────────────────────────────────── */
const BULAN_NAMA = [
  'Januari','Februari','Maret','April','Mei','Juni',
  'Juli','Agustus','September','Oktober','November','Desember'
];

function formatDateDisplay(isoStr) {
  // isoStr: '1998-08-12' → '12-Agustus-1998'
  if (!isoStr) return '';
  const [y, m, d] = isoStr.split('-');
  return `${parseInt(d)}-${BULAN_NAMA[parseInt(m) - 1]}-${y}`;
}

/* ─────────────────────────────────────────────
   4. STATE PERUBAHAN (untuk Simpan Semua)
───────────────────────────────────────────── */
// Menyimpan semua field yang sudah diedit tapi belum dikirim ke server
const pendingChanges = {};

/* ─────────────────────────────────────────────
   5. BUKA / TUTUP EDIT FIELD
───────────────────────────────────────────── */
function openEdit(fieldKey) {
  const row  = document.getElementById(`row-${fieldKey}`);
  const edit = document.getElementById(`edit-${fieldKey}`);
  if (!row || !edit) return;

  row.classList.add('d-none');
  edit.classList.remove('d-none');

  // Fokus ke input pertama
  const firstInput = edit.querySelector('input, textarea, select');
  if (firstInput) {
    setTimeout(() => firstInput.focus(), 50);
  }
}

function closeEdit(fieldKey) {
  const row  = document.getElementById(`row-${fieldKey}`);
  const edit = document.getElementById(`edit-${fieldKey}`);
  if (!row || !edit) return;

  edit.classList.add('d-none');
  row.classList.remove('d-none');
}

/* ─────────────────────────────────────────────
   6. SIMPAN FIELD INDIVIDUAL (update DOM + state)
───────────────────────────────────────────── */
function saveField(fieldKey) {
  let newValue   = '';
  let displayVal = '';

  // ── Kasus khusus: password ──────────────────
  if (fieldKey === 'password') {
    const lama    = document.getElementById('input-pw-lama').value.trim();
    const baru    = document.getElementById('input-pw-baru').value.trim();
    const konfirm = document.getElementById('input-pw-konfirm').value.trim();

    if (!lama || !baru || !konfirm) {
      showAlert('error', 'Semua field password wajib diisi.');
      return;
    }
    if (baru.length < 8) {
      showAlert('error', 'Password baru minimal 8 karakter.');
      return;
    }
    if (baru !== konfirm) {
      showAlert('error', 'Konfirmasi password tidak cocok.');
      return;
    }

    // Simpan ke pending
    pendingChanges['password'] = { lama, baru };

    // Reset input & tutup
    document.getElementById('input-pw-lama').value    = '';
    document.getElementById('input-pw-baru').value    = '';
    document.getElementById('input-pw-konfirm').value = '';

    closeEdit('password');
    showAlert('success', 'Password siap diperbarui. Klik "Simpan Perubahan" untuk menerapkan.');
    return;
  }

  // ── Kasus khusus: tanggal lahir ─────────────
  if (fieldKey === 'lahir') {
    const inputEl = document.getElementById('input-lahir');
    if (!inputEl || !inputEl.value) {
      showAlert('error', 'Tanggal lahir tidak boleh kosong.');
      return;
    }
    newValue   = inputEl.value;               // ISO: '1998-08-12'
    displayVal = formatDateDisplay(newValue);  // '12-Agustus-1998'
    document.getElementById('val-lahir').textContent = displayVal;
    pendingChanges['tanggal_lahir'] = newValue;
    closeEdit('lahir');
    showAlert('success', `Tanggal lahir diubah ke "${displayVal}". Klik "Simpan Perubahan" untuk menyimpan.`);
    return;
  }

  // ── Field teks biasa ─────────────────────────
  const inputEl = document.getElementById(`input-${fieldKey}`);
  if (!inputEl) return;

  newValue = inputEl.tagName === 'TEXTAREA'
    ? inputEl.value.trim()
    : inputEl.value.trim();

  // Validasi tidak boleh kosong
  if (!newValue) {
    showAlert('error', 'Field tidak boleh kosong.');
    inputEl.focus();
    return;
  }

  // Validasi email sederhana
  if (fieldKey === 'email') {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(newValue)) {
      showAlert('error', 'Format email tidak valid.');
      inputEl.focus();
      return;
    }
  }

  // Update tampilan nilai
  displayVal = newValue;
  document.getElementById(`val-${fieldKey}`).textContent = displayVal;

  // Update nama di kartu identitas jika field adalah 'nama'
  if (fieldKey === 'nama') {
    updateIdentityName(newValue);
  }

  // Simpan ke pending
  pendingChanges[fieldKey] = newValue;

  closeEdit(fieldKey);
  showAlert('success', `"${getLabelField(fieldKey)}" diubah. Klik "Simpan Perubahan" untuk menyimpan.`);
}

/* ─────────────────────────────────────────────
   7. HELPER: LABEL FIELD
───────────────────────────────────────────── */
function getLabelField(key) {
  const labels = {
    nama:    'Nama Lengkap',
    email:   'Email',
    hp:      'Nomor HP',
    lahir:   'Tanggal Lahir',
    alamat:  'Alamat',
    foto:    'Foto Profil',
    password:'Password',
  };
  return labels[key] || key;
}

/* ─────────────────────────────────────────────
   8. UPDATE IDENTITAS NAMA & INISIAL
───────────────────────────────────────────── */
function updateIdentityName(newNama) {
  // Update teks nama di kartu identitas
  const nameEl = document.querySelector('.identity-name');
  if (nameEl) {
    // Hapus semua child text node, pertahankan tombol pensil
    const btnPencil = nameEl.querySelector('.btn-edit-inline');
    nameEl.textContent = newNama + ' ';
    if (btnPencil) nameEl.appendChild(btnPencil);
  }

  // Update inisial avatar
  const avatarEl = document.getElementById('avatarDisplay');
  if (avatarEl && !avatarEl.querySelector('img')) {
    const parts   = newNama.trim().split(' ');
    const inisial = parts.length >= 2
      ? (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
      : newNama.substring(0, 2).toUpperCase();
    avatarEl.textContent = inisial;
  }
}

/* ─────────────────────────────────────────────
   9. TOMBOL "UBAH" – EVENT LISTENER
───────────────────────────────────────────── */
document.querySelectorAll('.btn-ubah').forEach(btn => {
  btn.addEventListener('click', function () {
    const fieldKey = this.dataset.field;

    // Kasus khusus foto → trigger input file
    if (fieldKey === 'foto') {
      document.getElementById('inputFotoProfil').click();
      return;
    }

    openEdit(fieldKey);
  });
});

/* ─────────────────────────────────────────────
   10. TOMBOL "BATAL" – EVENT LISTENER
───────────────────────────────────────────── */
document.querySelectorAll('.btn-batal-field').forEach(btn => {
  btn.addEventListener('click', function () {
    closeEdit(this.dataset.field);
  });
});

/* ─────────────────────────────────────────────
   11. TOMBOL "SIMPAN" PER FIELD – EVENT LISTENER
───────────────────────────────────────────── */
document.querySelectorAll('.btn-simpan-field').forEach(btn => {
  btn.addEventListener('click', function () {
    saveField(this.dataset.field);
  });
});

/* ─────────────────────────────────────────────
   12. TOMBOL PENSIL NAMA (shortcut ke edit-nama)
───────────────────────────────────────────── */
const btnEditNamaInline = document.getElementById('btnEditNamaInline');
if (btnEditNamaInline) {
  btnEditNamaInline.addEventListener('click', () => openEdit('nama'));
}

/* ─────────────────────────────────────────────
   13. KEYBOARD: Enter = Simpan, Escape = Batal
───────────────────────────────────────────── */
document.addEventListener('keydown', function (e) {
  // Cari edit area yang sedang aktif (tidak d-none)
  const activeEdit = document.querySelector('.profil-field-edit:not(.d-none)');
  if (!activeEdit) return;

  const fieldKey = activeEdit.id.replace('edit-', '');

  if (e.key === 'Escape') {
    e.preventDefault();
    closeEdit(fieldKey);
  }

  // Enter pada input (bukan textarea) → simpan
  if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
    e.preventDefault();
    saveField(fieldKey);
  }
});

/* ─────────────────────────────────────────────
   14. UBAH FOTO PROFIL
───────────────────────────────────────────── */
const inputFoto = document.getElementById('inputFotoProfil');
if (inputFoto) {
  inputFoto.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;

    // Validasi tipe & ukuran
    const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
    if (!allowedTypes.includes(file.type)) {
      showAlert('error', 'Format foto tidak didukung. Gunakan JPG, PNG, atau WEBP.');
      return;
    }
    if (file.size > 2 * 1024 * 1024) { // Maks 2 MB
      showAlert('error', 'Ukuran foto melebihi 2 MB.');
      return;
    }

    // Preview foto di avatar
    const reader = new FileReader();
    reader.onload = function (ev) {
      const avatarEl = document.getElementById('avatarDisplay');
      if (avatarEl) {
        avatarEl.innerHTML = `<img src="${ev.target.result}" alt="Foto Profil"/>`;
      }
      // Update nama file di field foto
      const valFoto = document.getElementById('val-foto');
      if (valFoto) valFoto.textContent = file.name;
    };
    reader.readAsDataURL(file);

    // Simpan ke pending
    pendingChanges['foto'] = file;

    showAlert('success', `Foto "${file.name}" dipilih. Klik "Simpan Perubahan" untuk mengunggah.`);
  });
}

/* ─────────────────────────────────────────────
   15. TOMBOL "SIMPAN PERUBAHAN" (kirim semua ke server)
───────────────────────────────────────────── */
const btnSimpanProfil = document.getElementById('btnSimpanProfil');
if (btnSimpanProfil) {
  btnSimpanProfil.addEventListener('click', async function () {

    // Jika tidak ada perubahan
    if (Object.keys(pendingChanges).length === 0) {
      showAlert('error', 'Belum ada perubahan yang perlu disimpan.');
      return;
    }

    // Ubah tampilan tombol → loading
    const originalHTML = this.innerHTML;
    this.innerHTML = '<i class="bi bi-hourglass-split"></i>&nbsp; Menyimpan...';
    this.disabled  = true;

    try {
      // Gunakan FormData agar bisa kirim file (foto) sekaligus
      const formData = new FormData();
      formData.append('csrf_token', getCsrfToken());

      Object.entries(pendingChanges).forEach(([key, val]) => {
        if (val instanceof File) {
          formData.append(key, val);
        } else if (key === 'password') {
          formData.append('pw_lama', val.lama);
          formData.append('pw_baru', val.baru);
        } else {
          formData.append(key, val);
        }
      });

      const response = await fetch('/profil/simpan', {
        method: 'POST',
        body:   formData,
      });

      const result = await response.json();

      if (result.success) {
        // Bersihkan pending
        Object.keys(pendingChanges).forEach(k => delete pendingChanges[k]);
        showAlert('success', result.message || 'Profil berhasil disimpan.');
      } else {
        showAlert('error', result.message || 'Gagal menyimpan. Coba lagi.');
      }

    } catch (err) {
      console.error('[profil.js] Gagal kirim data:', err);
      showAlert('error', 'Koneksi gagal. Periksa jaringan dan coba lagi.');
    } finally {
      // Kembalikan tombol
      this.innerHTML = originalHTML;
      this.disabled  = false;
    }
  });
}

/* ─────────────────────────────────────────────
   16. SIDEBAR ACTIVE STATE
   (konsisten dengan dashboard.js & rekap.js)
───────────────────────────────────────────── */
document.querySelectorAll('.sidebar-item').forEach(item => {
  item.addEventListener('click', function (e) {
    // Hanya prevent default jika bukan link navigasi nyata
    if (!this.getAttribute('href') || this.getAttribute('href') === '#') {
      e.preventDefault();
    }
    document.querySelectorAll('.sidebar-item').forEach(el => el.classList.remove('active'));
    this.classList.add('active');
  });
});
