/* ═══════════════════════════════════════════
   rekap.js – CV. NAFIHAKA Creative
   Clock · Filter · Tabel · Pagination
   Modal Detail · Export · Sidebar Mobile
════════════════════════════════════════════ */

'use strict';

// ─────────────────────────────────────────────
// 1. CLOCK & DATE
// ─────────────────────────────────────────────
const HARI  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
const BULAN = ['Januari','Februari','Maret','April','Mei','Juni',
               'Juli','Agustus','September','Oktober','November','Desember'];

function updateClock() {
  const now = new Date();
  const pad = n => String(n).padStart(2, '0');
  const clk = document.getElementById('topbar-clock');
  const dt  = document.getElementById('topbar-date');
  if (clk) clk.textContent =
    `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
  if (dt) dt.textContent =
    `${HARI[now.getDay()]}, ${now.getDate()} ${BULAN[now.getMonth()]} ${now.getFullYear()}`;
}
updateClock();
setInterval(updateClock, 1000);

// ─────────────────────────────────────────────
// 2. DATA KARYAWAN
// ─────────────────────────────────────────────
const dataKaryawan = [
  { id:1,  nama:'Karezu Shiro',    nip:'EM001', divisi:'HRD',        hadir:16, terlambat:1, izin:2, absen:0, pct:84  },
  { id:2,  nama:'Rania Putri',     nip:'EM002', divisi:'Marketing',  hadir:18, terlambat:2, izin:1, absen:0, pct:90  },
  { id:3,  nama:'Dimas Arya',      nip:'EM003', divisi:'IT',         hadir:15, terlambat:3, izin:1, absen:2, pct:79  },
  { id:4,  nama:'Siti Aulia',      nip:'EM004', divisi:'Finance',    hadir:19, terlambat:0, izin:0, absen:1, pct:95  },
  { id:5,  nama:'Budi Santoso',    nip:'EM005', divisi:'Operations', hadir:14, terlambat:4, izin:3, absen:1, pct:74  },
  { id:6,  nama:'Hana Wijaya',     nip:'EM006', divisi:'Design',     hadir:17, terlambat:1, izin:2, absen:0, pct:89  },
  { id:7,  nama:'Ahmad Fauzi',     nip:'EM007', divisi:'Sales',      hadir:20, terlambat:0, izin:0, absen:0, pct:100 },
  { id:8,  nama:'Maya Lestari',    nip:'EM008', divisi:'HRD',        hadir:13, terlambat:5, izin:4, absen:2, pct:68  },
  { id:9,  nama:'Rizki Maulana',   nip:'EM009', divisi:'IT',         hadir:16, terlambat:2, izin:1, absen:1, pct:84  },
  { id:10, nama:'Dewi Kartika',    nip:'EM010', divisi:'Finance',    hadir:18, terlambat:1, izin:1, absen:0, pct:92  },
  { id:11, nama:'Fajar Pratama',   nip:'EM011', divisi:'IT',         hadir:15, terlambat:2, izin:2, absen:1, pct:79  },
  { id:12, nama:'Nisa Rahayu',     nip:'EM012', divisi:'Marketing',  hadir:17, terlambat:3, izin:1, absen:0, pct:87  },
  { id:13, nama:'Andi Kurniawan',  nip:'EM013', divisi:'Operations', hadir:12, terlambat:6, izin:3, absen:4, pct:63  },
  { id:14, nama:'Lila Santika',    nip:'EM014', divisi:'Design',     hadir:19, terlambat:1, izin:0, absen:0, pct:97  },
  { id:15, nama:'Yusuf Hakim',     nip:'EM015', divisi:'Sales',      hadir:16, terlambat:2, izin:2, absen:1, pct:84  },
  { id:16, nama:'Citra Amalia',    nip:'EM016', divisi:'HRD',        hadir:18, terlambat:0, izin:1, absen:0, pct:93  },
  { id:17, nama:'Bagas Wicaksono', nip:'EM017', divisi:'IT',         hadir:14, terlambat:4, izin:2, absen:2, pct:74  },
  { id:18, nama:'Putri Andini',    nip:'EM018', divisi:'Finance',    hadir:20, terlambat:0, izin:0, absen:0, pct:100 },
  { id:19, nama:'Hendra Saputra',  nip:'EM019', divisi:'Sales',      hadir:15, terlambat:3, izin:2, absen:1, pct:79  },
  { id:20, nama:'Wulandari',       nip:'EM020', divisi:'Marketing',  hadir:17, terlambat:1, izin:2, absen:0, pct:88  },
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
const avatarMap = {
  HRD:        'avatar-hrd',
  IT:         'avatar-it',
  Finance:    'avatar-finance',
  Marketing:  'avatar-marketing',
  Operations: 'avatar-operations',
  Design:     'avatar-design',
  Sales:      'avatar-sales',
};

function getAvatarClass(divisi) { return avatarMap[divisi] || 'avatar-hrd'; }

function getInisial(nama) {
  return nama.trim().split(/\s+/).map(w => w[0]).slice(0, 2).join('').toUpperCase();
}

function getPctClass(pct) {
  if (pct >= 90) return 'pct-high';
  if (pct >= 75) return 'pct-medium';
  return 'pct-low';
}

function getPctStatus(pct) {
  if (pct >= 90) return 'Sangat Baik';
  if (pct >= 75) return 'Cukup Baik';
  return 'Perlu Perhatian';
}

function getFiltered() {
  const q = searchQuery.toLowerCase();
  return dataKaryawan.filter(k => {
    const matchDiv = filterDivisi === 'semua' || k.divisi === filterDivisi;
    const matchQ   = !q ||
      k.nama.toLowerCase().includes(q) ||
      k.nip.toLowerCase().includes(q)  ||
      k.divisi.toLowerCase().includes(q);
    return matchDiv && matchQ;
  });
}

// ─────────────────────────────────────────────
// 5. RENDER TABEL
// ─────────────────────────────────────────────
function renderTabel() {
  const tbody      = document.getElementById('table-body');
  const emptyState = document.getElementById('empty-state');
  const paginInfo  = document.getElementById('pagination-info');
  const filtered   = getFiltered();

  tbody.innerHTML = '';

  if (filtered.length === 0) {
    emptyState.classList.remove('d-none');
    paginInfo.textContent = '';
    document.getElementById('pagination-btns').innerHTML = '';
    return;
  }
  emptyState.classList.add('d-none');

  // Hitung pagination
  const totalPages = Math.ceil(filtered.length / perPage);
  if (currentPage > totalPages) currentPage = 1;
  const start = (currentPage - 1) * perPage;
  const slice = filtered.slice(start, start + perPage);

  // Render baris
  slice.forEach((k, idx) => {
    const tr = document.createElement('tr');
    tr.style.animationDelay = `${idx * 0.04}s`;
    tr.innerHTML = `
      <td class="col-karyawan">
        <div class="karyawan-cell">
          <div class="karyawan-avatar ${getAvatarClass(k.divisi)}">${getInisial(k.nama)}</div>
          <div>
            <div class="karyawan-name">${k.nama}</div>
            <div class="karyawan-nip">${k.nip}</div>
          </div>
        </div>
      </td>
      <td class="col-divisi"><span class="divisi-badge">${k.divisi}</span></td>
      <td class="col-num"><strong>${k.hadir}</strong></td>
      <td class="col-num">${k.terlambat}</td>
      <td class="col-num">${k.izin}</td>
      <td class="col-num">${k.absen}</td>
      <td class="col-pct"><span class="${getPctClass(k.pct)}">${k.pct}%</span></td>
      <td class="col-detail">
        <button class="btn-detail btn-open-detail" data-id="${k.id}">
          <i class="bi bi-search"></i> Detail
        </button>
      </td>
    `;
    tbody.appendChild(tr);
  });

  // Pagination info
  paginInfo.textContent =
    `Menampilkan ${start + 1}–${Math.min(start + perPage, filtered.length)} dari ${filtered.length} karyawan`;

  renderPagination(totalPages);

  // Pasang event tombol detail
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

  const makeBtn = (content, page, isDisabled = false, isActive = false) => {
    const btn = document.createElement('button');
    btn.className = `btn-page${isActive ? ' active' : ''}`;
    btn.innerHTML = content;
    btn.disabled  = isDisabled;
    if (!isDisabled && !isActive) {
      btn.addEventListener('click', () => { currentPage = page; renderTabel(); });
    }
    return btn;
  };

  container.appendChild(makeBtn('<i class="bi bi-chevron-left"></i>', currentPage - 1, currentPage === 1));

  for (let i = 1; i <= totalPages; i++) {
    container.appendChild(makeBtn(i, i, i === currentPage, i === currentPage));
  }

  container.appendChild(makeBtn('<i class="bi bi-chevron-right"></i>', currentPage + 1, currentPage === totalPages));
}

// ─────────────────────────────────────────────
// 7. MODAL DETAIL
// ─────────────────────────────────────────────
let bsModalDetail;

function bukaModalDetail(id) {
  const k = dataKaryawan.find(x => x.id === id);
  if (!k) return;

  document.getElementById('modalDetailLabel').textContent = `Detail Kehadiran – ${k.nama}`;

  document.getElementById('modal-detail-body').innerHTML = `
    <div class="d-flex align-items-center gap-3 mb-4">
      <div class="karyawan-avatar ${getAvatarClass(k.divisi)}"
           style="width:52px;height:52px;font-size:1rem;border-radius:50%;">
        ${getInisial(k.nama)}
      </div>
      <div>
        <div style="font-size:1.05rem;font-weight:800;color:var(--text);">${k.nama}</div>
        <div style="font-size:.8rem;color:var(--muted);">${k.nip} &bull; ${k.divisi}</div>
      </div>
    </div>

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
        <div class="detail-stat-number" style="color:var(--red);">${k.absen}</div>
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

    <div style="background:var(--blue-light);border-radius:12px;padding:16px 18px;">
      <div class="detail-info-row">
        <span class="info-label">Periode Laporan</span>
        <span class="info-value">${filterBulan}</span>
      </div>
      <div class="detail-info-row">
        <span class="info-label">Divisi</span>
        <span class="info-value">${k.divisi}</span>
      </div>
      <div class="detail-info-row" style="margin-bottom:0;">
        <span class="info-label">Status Kehadiran</span>
        <span class="info-value ${getPctClass(k.pct)}">${getPctStatus(k.pct)}</span>
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
    const labelEl = document.getElementById('filterBulanLabel');
    if (labelEl) labelEl.querySelector('span').textContent = filterBulan;
    const titleEl = document.getElementById('table-title');
    if (titleEl) titleEl.textContent = `Rekap per Karyawan – ${filterBulan}`;
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
    const labelEl = document.getElementById('filterDivisiLabel');
    if (labelEl) labelEl.querySelector('span').textContent = this.textContent.trim();
    currentPage = 1;
    renderTabel();
  });
});

