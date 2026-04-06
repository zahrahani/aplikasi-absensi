/* ================================================================
   karyawan.js — Logic Halaman Karyawan CV. NAFIHAKA Creative
================================================================ */

'use strict';

/* ----------------------------------------------------------------
   1. DATA AWAL KARYAWAN
---------------------------------------------------------------- */
const dataKaryawanAwal = [
  {
    id: 'NFK-001',
    nama: 'Budi Raharjo',
    email: 'budi.r@nafihaka.id',
    telp: '+62 812-3456-7890',
    tglLahir: '1990-03-15',
    gender: 'Laki-laki',
    alamat: 'Jl. Mawar No. 12, Klaten, Jawa Tengah',
    dept: 'Desain',
    jabatan: 'Senior Designer',
    tglGabung: '2021-01-12',
    kontrak: 'Karyawan Tetap',
    status: 'Aktif',
    gaji: 'Rp 8.500.000',
    avatarBg: '#6c5ce7',
    kehadiran: { hadir: 20, terlambat: 2, tidakHadir: 0, izin: 1 }
  },
  {
    id: 'NFK-002',
    nama: 'Siti Wahyuni',
    email: 'siti.w@nafihaka.id',
    telp: '+62 813-2233-4455',
    tglLahir: '1988-07-22',
    gender: 'Perempuan',
    alamat: 'Jl. Melati No. 5, Solo, Jawa Tengah',
    dept: 'Marketing',
    jabatan: 'Marketing Manager',
    tglGabung: '2019-03-03',
    kontrak: 'Karyawan Tetap',
    status: 'Aktif',
    gaji: 'Rp 10.000.000',
    avatarBg: '#00b894',
    kehadiran: { hadir: 22, terlambat: 0, tidakHadir: 0, izin: 0 }
  },
  {
    id: 'NFK-003',
    nama: 'Dimas Pratama',
    email: 'dimas.p@nafihaka.id',
    telp: '+62 857-8899-0011',
    tglLahir: '1995-11-05',
    gender: 'Laki-laki',
    alamat: 'Jl. Kenanga No. 8, Yogyakarta',
    dept: 'Produksi',
    jabatan: 'Operator Mesin',
    tglGabung: '2022-06-17',
    kontrak: 'Kontrak',
    status: 'Cuti',
    gaji: 'Rp 5.200.000',
    avatarBg: '#e17055',
    kehadiran: { hadir: 10, terlambat: 1, tidakHadir: 0, izin: 12 }
  },
  {
    id: 'NFK-004',
    nama: 'Rizki Laksono',
    email: 'rizki.l@nafihaka.id',
    telp: '+62 821-6677-8899',
    tglLahir: '1998-04-30',
    gender: 'Laki-laki',
    alamat: 'Jl. Anggrek No. 21, Semarang',
    dept: 'IT',
    jabatan: 'Web Developer',
    tglGabung: '2023-09-09',
    kontrak: 'Kontrak',
    status: 'Aktif',
    gaji: 'Rp 7.000.000',
    avatarBg: '#0984e3',
    kehadiran: { hadir: 21, terlambat: 1, tidakHadir: 0, izin: 0 }
  },
  {
    id: 'NFK-005',
    nama: 'Nurul Aini',
    email: 'nurul.a@nafihaka.id',
    telp: '+62 811-5566-7788',
    tglLahir: '1992-09-18',
    gender: 'Perempuan',
    alamat: 'Jl. Dahlia No. 3, Klaten, Jawa Tengah',
    dept: 'Keuangan',
    jabatan: 'Staff Akuntansi',
    tglGabung: '2020-02-22',
    kontrak: 'Karyawan Tetap',
    status: 'Aktif',
    gaji: 'Rp 6.500.000',
    avatarBg: '#a29bfe',
    kehadiran: { hadir: 19, terlambat: 3, tidakHadir: 0, izin: 0 }
  },
  {
    id: 'NFK-006',
    nama: 'Hendra Maulana',
    email: 'hendra.m@nafihaka.id',
    telp: '+62 878-4433-2211',
    tglLahir: '1985-01-10',
    gender: 'Laki-laki',
    alamat: 'Jl. Cempaka No. 17, Surakarta',
    dept: 'HRD',
    jabatan: 'HRD Supervisor',
    tglGabung: '2018-08-01',
    kontrak: 'Karyawan Tetap',
    status: 'Tidak Aktif',
    gaji: 'Rp 9.000.000',
    avatarBg: '#636e72',
    kehadiran: { hadir: 0, terlambat: 0, tidakHadir: 22, izin: 0 }
  },
  {
    id: 'NFK-007',
    nama: 'Dewi Kusuma',
    email: 'dewi.k@nafihaka.id',
    telp: '+62 819-7788-9900',
    tglLahir: '1997-05-25',
    gender: 'Perempuan',
    alamat: 'Jl. Tulip No. 9, Boyolali',
    dept: 'Desain',
    jabatan: 'Junior Designer',
    tglGabung: '2024-02-01',
    kontrak: 'Magang',
    status: 'Aktif',
    gaji: 'Rp 3.000.000',
    avatarBg: '#fd79a8',
    kehadiran: { hadir: 18, terlambat: 4, tidakHadir: 0, izin: 0 }
  },
  {
    id: 'NFK-008',
    nama: 'Agung Santoso',
    email: 'agung.s@nafihaka.id',
    telp: '+62 856-3344-5566',
    tglLahir: '1991-12-03',
    gender: 'Laki-laki',
    alamat: 'Jl. Flamboyan No. 14, Klaten',
    dept: 'Produksi',
    jabatan: 'Kepala Produksi',
    tglGabung: '2017-05-10',
    kontrak: 'Karyawan Tetap',
    status: 'Aktif',
    gaji: 'Rp 12.000.000',
    avatarBg: '#00cec9',
    kehadiran: { hadir: 22, terlambat: 0, tidakHadir: 0, izin: 0 }
  }
];

