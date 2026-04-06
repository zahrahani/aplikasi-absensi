/* ═══════════════════════════════════════════
   karyawan.js – CV. NAFIHAKA Creative
   Fitur: Clock, Render Tabel, Search, Filter
          Divisi & Status, Pagination,
          Modal Detail, Modal Tambah/Edit
════════════════════════════════════════════ */

'use strict';

// ─────────────────────────────────────────────
// 1. REAL-TIME CLOCK & DATE
// ─────────────────────────────────────────────
const HARI  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
const BULAN = [
  'Januari','Februari','Maret','April','Mei','Juni',
  'Juli','Agustus','September','Oktober','November','Desember'
];

function updateClock() {
  const now = new Date();
  const pad = (n) => String(n).padStart(2, '0');
  document.getElementById('topbar-clock').textContent =
    `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
  document.getElementById('topbar-date').textContent =
    `${HARI[now.getDay()]}, ${now.getDate()} ${BULAN[now.getMonth()]} ${now.getFullYear()}`;
}
updateClock();
setInterval(updateClock, 1000);


// ─────────────────────────────────────────────
// 2. DATA KARYAWAN
// ─────────────────────────────────────────────
let dataKaryawan = [
  {
    id: 1,
    nama: 'Karezu Shiro',
    inisial: 'KS',
    nip: 'NFC-2022-001',
    divisi: 'HRD',
    jabatan: 'HR Specialist',
    hp: '0812-3456-7890',
    email: 'karezu.shiro@nafihaka.com',
    alamat: 'Jl. Mawar No. 12, Bandung, Jawa Barat',
    bergabung: '2022-03-15',
    status: 'aktif',
    kehadiran: 94,
    izinDisetujui: 3,
    keterlambatan: 2,
  },
  {
    id: 2,
    nama: 'Rania Putri',
    inisial: 'RP',
    nip: 'NFC-2021-008',
    divisi: 'Marketing',
    jabatan: 'Content Creator',
    hp: '0813-5678-9012',
    email: 'rania.putri@nafihaka.com',
    alamat: 'Jl. Melati No. 7, Jakarta Selatan',
    bergabung: '2021-07-20',
    status: 'aktif',
    kehadiran: 89,
    izinDisetujui: 5,
    keterlambatan: 6,
  },
  {
    id: 3,
    nama: 'Dimas Arya',
    inisial: 'DA',
    nip: 'NFC-2023-014',
    divisi: 'IT',
    jabatan: 'Frontend Developer',
    hp: '0857-9012-3456',
    email: 'dimas.arya@nafihaka.com',
    alamat: 'Jl. Anggrek No. 3, Yogyakarta',
    bergabung: '2023-01-10',
    status: 'aktif',
    kehadiran: 97,
    izinDisetujui: 1,
    keterlambatan: 0,
  },
  {
    id: 4,
    nama: 'Siti Aulia',
    inisial: 'SA',
    nip: 'NFC-2020-003',
    divisi: 'Finance',
    jabatan: 'Accounting Staff',
    hp: '0821-6789-0123',
    email: 'siti.aulia@nafihaka.com',
    alamat: 'Jl. Kenanga No. 21, Surabaya, Jawa Timur',
    bergabung: '2020-09-01',
    status: 'aktif',
    kehadiran: 91,
    izinDisetujui: 4,
    keterlambatan: 3,
  },
  {
    id: 5,
    nama: 'Budi Santoso',
    inisial: 'BS',
    nip: 'NFC-2019-007',
    divisi: 'Operations',
    jabatan: 'Logistics Coordinator',
    hp: '0878-0123-4567',
    email: 'budi.santoso@nafihaka.com',
    alamat: 'Jl. Dahlia No. 5, Bekasi, Jawa Barat',
    bergabung: '2019-04-15',
    status: 'aktif',
    kehadiran: 88,
    izinDisetujui: 6,
    keterlambatan: 8,
  },
  {
    id: 6,
    nama: 'Hana Wijaya',
    inisial: 'HW',
    nip: 'NFC-2022-019',
    divisi: 'Design',
    jabatan: 'UI/UX Designer',
    hp: '0819-2345-6789',
    email: 'hana.wijaya@nafihaka.com',
    alamat: 'Jl. Tulip No. 9, Depok, Jawa Barat',
    bergabung: '2022-11-05',
    status: 'cuti',
    kehadiran: 85,
    izinDisetujui: 8,
    keterlambatan: 4,
  },
  {
    id: 7,
    nama: 'Ahmad Fauzi',
    inisial: 'AF',
    nip: 'NFC-2021-011',
    divisi: 'Sales',
    jabatan: 'Account Manager',
    hp: '0856-3456-7891',
    email: 'ahmad.fauzi@nafihaka.com',
    alamat: 'Jl. Bougenville No. 16, Tangerang, Banten',
    bergabung: '2021-02-28',
    status: 'aktif',
    kehadiran: 92,
    izinDisetujui: 3,
    keterlambatan: 1,
  },
  {
    id: 8,
    nama: 'Maya Lestari',
    inisial: 'ML',
    nip: 'NFC-2023-022',
    divisi: 'HRD',
    jabatan: 'Recruitment Staff',
    hp: '0812-9876-5432',
    email: 'maya.lestari@nafihaka.com',
    alamat: 'Jl. Cempaka No. 11, Jakarta Barat',
    bergabung: '2023-05-17',
    status: 'aktif',
    kehadiran: 96,
    izinDisetujui: 2,
    keterlambatan: 1,
  },
  {
    id: 9,
    nama: 'Rizki Maulana',
    inisial: 'RM',
    nip: 'NFC-2022-016',
    divisi: 'IT',
    jabatan: 'Backend Developer',
    hp: '0877-6543-2109',
    email: 'rizki.maulana@nafihaka.com',
    alamat: 'Jl. Seruni No. 4, Bogor, Jawa Barat',
    bergabung: '2022-08-01',
    status: 'aktif',
    kehadiran: 93,
    izinDisetujui: 4,
    keterlambatan: 2,
  },
  {
    id: 10,
    nama: 'Dewi Kartika',
    inisial: 'DK',
    nip: 'NFC-2018-002',
    divisi: 'Finance',
    jabatan: 'Tax Consultant',
    hp: '0821-1234-5678',
    email: 'dewi.kartika@nafihaka.com',
    alamat: 'Jl. Flamboyan No. 8, Malang, Jawa Timur',
    bergabung: '2018-06-10',
    status: 'aktif',
    kehadiran: 90,
    izinDisetujui: 5,
    keterlambatan: 5,
  },
  {
    id: 11,
    nama: 'Farhan Hidayat',
    inisial: 'FH',
    nip: 'NFC-2023-025',
    divisi: 'Marketing',
    jabatan: 'Digital Marketing Staff',
    hp: '0856-7890-1234',
    email: 'farhan.hidayat@nafihaka.com',
    alamat: 'Jl. Mawar No. 22, Semarang, Jawa Tengah',
    bergabung: '2023-09-01',
    status: 'aktif',
    kehadiran: 98,
    izinDisetujui: 1,
    keterlambatan: 0,
  },
  {
    id: 12,
    nama: 'Nadia Kusuma',
    inisial: 'NK',
    nip: 'NFC-2017-001',
    divisi: 'Operations',
    jabatan: 'Operations Manager',
    hp: '0813-0987-6543',
    email: 'nadia.kusuma@nafihaka.com',
    alamat: 'Jl. Kamboja No. 30, Jakarta Pusat',
    bergabung: '2017-01-15',
    status: 'nonaktif',
    kehadiran: 0,
    izinDisetujui: 0,
    keterlambatan: 0,
  },
];

// ─────────────────────────────────────────────
// 3. STATE FILTER, SEARCH, PAGINATION
// ─────────────────────────────────────────────
let filterDivisi  = 'semua';
let filterStatus  = 'semua';
let searchQuery   = '';
let currentPage   = 1;
const rowsPerPage = 8;

// State modal
let selectedId   = null;
let isEditMode   = false;


// ─────────────────────────────────────────────
// 4. HELPERS
// ─────────────────────────────────────────────
function getAvatarClass(divisi) {
  const map = {
    HRD:        'avatar-hrd',
    IT:         'avatar-it',
    Finance:    'avatar-finance',
    Marketing:  'avatar-marketing',
    Operations: 'avatar-operations',
    Design:     'avatar-design',
    Sales:      'avatar-sales',
  };
  return map[divisi] || 'avatar-default';
}

function formatTanggal(str) {
  if (!str) return '—';
  const d = new Date(str);
  return `${d.getDate()} ${BULAN[d.getMonth()]} ${d.getFullYear()}`;
}

function getStatusBadge(status) {
  const map = {
    aktif:    { cls: 'status-aktif',    icon: 'bi-circle-fill', label: 'Aktif'     },
    cuti:     { cls: 'status-cuti',     icon: 'bi-moon-fill',   label: 'Cuti'       },
    nonaktif: { cls: 'status-nonaktif', icon: 'bi-x-circle',    label: 'Non-Aktif'  },
  };
  const s = map[status] || map['nonaktif'];
  return `<span class="status-badge ${s.cls}">
    <i class="bi ${s.icon}" style="font-size:.65rem;"></i> ${s.label}
  </span>`;
}

function getPctClass(pct) {
  if (pct >= 90) return 'pct-high';
  if (pct >= 75) return 'pct-medium';
  return 'pct-low';
}

function updateStatCards() {
  const aktif    = dataKaryawan.filter(k => k.status === 'aktif').length;
  const cuti     = dataKaryawan.filter(k => k.status === 'cuti').length;
  const divisiSet = new Set(dataKaryawan.map(k => k.divisi));
  const thisYear = new Date().getFullYear();
  const thisMonth = new Date().getMonth();
  const baru = dataKaryawan.filter(k => {
    const d = new Date(k.bergabung);
    return d.getFullYear() === thisYear && d.getMonth() === thisMonth;
  }).length;

  document.getElementById('stat-total').textContent   = dataKaryawan.length;
  document.getElementById('stat-aktif').textContent   = aktif;
  document.getElementById('stat-cuti').textContent    = cuti;
  document.getElementById('stat-divisi').textContent  = divisiSet.size;
  document.getElementById('stat-baru').textContent    = baru;
}


// ─────────────────────────────────────────────
// 5. RENDER TABEL
// ─────────────────────────────────────────────
function getFiltered() {
  return dataKaryawan.filter(k => {
    const matchDivisi = filterDivisi === 'semua' || k.divisi === filterDivisi;
    const matchStatus = filterStatus === 'semua' || k.status === filterStatus;
    const q = searchQuery.toLowerCase();
    const matchSearch = !q ||
      k.nama.toLowerCase().includes(q) ||
      k.nip.toLowerCase().includes(q) ||
      k.jabatan.toLowerCase().includes(q) ||
      k.divisi.toLowerCase().includes(q) ||
      k.email.toLowerCase().includes(q);
    return matchDivisi && matchStatus && matchSearch;
  });
}

function renderTabel() {
  const filtered   = getFiltered();
  const tbody      = document.getElementById('table-body');
  const emptyState = document.getElementById('empty-state');
  const totalRows  = filtered.length;
  const totalPages = Math.ceil(totalRows / rowsPerPage) || 1;

  if (currentPage > totalPages) currentPage = totalPages;

  const start   = (currentPage - 1) * rowsPerPage;
  const pageData = filtered.slice(start, start + rowsPerPage);

  tbody.innerHTML = '';

  if (totalRows === 0) {
    emptyState.classList.remove('d-none');
    renderPagination(0, 0, 0);
    return;
  }
  emptyState.classList.add('d-none');

  pageData.forEach(k => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>
        <div class="karyawan-cell">
          <div class="karyawan-avatar ${getAvatarClass(k.divisi)}">${k.inisial}</div>
          <div>
            <div class="karyawan-name">${k.nama}</div>
            <div class="karyawan-nip">${k.nip}</div>
          </div>
        </div>
      </td>
      <td><span class="divisi-badge">${k.divisi}</span></td>
      <td style="font-size:.875rem; color:var(--text); font-weight:500;">${k.jabatan}</td>
      <td style="font-family:'DM Mono',monospace; font-size:.82rem; color:var(--muted);">${k.hp}</td>
      <td style="font-size:.82rem; color:var(--muted); white-space:nowrap;">${formatTanggal(k.bergabung)}</td>
      <td>${getStatusBadge(k.status)}</td>
      <td>
        <button class="btn-detail btn-open-detail" data-id="${k.id}">
          <i class="bi bi-eye"></i> Detail
        </button>
      </td>
    `;
    tbody.appendChild(tr);
  });

  // Event listener tombol detail
  tbody.querySelectorAll('.btn-open-detail').forEach(btn => {
    btn.addEventListener('click', () => bukaModalDetail(parseInt(btn.dataset.id)));
  });

  renderPagination(totalRows, start, pageData.length);
}