// ─────────────────────────────────────────────
// 10. SEARCH
// ─────────────────────────────────────────────
document.getElementById('searchInput').addEventListener('input', function () {
  searchQuery = this.value.trim();
  currentPage = 1;
  renderTabel();
});

// ─────────────────────────────────────────────
// 11. EXPORT PDF
// ─────────────────────────────────────────────
document.getElementById('btnExport').addEventListener('click', () => {
  window.print();
});

// ─────────────────────────────────────────────
// 12. MOBILE SIDEBAR TOGGLE
// ─────────────────────────────────────────────
function initMobileSidebar() {
  const sidebar   = document.getElementById('sidebar');
  const overlay   = document.getElementById('sidebarOverlay');
  const btnOpen   = document.getElementById('btnMenuMobile');
  const btnClose  = document.getElementById('sidebarToggle');

  function openSidebar() {
    sidebar.classList.add('open');
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeSidebar() {
    sidebar.classList.remove('open');
    overlay.classList.remove('open');
    document.body.style.overflow = '';
  }

  if (btnOpen)  btnOpen.addEventListener('click', openSidebar);
  if (btnClose) btnClose.addEventListener('click', closeSidebar);
  if (overlay)  overlay.addEventListener('click', closeSidebar);

  // Tutup sidebar saat resize ke desktop
  window.addEventListener('resize', () => {
    if (window.innerWidth >= 992) closeSidebar();
  });
}

// ─────────────────────────────────────────────
// 13. INIT
// ─────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  bsModalDetail = new bootstrap.Modal(document.getElementById('modalDetail'));
  initMobileSidebar();
  renderTabel();
});