/* ----------------------------------------------------------------
   2. STATE APLIKASI
---------------------------------------------------------------- */
const state = {
  semua: [...dataKaryawanAwal],        // master list (data aktif)
  filtered: [...dataKaryawanAwal],     // hasil filter/search
  halamanAktif: 1,
  perHalaman: 6,
  karyawanAktif: null,                 // objek karyawan yang sedang dibuka
  filterDept: '',                      // nilai aktif filter departemen
  filterStatus: '',                    // nilai aktif filter status
  modalDetail: null,
  modalForm: null,
  modalHapus: null,
};

/* ----------------------------------------------------------------
   3. UTILITAS
---------------------------------------------------------------- */

/** Ambil inisial dari nama */
function inisial(nama) {
  return nama.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase();
}

/** Format tanggal dari YYYY-MM-DD → DD Month YYYY (ID) */
function formatTanggal(str) {
  if (!str) return '-';
  const bulan = ['Januari','Februari','Maret','April','Mei','Juni',
                 'Juli','Agustus','September','Oktober','November','Desember'];
  const [y, m, d] = str.split('-');
  return `${parseInt(d)} ${bulan[parseInt(m) - 1]} ${y}`;
}

/** Hasilkan warna avatar acak dari palet */
const paletWarna = ['#6c5ce7','#00b894','#e17055','#0984e3','#a29bfe',
                    '#fd79a8','#00cec9','#e84393','#fdcb6e','#74b9ff'];
function warnaDari(str) {
  let hash = 0;
  for (let c of str) hash += c.charCodeAt(0);
  return paletWarna[hash % paletWarna.length];
}

/** Kelas CSS badge departemen */
const deptClass = {
  'Desain':    'dept-desain',
  'Marketing': 'dept-marketing',
  'Produksi':  'dept-produksi',
  'IT':        'dept-it',
  'Keuangan':  'dept-keuangan',
  'HRD':       'dept-hrd',
};

