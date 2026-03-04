/* ═══════════════════════════════════════════
   rekap.js – CV. NAFIHAKA Creative
   Fitur: Clock, Data, Render Tabel, Search,
          Filter Bulan & Divisi, Pagination,
          Modal Detail, Export PDF (print)
════════════════════════════════════════════ */

'use strict';

// ─────────────────────────────────────────────
// 1. REAL-TIME CLOCK & DATE
// ─────────────────────────────────────────────
const HARI  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
const BULAN = ['Januari','Februari','Maret','April','Mei','Juni',
               'Juli','Agustus','September','Oktober','November','Desember'];

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
const dataKaryawan = [
  { id:1,  nama:'Karezu Shiro',   nip:'EM001', divisi:'HRD',        hadir:16, terlambat:1, izin:2, absen:0, pct:84 },
  { id:2,  nama:'Rania Putri',    nip:'EM002', divisi:'Marketing',  hadir:18, terlambat:2, izin:1, absen:0, pct:90 },
  { id:3,  nama:'Dimas Arya',     nip:'EM003', divisi:'IT',         hadir:15, terlambat:3, izin:1, absen:2, pct:79 },
  { id:4,  nama:'Siti Aulia',     nip:'EM004', divisi:'Finance',    hadir:19, terlambat:0, izin:0, absen:1, pct:95 },
  { id:5,  nama:'Budi Santoso',   nip:'EM005', divisi:'Operations', hadir:14, terlambat:4, izin:3, absen:1, pct:74 },
  { id:6,  nama:'Hana Wijaya',    nip:'EM006', divisi:'Design',     hadir:17, terlambat:1, izin:2, absen:0, pct:89 },
  { id:7,  nama:'Ahmad Fauzi',    nip:'EM007', divisi:'Sales',      hadir:20, terlambat:0, izin:0, absen:0, pct:100 },
  { id:8,  nama:'Maya Lestari',   nip:'EM008', divisi:'HRD',        hadir:13, terlambat:5, izin:4, absen:2, pct:68 },
  { id:9,  nama:'Rizki Maulana',  nip:'EM009', divisi:'IT',         hadir:16, terlambat:2, izin:1, absen:1, pct:84 },
  { id:10, nama:'Dewi Kartika',   nip:'EM010', divisi:'Finance',    hadir:18, terlambat:1, izin:1, absen:0, pct:92 },
  { id:11, nama:'Fajar Pratama',  nip:'EM011', divisi:'IT',         hadir:15, terlambat:2, izin:2, absen:1, pct:79 },
  { id:12, nama:'Nisa Rahayu',    nip:'EM012', divisi:'Marketing',  hadir:17, terlambat:3, izin:1, absen:0, pct:87 },
  { id:13, nama:'Andi Kurniawan', nip:'EM013', divisi:'Operations', hadir:12, terlambat:6, izin:3, absen:4, pct:63 },
  { id:14, nama:'Lila Santika',   nip:'EM014', divisi:'Design',     hadir:19, terlambat:1, izin:0, absen:0, pct:97 },
  { id:15, nama:'Yusuf Hakim',    nip:'EM015', divisi:'Sales',      hadir:16, terlambat:2, izin:2, absen:1, pct:84 },
  { id:16, nama:'Citra Amalia',   nip:'EM016', divisi:'HRD',        hadir:18, terlambat:0, izin:1, absen:0, pct:93 },
  { id:17, nama:'Bagas Wicaksono',nip:'EM017', divisi:'IT',         hadir:14, terlambat:4, izin:2, absen:2, pct:74 },
  { id:18, nama:'Putri Andini',   nip:'EM018', divisi:'Finance',    hadir:20, terlambat:0, izin:0, absen:0, pct:100 },
  { id:19, nama:'Hendra Saputra', nip:'EM019', divisi:'Sales',      hadir:15, terlambat:3, izin:2, absen:1, pct:79 },
  { id:20, nama:'Wulandari',      nip:'EM020', divisi:'Marketing',  hadir:17, terlambat:1, izin:2, absen:0, pct:88 },
];

// ─────────────────────────────────────────────
// 3. STATE
// ─────────────────────────────────────────────
let filterBulan  = 'Februari 2026';
let filterDivisi = 'semua';
let searchQuery  = '';
let currentPage  = 1;
const perPage    = 8;

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
  return map[divisi] || 'avatar-hrd';
}

function getInisial(nama) {
  return nama.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase();
}