function renderPagination(total, start, count) {
  const info    = document.getElementById('pagination-info');
  const btnWrap = document.getElementById('pagination-btns');
  const totalPages = Math.ceil(total / rowsPerPage) || 1;

  info.textContent = total > 0
    ? `Menampilkan ${start + 1}–${start + count} dari ${total} karyawan`
    : 'Tidak ada data';

  btnWrap.innerHTML = '';

  // Tombol Prev
  const prev = document.createElement('button');
  prev.className = 'btn-page';
  prev.innerHTML = '<i class="bi bi-chevron-left"></i>';
  prev.disabled  = currentPage === 1;
  prev.addEventListener('click', () => { currentPage--; renderTabel(); });
  btnWrap.appendChild(prev);

  // Tombol halaman
  for (let i = 1; i <= totalPages; i++) {
    const btn = document.createElement('button');
    btn.className = `btn-page${i === currentPage ? ' active' : ''}`;
    btn.textContent = i;
    btn.addEventListener('click', () => { currentPage = i; renderTabel(); });
    btnWrap.appendChild(btn);
  }

  // Tombol Next
  const next = document.createElement('button');
  next.className = 'btn-page';
  next.innerHTML = '<i class="bi bi-chevron-right"></i>';
  next.disabled  = currentPage === totalPages;
  next.addEventListener('click', () => { currentPage++; renderTabel(); });
  btnWrap.appendChild(next);
}


