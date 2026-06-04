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
  }
});

/* ─────────────────────────────────────────────
   14. UBAH FOTO PROFIL
───────────────────────────────────────────── */
const inputFoto    = document.getElementById('inputFotoProfil');
const btnSimpanFoto = document.getElementById('btnSimpanFoto');
const btnBatalFoto  = document.getElementById('btnBatalFoto');
const ubahFotoLabel = document.querySelector('.ubah-foto-label');

// Snapshot inisial avatar sebelum preview agar bisa di-restore saat batal
let snapshotAvatar = null;

if (inputFoto) {
    inputFoto.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        // Validasi tipe
        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
        if (!allowedTypes.includes(file.type)) {
            showAlert('error', 'Format foto tidak didukung. Gunakan JPG, PNG, atau WEBP.');
            return;
        }

        // Simpan snapshot avatar sebelum diganti preview
        const avatarEl = document.getElementById('avatarDisplay');
        snapshotAvatar = avatarEl.innerHTML;

        // Preview foto di avatar
        const reader = new FileReader();
        reader.onload = function (ev) {
            avatarEl.innerHTML = `<img src="${ev.target.result}" alt="Foto Profil"/>`;
        };
        reader.readAsDataURL(file);

        // Tampilkan tombol simpan & batal, sembunyikan label ubah foto
        btnSimpanFoto.classList.remove('d-none');
        btnBatalFoto.classList.remove('d-none');
        ubahFotoLabel.classList.add('d-none');
    });
}

if (btnBatalFoto) {
    btnBatalFoto.addEventListener('click', function () {
        // Kembalikan avatar ke kondisi semula
        const avatarEl = document.getElementById('avatarDisplay');
        if (snapshotAvatar !== null) avatarEl.innerHTML = snapshotAvatar;

        // Reset input file agar bisa pilih file yang sama lagi
        inputFoto.value = '';
        snapshotAvatar  = null;

        // Sembunyikan tombol simpan & batal, tampilkan kembali label ubah foto
        btnSimpanFoto.classList.add('d-none');
        btnBatalFoto.classList.add('d-none');
        ubahFotoLabel.classList.remove('d-none');
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