/** Warna dot status */
const statusDotClass = {
  'Aktif':       'bg-success',
  'Cuti':        'bg-warning',
  'Tidak Aktif': 'bg-danger',
};
const statusTextClass = {
  'Aktif':       'text-success',
  'Cuti':        'text-warning',
  'Tidak Aktif': 'text-danger',
};

/** Hasilkan ID baru */
function idBaru() {
  const nums = state.semua.map(k => parseInt(k.id.replace('NFK-', '')));
  const max  = nums.length ? Math.max(...nums) : 0;
  return `NFK-${String(max + 1).padStart(3, '0')}`;
}

/* ----------------------------------------------------------------
   4. RENDER TABEL
---------------------------------------------------------------- */
function renderTabel() {
  const tbody      = document.getElementById('tabel-karyawan');
  const emptyState = document.getElementById('empty-state');
  const start      = (state.halamanAktif - 1) * state.perHalaman;
  const slice      = state.filtered.slice(start, start + state.perHalaman);

  tbody.innerHTML = '';

  if (state.filtered.length === 0) {
    emptyState.classList.remove('d-none');
  } else {
    emptyState.classList.add('d-none');
  }

  slice.forEach(k => {
    const bg = k.avatarBg || warnaDari(k.nama);
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>
        <div class="d-flex align-items-center gap-2">
          <div class="avatar-circle" style="background:${bg};">${inisial(k.nama)}</div>
          <div>
            <div class="fw-semibold">${k.nama}</div>
            <div class="text-muted" style="font-size:.75rem;">${k.email}</div>
          </div>
        </div>
      </td>
      <td><span class="text-muted">#${k.id}</span></td>
      <td><span class="badge badge-dept ${deptClass[k.dept] || ''}">${k.dept}</span></td>
      <td>${k.jabatan}</td>
      <td>${formatTanggal(k.tglGabung)}</td>
      <td>
        <span class="status-dot ${statusDotClass[k.status] || 'bg-secondary'}"></span>
        <span class="${statusTextClass[k.status] || ''} small fw-semibold">${k.status}</span>
      </td>
      <td>
        <button class="action-btn" data-aksi="detail" data-id="${k.id}" title="Detail">👁️</button>
        <button class="action-btn" data-aksi="edit"   data-id="${k.id}" title="Edit">✏️</button>
        <button class="action-btn" data-aksi="hapus"  data-id="${k.id}" title="Hapus">🗑️</button>
      </td>
    `;
    tbody.appendChild(tr);
  });

  renderPagination();
  updateStatCards();
}

/* ----------------------------------------------------------------
   5. PAGINATION
---------------------------------------------------------------- */
function renderPagination() {
  const total    = state.filtered.length;
  const totalHlm = Math.ceil(total / state.perHalaman);
  const start    = (state.halamanAktif - 1) * state.perHalaman + 1;
  const end      = Math.min(start + state.perHalaman - 1, total);
  const nav      = document.getElementById('pagination-nav');
  const info     = document.getElementById('pagination-info');

  info.textContent = total
    ? `Menampilkan ${start}–${end} dari ${total} karyawan`
    : 'Tidak ada data';

  nav.innerHTML = '';

  const buatBtn = (label, halaman, disabled = false, aktif = false) => {
    const btn = document.createElement('button');
    btn.textContent = label;
    if (disabled) btn.disabled = true;
    if (aktif)    btn.classList.add('active');
    if (!disabled && !aktif) {
      btn.addEventListener('click', () => {
        state.halamanAktif = halaman;
        renderTabel();
      });
    }
    return btn;
  };

  nav.appendChild(buatBtn('«', state.halamanAktif - 1, state.halamanAktif === 1));

  for (let i = 1; i <= totalHlm; i++) {
    if (totalHlm > 7 && i > 3 && i < totalHlm - 1 && Math.abs(i - state.halamanAktif) > 1) {
      if (i === 4) nav.appendChild(buatBtn('…', null, true));
      continue;
    }
    nav.appendChild(buatBtn(i, i, false, i === state.halamanAktif));
  }

  nav.appendChild(buatBtn('»', state.halamanAktif + 1, state.halamanAktif === totalHlm));
}

/* ----------------------------------------------------------------
   6. STAT CARDS
---------------------------------------------------------------- */
function updateStatCards() {
  const total     = state.semua.length;
  const aktif     = state.semua.filter(k => k.status === 'Aktif').length;
  const nonaktif  = state.semua.filter(k => k.status !== 'Aktif').length;

  // Karyawan baru = bergabung di bulan & tahun ini
  const now   = new Date();
  const baru  = state.semua.filter(k => {
    if (!k.tglGabung) return false;
    const d = new Date(k.tglGabung);
    return d.getFullYear() === now.getFullYear() && d.getMonth() === now.getMonth();
  }).length;

  document.getElementById('stat-total').textContent    = total;
  document.getElementById('stat-aktif').textContent    = aktif;
  document.getElementById('stat-baru').textContent     = baru;
  document.getElementById('stat-nonaktif').textContent = nonaktif;
}

/* ----------------------------------------------------------------
   7. FILTER & SEARCH
---------------------------------------------------------------- */
function applyFilter() {
  const dept   = state.filterDept.toLowerCase();
  const status = state.filterStatus.toLowerCase();
  const q      = document.getElementById('search-input').value.trim().toLowerCase();

  state.filtered = state.semua.filter(k => {
    const cocokDept   = !dept   || k.dept.toLowerCase()   === dept;
    const cocokStatus = !status || k.status.toLowerCase() === status;
    const cocokQ      = !q      || k.nama.toLowerCase().includes(q) || k.id.toLowerCase().includes(q);
    return cocokDept && cocokStatus && cocokQ;
  });

  state.halamanAktif = 1;
  renderTabel();
}

/* ----------------------------------------------------------------
   8. MODAL DETAIL
---------------------------------------------------------------- */
function bukaDetail(id) {
  const k = state.semua.find(x => x.id === id);
  if (!k) return;
  state.karyawanAktif = k;

  const bg = k.avatarBg || warnaDari(k.nama);

  document.getElementById('detail-avatar').textContent         = inisial(k.nama);
  document.getElementById('detail-avatar').style.background    = bg;
  document.getElementById('detail-nama').textContent           = k.nama;
  document.getElementById('detail-sub').textContent            = `${k.jabatan} · #${k.id}`;
  document.getElementById('detail-email').textContent          = k.email;
  document.getElementById('detail-telp').textContent           = k.telp;
  document.getElementById('detail-tgl-lahir').textContent      = formatTanggal(k.tglLahir);
  document.getElementById('detail-gender').textContent         = k.gender;
  document.getElementById('detail-alamat').textContent         = k.alamat;
  document.getElementById('detail-dept').textContent           = k.dept;
  document.getElementById('detail-jabatan').textContent        = k.jabatan;
  document.getElementById('detail-tgl-gabung').textContent     = formatTanggal(k.tglGabung);
  document.getElementById('detail-kontrak').textContent        = k.kontrak;
  document.getElementById('detail-gaji').textContent           = k.gaji;
  document.getElementById('detail-hadir').textContent          = k.kehadiran?.hadir      ?? '-';
  document.getElementById('detail-terlambat').textContent      = k.kehadiran?.terlambat  ?? '-';
  document.getElementById('detail-tidakhadir').textContent     = k.kehadiran?.tidakHadir ?? '-';
  document.getElementById('detail-izin').textContent           = k.kehadiran?.izin       ?? '-';

  const badgesEl = document.getElementById('detail-badges');
  const stClass  = k.status === 'Aktif' ? 'bg-success' : k.status === 'Cuti' ? 'bg-warning text-dark' : 'bg-danger';
  badgesEl.innerHTML = `
    <span class="badge badge-dept ${deptClass[k.dept] || ''}">${k.dept}</span>
    <span class="badge ${stClass} ms-1">${k.status}</span>
  `;

  state.modalDetail.show();
}

/* ----------------------------------------------------------------
   9. MODAL FORM (TAMBAH / EDIT)
---------------------------------------------------------------- */
function resetForm() {
  ['form-id','form-nama','form-email','form-telp','form-tgl-lahir',
   'form-jabatan','form-gaji','form-alamat'].forEach(id => {
    document.getElementById(id).value = '';
  });
  ['form-gender','form-dept','form-kontrak','form-status'].forEach(id => {
    document.getElementById(id).selectedIndex = 0;
  });
  document.getElementById('form-alert').classList.add('d-none');
}

function bukaTambah() {
  resetForm();
  document.getElementById('modalFormLabel').textContent = 'Tambah Karyawan Baru';
  state.modalForm.show();
}

function bukaEdit(id) {
  const k = state.semua.find(x => x.id === id);
  if (!k) return;

  resetForm();
  document.getElementById('modalFormLabel').textContent = 'Edit Data Karyawan';
  document.getElementById('form-id').value         = k.id;
  document.getElementById('form-nama').value       = k.nama;
  document.getElementById('form-email').value      = k.email;
  document.getElementById('form-telp').value       = k.telp;
  document.getElementById('form-tgl-lahir').value  = k.tglLahir;
  document.getElementById('form-jabatan').value    = k.jabatan;
  document.getElementById('form-gaji').value       = k.gaji;
  document.getElementById('form-alamat').value     = k.alamat;
  document.getElementById('form-gender').value     = k.gender;
  document.getElementById('form-dept').value       = k.dept;
  document.getElementById('form-kontrak').value    = k.kontrak;
  document.getElementById('form-status').value     = k.status;

  // Tutup modal detail jika terbuka, lalu buka form
  state.modalDetail.hide();
  setTimeout(() => state.modalForm.show(), 300);
}

function simpanForm() {
  const alertEl = document.getElementById('form-alert');
  alertEl.classList.add('d-none');

  const nama     = document.getElementById('form-nama').value.trim();
  const email    = document.getElementById('form-email').value.trim();
  const dept     = document.getElementById('form-dept').value;
  const jabatan  = document.getElementById('form-jabatan').value.trim();
  const status   = document.getElementById('form-status').value;

  if (!nama || !email || !dept || !jabatan) {
    alertEl.textContent = 'Nama, Email, Departemen, dan Jabatan wajib diisi.';
    alertEl.classList.remove('d-none');
    return;
  }

  const idForm = document.getElementById('form-id').value;

  const dataForm = {
    nama,
    email,
    telp:      document.getElementById('form-telp').value.trim(),
    tglLahir:  document.getElementById('form-tgl-lahir').value,
    gender:    document.getElementById('form-gender').value,
    dept,
    jabatan,
    kontrak:   document.getElementById('form-kontrak').value,
    tglGabung: document.getElementById('form-tgl-gabung').value,
    status,
    gaji:      document.getElementById('form-gaji').value.trim(),
    alamat:    document.getElementById('form-alamat').value.trim(),
    kehadiran: { hadir: 0, terlambat: 0, tidakHadir: 0, izin: 0 },
  };

  if (idForm) {
    // Edit
    const idx = state.semua.findIndex(x => x.id === idForm);
    if (idx !== -1) {
      dataForm.id         = idForm;
      dataForm.avatarBg   = state.semua[idx].avatarBg;
      dataForm.kehadiran  = state.semua[idx].kehadiran;
      state.semua[idx]    = dataForm;
    }
  } else {
    // Tambah baru
    dataForm.id       = idBaru();
    dataForm.avatarBg = warnaDari(nama);
    state.semua.push(dataForm);
  }

  applyFilter();
  state.modalForm.hide();
}

/* ----------------------------------------------------------------
   10. MODAL HAPUS
---------------------------------------------------------------- */
function bukaHapus(id) {
  const k = state.semua.find(x => x.id === id);
  if (!k) return;
  document.getElementById('hapus-nama').textContent = k.nama;
  document.getElementById('hapus-id').value         = k.id;
  state.modalHapus.show();
}

function konfirmasiHapus() {
  const id  = document.getElementById('hapus-id').value;
  state.semua = state.semua.filter(k => k.id !== id);
  applyFilter();
  state.modalHapus.hide();
}

/* ----------------------------------------------------------------
   11. JAM & TANGGAL REAL-TIME
---------------------------------------------------------------- */
function updateClock() {
  const now   = new Date();
  const hari  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
  const bulan = ['Januari','Februari','Maret','April','Mei','Juni',
                 'Juli','Agustus','September','Oktober','November','Desember'];
  const pad   = n => String(n).padStart(2, '0');

  const tglEl = document.getElementById('tanggal');
  const jamEl = document.getElementById('jam');
  if (tglEl) tglEl.textContent =
    `${hari[now.getDay()]}, ${pad(now.getDate())} ${bulan[now.getMonth()]} ${now.getFullYear()}`;
  if (jamEl) jamEl.textContent =
    `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
}

/* ----------------------------------------------------------------
   12. EVENT LISTENERS & INIT
---------------------------------------------------------------- */
document.addEventListener('DOMContentLoaded', () => {

  /* Inisialisasi modal Bootstrap */
  state.modalDetail = new bootstrap.Modal(document.getElementById('modalDetail'));
  state.modalForm   = new bootstrap.Modal(document.getElementById('modalForm'));
  state.modalHapus  = new bootstrap.Modal(document.getElementById('modalHapus'));

  /* Render awal */
  renderTabel();
  updateClock();
  setInterval(updateClock, 1000);

  /* Filter Departemen — dropdown items */
  document.querySelectorAll('.filter-dept').forEach(item => {
    item.addEventListener('click', e => {
      e.preventDefault();
      state.filterDept = item.dataset.value;
      document.getElementById('filterDeptLabel').textContent =
        item.dataset.value ? item.textContent : 'Semua Departemen';
      applyFilter();
    });
  });

  /* Filter Status — dropdown items */
  document.querySelectorAll('.filter-status').forEach(item => {
    item.addEventListener('click', e => {
      e.preventDefault();
      state.filterStatus = item.dataset.value;
      document.getElementById('filterStatusLabel').textContent =
        item.dataset.value ? item.textContent : 'Semua Status';
      applyFilter();
    });
  });

  /* Search input */
  document.getElementById('search-input').addEventListener('input', applyFilter);

  /* Tombol Tambah Karyawan */
  document.getElementById('btn-tambah-karyawan').addEventListener('click', bukaTambah);

  /* Tombol Simpan form */
  document.getElementById('btn-simpan').addEventListener('click', simpanForm);

  /* Tombol Edit dari modal detail */
  document.getElementById('btn-edit-from-detail').addEventListener('click', () => {
    if (state.karyawanAktif) bukaEdit(state.karyawanAktif.id);
  });

  /* Tombol Konfirmasi Hapus */
  document.getElementById('btn-konfirmasi-hapus').addEventListener('click', konfirmasiHapus);

  /* Delegasi aksi tabel (detail / edit / hapus) */
  document.getElementById('tabel-karyawan').addEventListener('click', e => {
    const btn = e.target.closest('[data-aksi]');
    if (!btn) return;
    const { aksi, id } = btn.dataset;
    if (aksi === 'detail') bukaDetail(id);
    if (aksi === 'edit')   bukaEdit(id);
    if (aksi === 'hapus')  bukaHapus(id);
  });
});