// ─────────────────────────────────────────────
// 6. MODAL DETAIL KARYAWAN
// ─────────────────────────────────────────────
const bsModalDetail = new bootstrap.Modal(document.getElementById('modalDetail'));

function bukaModalDetail(id) {
  selectedId = id;
  const k = dataKaryawan.find(x => x.id === id);
  if (!k) return;

  const body = document.getElementById('modal-detail-body');
  body.innerHTML = `
    <!-- Avatar + Nama -->
    <div class="modal-avatar-wrap">
      <div class="modal-avatar ${getAvatarClass(k.divisi)}">${k.inisial}</div>
      <div>
        <div class="modal-karyawan-name">${k.nama}</div>
        <div class="modal-karyawan-sub">${k.jabatan} &bull; ${k.divisi}</div>
        <div style="margin-top:6px;">${getStatusBadge(k.status)}</div>
      </div>
    </div>

    <!-- Stat Grid -->
    <div class="detail-grid">
      <div class="detail-stat-box">
        <div class="detail-stat-number">${k.kehadiran}%</div>
        <div class="detail-stat-label">Kehadiran</div>
      </div>
      <div class="detail-stat-box">
        <div class="detail-stat-number">${k.izinDisetujui}</div>
        <div class="detail-stat-label">Izin Disetujui</div>
      </div>
      <div class="detail-stat-box">
        <div class="detail-stat-number">${k.keterlambatan}</div>
        <div class="detail-stat-label">Keterlambatan</div>
      </div>
    </div>

    <!-- Info Detail -->
    <div class="detail-info-row"><span>NIP</span><span>${k.nip}</span></div>
    <div class="detail-info-row"><span>Email</span><span>${k.email}</span></div>
    <div class="detail-info-row"><span>No. HP</span><span>${k.hp}</span></div>
    <div class="detail-info-row"><span>Tanggal Bergabung</span><span>${formatTanggal(k.bergabung)}</span></div>
    <div class="detail-info-row"><span>Alamat</span><span>${k.alamat}</span></div>
  `;

  bsModalDetail.show();
}