function getPctClass(pct) {
  if (pct >= 90) return 'pct-high';
  if (pct >= 75) return 'pct-medium';
  return 'pct-low';
}

function getFiltered() {
  return dataKaryawan.filter(k => {
    const matchDiv  = filterDivisi === 'semua' || k.divisi === filterDivisi;
    const matchSrc  = k.nama.toLowerCase().includes(searchQuery) ||
                      k.nip.toLowerCase().includes(searchQuery)  ||
                      k.divisi.toLowerCase().includes(searchQuery);
    return matchDiv && matchSrc;
  });
}

// ─────────────────────────────────────────────
// 5. RENDER TABEL
// ─────────────────────────────────────────────
function renderTabel() {
  const tbody      = document.getElementById('table-body');
  const emptyState = document.getElementById('empty-state');
  const filtered   = getFiltered();

  tbody.innerHTML = '';

  if (filtered.length === 0) {
    emptyState.classList.remove('d-none');
    document.getElementById('pagination-info').textContent = '';
    document.getElementById('pagination-btns').innerHTML = '';
    return;
  }
  emptyState.classList.add('d-none');

  // Pagination slice
  const totalPages = Math.ceil(filtered.length / perPage);
  if (currentPage > totalPages) currentPage = 1;

  const start = (currentPage - 1) * perPage;
  const slice = filtered.slice(start, start + perPage);

  slice.forEach((k, idx) => {
    const tr = document.createElement('tr');
    tr.style.animationDelay = `${idx * 0.04}s`;
    tr.innerHTML = `
      <td>
        <div class="karyawan-cell">
          <div class="karyawan-avatar ${getAvatarClass(k.divisi)}">${getInisial(k.nama)}</div>
          <div>
            <div class="karyawan-name">${k.nama}</div>
            <div class="karyawan-nip">${k.nip}</div>
          </div>
        </div>
      </td>
      <td><span class="divisi-badge">${k.divisi}</span></td>
      <td><strong>${k.hadir}</strong></td>
      <td>${k.terlambat}</td>
      <td>${k.izin}</td>
      <td>${k.absen}</td>
      <td><span class="${getPctClass(k.pct)}">${k.pct}%</span></td>
      <td>
        <button class="btn-detail btn-open-detail" data-id="${k.id}">
          <i class="bi bi-search"></i> Detail
        </button>
      </td>
    `;
    tbody.appendChild(tr);
  });

  // Pagination info
  document.getElementById('pagination-info').textContent =
    `Menampilkan ${start + 1}–${Math.min(start + perPage, filtered.length)} dari ${filtered.length} karyawan`;

  renderPagination(totalPages);

  // Event tombol detail
  tbody.querySelectorAll('.btn-open-detail').forEach(btn => {
    btn.addEventListener('click', () => bukaModalDetail(parseInt(btn.dataset.id)));
  });
}

// ─────────────────────────────────────────────
// 6. PAGINATION
// ─────────────────────────────────────────────
function renderPagination(totalPages) {
  const container = document.getElementById('pagination-btns');
  container.innerHTML = '';

  // Prev
  const btnPrev = document.createElement('button');
  btnPrev.className = 'btn-page';
  btnPrev.innerHTML = '<i class="bi bi-chevron-left"></i>';
  btnPrev.disabled = currentPage === 1;
  btnPrev.addEventListener('click', () => { currentPage--; renderTabel(); });
  container.appendChild(btnPrev);

  // Page numbers
  for (let i = 1; i <= totalPages; i++) {
    const btn = document.createElement('button');
    btn.className = `btn-page${i === currentPage ? ' active' : ''}`;
    btn.textContent = i;
    btn.addEventListener('click', () => { currentPage = i; renderTabel(); });
    container.appendChild(btn);
  }

  // Next
  const btnNext = document.createElement('button');
  btnNext.className = 'btn-page';
  btnNext.innerHTML = '<i class="bi bi-chevron-right"></i>';
  btnNext.disabled = currentPage === totalPages;
  btnNext.addEventListener('click', () => { currentPage++; renderTabel(); });
  container.appendChild(btnNext);
}

// ─────────────────────────────────────────────
// 7. MODAL DETAIL
// ─────────────────────────────────────────────
const bsModalDetail = new bootstrap.Modal(document.getElementById('modalDetail'));