// Tombol Edit di Modal Detail → buka Modal Form dgn data terisi
document.getElementById('btnModalEdit').addEventListener('click', () => {
  if (!selectedId) return;
  bsModalDetail.hide();
  setTimeout(() => bukaModalForm(selectedId), 300);
});


// ─────────────────────────────────────────────
// 7. MODAL TAMBAH / EDIT KARYAWAN
// ─────────────────────────────────────────────
const bsModalForm = new bootstrap.Modal(document.getElementById('modalForm'));

function bukaModalForm(id = null) {
  isEditMode = id !== null;
  selectedId = id;

  document.getElementById('modalFormLabel').textContent =
    isEditMode ? 'Edit Data Karyawan' : 'Tambah Karyawan Baru';
  document.getElementById('form-error').classList.add('d-none');

  if (isEditMode) {
    const k = dataKaryawan.find(x => x.id === id);
    if (!k) return;
    document.getElementById('formNama').value    = k.nama;
    document.getElementById('formNip').value     = k.nip;
    document.getElementById('formDivisi').value  = k.divisi;
    document.getElementById('formJabatan').value = k.jabatan;
    document.getElementById('formHp').value      = k.hp;
    document.getElementById('formEmail').value   = k.email;
    document.getElementById('formTanggal').value = k.bergabung;
    document.getElementById('formStatus').value  = k.status;
    document.getElementById('formAlamat').value  = k.alamat;
  } else {
    document.getElementById('formNama').value    = '';
    document.getElementById('formNip').value     = '';
    document.getElementById('formDivisi').value  = '';
    document.getElementById('formJabatan').value = '';
    document.getElementById('formHp').value      = '';
    document.getElementById('formEmail').value   = '';
    document.getElementById('formTanggal').value = '';
    document.getElementById('formStatus').value  = 'aktif';
    document.getElementById('formAlamat').value  = '';
  }

  bsModalForm.show();
}

// Validasi & Simpan Form
document.getElementById('btnSimpan').addEventListener('click', () => {
  const nama    = document.getElementById('formNama').value.trim();
  const nip     = document.getElementById('formNip').value.trim();
  const divisi  = document.getElementById('formDivisi').value;
  const jabatan = document.getElementById('formJabatan').value.trim();
  const hp      = document.getElementById('formHp').value.trim();
  const email   = document.getElementById('formEmail').value.trim();
  const tanggal = document.getElementById('formTanggal').value;
  const status  = document.getElementById('formStatus').value;
  const alamat  = document.getElementById('formAlamat').value.trim();

  const errEl  = document.getElementById('form-error');
  const errMsg = document.getElementById('form-error-msg');

  // Validasi sederhana
  if (!nama || !nip || !divisi || !jabatan || !hp || !email || !tanggal) {
    errEl.classList.remove('d-none');
    errMsg.textContent = 'Harap lengkapi semua field yang wajib diisi.';
    return;
  }

  errEl.classList.add('d-none');

  // Buat inisial dari nama
  const parts   = nama.trim().split(' ');
  const inisial = parts.length >= 2
    ? (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
    : nama.substring(0, 2).toUpperCase();

  if (isEditMode) {
    // Update data yang sudah ada
    dataKaryawan = dataKaryawan.map(k => k.id === selectedId
      ? { ...k, nama, nip, divisi, jabatan, hp, email, bergabung: tanggal, status, alamat, inisial }
      : k
    );
  } else {
    // Tambah data baru
    const newId = Math.max(...dataKaryawan.map(k => k.id)) + 1;
    dataKaryawan.push({
      id: newId,
      nama, nip, divisi, jabatan, hp, email,
      alamat,
      bergabung: tanggal,
      status,
      inisial,
      kehadiran: 0,
      izinDisetujui: 0,
      keterlambatan: 0,
    });
  }

  bsModalForm.hide();
  updateStatCards();
  renderTabel();
});


// ─────────────────────────────────────────────
// 8. TOMBOL TAMBAH KARYAWAN
// ─────────────────────────────────────────────
document.getElementById('btnTambah').addEventListener('click', () => {
  bukaModalForm(null);
});


// ─────────────────────────────────────────────
// 9. SEARCH
// ─────────────────────────────────────────────
document.getElementById('searchInput').addEventListener('input', function () {
  searchQuery  = this.value.toLowerCase().trim();
  currentPage  = 1;
  renderTabel();
});


// ─────────────────────────────────────────────
// 10. FILTER DIVISI
// ─────────────────────────────────────────────
document.querySelectorAll('.filter-divisi').forEach(item => {
  item.addEventListener('click', function (e) {
    e.preventDefault();
    filterDivisi = this.dataset.value;
    document.getElementById('filterDivisiLabel').textContent = this.textContent;
    currentPage  = 1;
    renderTabel();
  });
});


// ─────────────────────────────────────────────
// 11. FILTER STATUS
// ─────────────────────────────────────────────
document.querySelectorAll('.filter-status').forEach(item => {
  item.addEventListener('click', function (e) {
    e.preventDefault();
    filterStatus = this.dataset.value;
    document.getElementById('filterStatusLabel').textContent = this.textContent;
    currentPage  = 1;
    renderTabel();
  });
});


// ─────────────────────────────────────────────
// 12. SIDEBAR ACTIVE STATE
// ─────────────────────────────────────────────
document.querySelectorAll('.sidebar-item').forEach(item => {
  item.addEventListener('click', function () {
    document.querySelectorAll('.sidebar-item').forEach(el => el.classList.remove('active'));
    this.classList.add('active');
  });
});


// ─────────────────────────────────────────────
// 13. INIT
// ─────────────────────────────────────────────
updateStatCards();
renderTabel();