function bukaModalDetail(id) {
  const k = dataKaryawan.find(x => x.id === id);
  if (!k) return;

  document.getElementById('modalDetailLabel').textContent = `Detail Kehadiran – ${k.nama}`;

  document.getElementById('modal-detail-body').innerHTML = `
    <!-- Info Karyawan -->
    <div class="d-flex align-items-center gap-3 mb-4">
      <div class="karyawan-avatar ${getAvatarClass(k.divisi)}" style="width:52px;height:52px;font-size:1rem;">
        ${getInisial(k.nama)}
      </div>
      <div>
        <div style="font-size:1.05rem;font-weight:800;color:var(--text);">${k.nama}</div>
        <div style="font-size:.8rem;color:var(--muted);">${k.nip} &bull; ${k.divisi}</div>
      </div>
    </div>

    <!-- Stat Grid -->
    <div class="detail-grid">
      <div class="detail-stat-box">
        <div class="detail-stat-number" style="color:var(--green);">${k.hadir}</div>
        <div class="detail-stat-label">Hari Hadir</div>
      </div>
      <div class="detail-stat-box">
        <div class="detail-stat-number" style="color:#f59e0b;">${k.terlambat}</div>
        <div class="detail-stat-label">Terlambat</div>
      </div>
      <div class="detail-stat-box">
        <div class="detail-stat-number" style="color:var(--navy);">${k.izin}</div>
        <div class="detail-stat-label">Izin Disetujui</div>
      </div>
      <div class="detail-stat-box">
        <div class="detail-stat-number" style="color:#e03b3b;">${k.absen}</div>
        <div class="detail-stat-label">Tidak Hadir (Alpha)</div>
      </div>
      <div class="detail-stat-box">
        <div class="detail-stat-number ${getPctClass(k.pct)}">${k.pct}%</div>
        <div class="detail-stat-label">% Kehadiran</div>
      </div>
      <div class="detail-stat-box">
        <div class="detail-stat-number" style="color:var(--text);">${k.hadir + k.izin}</div>
        <div class="detail-stat-label">Total Hari Kerja</div>
      </div>
    </div>

    <!-- Info tambahan -->
    <div style="background:var(--blue-light);border-radius:10px;padding:14px 16px;">
      <div class="detail-info-row">
        <span>Periode Laporan</span>
        <span>${filterBulan}</span>
      </div>
      <div class="detail-info-row">
        <span>Divisi</span>
        <span>${k.divisi}</span>
      </div>
      <div class="detail-info-row">
        <span>Status Kehadiran</span>
        <span class="${getPctClass(k.pct)}">
          ${k.pct >= 90 ? 'Sangat Baik' : k.pct >= 75 ? 'Cukup Baik' : 'Perlu Perhatian'}
        </span>
      </div>
    </div>
  `;

  bsModalDetail.show();
}

// ─────────────────────────────────────────────
// 8. FILTER BULAN
// ─────────────────────────────────────────────
document.querySelectorAll('.filter-bulan').forEach(item => {
  item.addEventListener('click', function (e) {
    e.preventDefault();
    filterBulan = this.dataset.value;
    document.getElementById('filterBulanLabel').textContent = filterBulan;
    document.getElementById('table-title').textContent = `Rekap per Karyawan – ${filterBulan}`;
    currentPage = 1;
    renderTabel();
  });
});

// ─────────────────────────────────────────────
// 9. FILTER DIVISI
// ─────────────────────────────────────────────
document.querySelectorAll('.filter-divisi').forEach(item => {
  item.addEventListener('click', function (e) {
    e.preventDefault();
    filterDivisi = this.dataset.value;
    document.getElementById('filterDivisiLabel').textContent = this.textContent;
    currentPage = 1;
    renderTabel();
  });
});

// ─────────────────────────────────────────────
// 10. SEARCH
// ─────────────────────────────────────────────
document.getElementById('searchInput').addEventListener('input', function () {
  searchQuery = this.value.toLowerCase().trim();
  currentPage = 1;
  renderTabel();
});

// ─────────────────────────────────────────────
// 11. EXPORT PDF (print halaman)
// ─────────────────────────────────────────────
document.getElementById('btnExport').addEventListener('click', () => {
  window.print();
});

// ─────────────────────────────────────────────
// 12. SIDEBAR ACTIVE STATE
// ─────────────────────────────────────────────
document.querySelectorAll('.sidebar-item').forEach(item => {
  item.addEventListener('click', function (e) {
    e.preventDefault();
    document.querySelectorAll('.sidebar-item').forEach(el => el.classList.remove('active'));
    this.classList.add('active');
  });
});

// ─────────────────────────────────────────────
// 13. INIT
// ─────────────────────────────────────────────
renderTabel();
