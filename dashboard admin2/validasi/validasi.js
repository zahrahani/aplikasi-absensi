/* ═══════════════════════════════════════════
   validasi.js – CV. NAFIHAKA Creative
   Clock · Render Kartu · Search · Filter
   Tolak/Setujui · Bulk Aksi · Modal · Mobile Sidebar
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
// 2. DATA PENGAJUAN IZIN
// ─────────────────────────────────────────────
let dataPengajuan = [
  {
    id: 1, nama: 'Karezu Shiro',    inisial: 'KS', divisi: 'hrd',
    dept: 'HRD – HR Specialist',        waktu: '2 jam lalu',
    jenis: 'sakit',     urgent: true,
    tanggal: '25 – 27 Feb 2026',  durasi: '2 Hari',
    lampiran: 'Surat Dokter',
    alasan: '"Demam tinggi dan perlu istirahat, sudah konsultasi ke dokter"',
    status: 'pending',
  },
  {
    id: 2, nama: 'Rania Putri',     inisial: 'RP', divisi: 'marketing',
    dept: 'Marketing – Content Creator', waktu: '3 jam lalu',
    jenis: 'cuti',      urgent: false,
    tanggal: '28 Feb – 1 Mar 2026', durasi: '2 Hari',
    lampiran: 'Formulir Cuti',
    alasan: '"Keperluan keluarga yang tidak dapat ditunda"',
    status: 'pending',
  },
  {
    id: 3, nama: 'Dimas Arya',      inisial: 'DA', divisi: 'it',
    dept: 'IT – Frontend Developer',     waktu: '5 jam lalu',
    jenis: 'dinas',     urgent: true,
    tanggal: '26 Feb 2026',       durasi: '1 Hari',
    lampiran: 'Surat Tugas',
    alasan: '"Pertemuan klien di Jakarta untuk presentasi proyek baru"',
    status: 'pending',
  },
  {
    id: 4, nama: 'Siti Aulia',      inisial: 'SA', divisi: 'finance',
    dept: 'Finance – Accounting Staff',  waktu: '1 hari lalu',
    jenis: 'keperluan', urgent: false,
    tanggal: '27 Feb 2026',       durasi: '1 Hari',
    lampiran: null,
    alasan: '"Mengurus administrasi kependudukan yang sudah mendesak"',
    status: 'pending',
  },
  {
    id: 5, nama: 'Budi Santoso',    inisial: 'BS', divisi: 'operations',
    dept: 'Operations – Logistics',     waktu: '1 hari lalu',
    jenis: 'sakit',     urgent: false,
    tanggal: '25 Feb 2026',       durasi: '1 Hari',
    lampiran: 'Surat Dokter',
    alasan: '"Flu berat disertai batuk, disarankan dokter untuk istirahat"',
    status: 'pending',
  },
  {
    id: 6, nama: 'Hana Wijaya',     inisial: 'HW', divisi: 'design',
    dept: 'Design – UI/UX Designer',    waktu: '2 hari lalu',
    jenis: 'cuti',      urgent: false,
    tanggal: '1 – 3 Mar 2026',    durasi: '3 Hari',
    lampiran: 'Formulir Cuti',
    alasan: '"Liburan tahunan yang sudah direncanakan sejak lama"',
    status: 'pending',
  },
  {
    id: 7, nama: 'Ahmad Fauzi',     inisial: 'AF', divisi: 'sales',
    dept: 'Sales – Account Manager',    waktu: '2 hari lalu',
    jenis: 'dinas',     urgent: true,
    tanggal: '26 – 27 Feb 2026',  durasi: '2 Hari',
    lampiran: 'Surat Tugas',
    alasan: '"Kunjungan klien prioritas di Surabaya untuk negosiasi kontrak"',
    status: 'pending',
  },
  {
    id: 8, nama: 'Maya Lestari',    inisial: 'ML', divisi: 'hr',
    dept: 'HR – Recruitment Staff',     waktu: '3 hari lalu',
    jenis: 'sakit',     urgent: false,
    tanggal: '25 Feb 2026',       durasi: '1 Hari',
    lampiran: 'Surat Dokter',
    alasan: '"Migrain berat, tidak bisa bekerja di depan layar"',
    status: 'pending',
  },
  {
    id: 9, nama: 'Rizki Maulana',   inisial: 'RM', divisi: 'it',
    dept: 'IT – Backend Developer',     waktu: '3 hari lalu',
    jenis: 'keperluan', urgent: false,
    tanggal: '28 Feb 2026',       durasi: '1 Hari',
    lampiran: null,
    alasan: '"Acara pernikahan saudara di luar kota"',
    status: 'pending',
  },
  {
    id: 10, nama: 'Dewi Kartika',   inisial: 'DK', divisi: 'finance',
    dept: 'Finance – Tax Consultant',   waktu: '4 hari lalu',
    jenis: 'cuti',      urgent: false,
    tanggal: '2 – 4 Mar 2026',    durasi: '3 Hari',
    lampiran: 'Formulir Cuti',
    alasan: '"Istirahat tahunan sesuai hak cuti yang belum diambil"',
    status: 'pending',
  },
];

// ─────────────────────────────────────────────
// 3. STATE
// ─────────────────────────────────────────────
let filterAktif   = 'semua';
let searchQuery   = '';
let pendingAction = null; // { type, id? }

// ─────────────────────────────────────────────
// 4. HELPERS
// ─────────────────────────────────────────────
const LABEL_JENIS = {
  sakit:     'Sakit',
  cuti:      'Cuti',
  dinas:     'Dinas Luar',
  keperluan: 'Keperluan Pribadi',
};

function getTagClass(jenis) {
  return { sakit:'tag-sakit', cuti:'tag-cuti', dinas:'tag-dinas', keperluan:'tag-keperluan' }[jenis] || 'tag-sakit';
}

function countPending() {
  return dataPengajuan.filter(p => p.status === 'pending').length;
}

function updateBadge() {
  const n = countPending();
  const badgeEl    = document.getElementById('badge-count');
  const subtitleEl = document.getElementById('subtitle-count');
  if (badgeEl)    badgeEl.textContent    = n;
  if (subtitleEl) subtitleEl.textContent = `${n} Pengajuan menunggu persetujuan`;
  if (window.AppBridge) window.AppBridge.updateBadge(n);
}

// ─────────────────────────────────────────────
// 5. RENDER KARTU
// ─────────────────────────────────────────────
function renderKartu() {
  const list       = document.getElementById('pengajuan-list');
  const emptyState = document.getElementById('empty-state');

  const filtered = dataPengajuan.filter(p => {
    const matchFilter = filterAktif === 'semua' || p.jenis === filterAktif;
    const q = searchQuery.toLowerCase();
    const matchSearch = !q ||
      p.nama.toLowerCase().includes(q)   ||
      p.dept.toLowerCase().includes(q)   ||
      p.alasan.toLowerCase().includes(q) ||
      LABEL_JENIS[p.jenis].toLowerCase().includes(q);
    return matchFilter && matchSearch;
  });

  list.innerHTML = '';

  if (filtered.length === 0) {
    emptyState.classList.remove('d-none');
    return;
  }
  emptyState.classList.add('d-none');

  filtered.forEach((p, idx) => {
    const card = document.createElement('div');
    card.className = `pengajuan-card${p.urgent ? ' urgent-card' : ''}`;
    card.dataset.id = p.id;
    card.style.animationDelay = `${idx * 0.055}s`;

    // Aksi: tombol jika pending, badge jika sudah diproses
    let actionHTML = '';
    if (p.status === 'pending') {
      actionHTML = `
        <button class="btn-tolak btn-act-tolak" data-id="${p.id}">
          <i class="bi bi-x-lg"></i> Tolak
        </button>
        <button class="btn-setujui btn-act-setujui" data-id="${p.id}">
          <i class="bi bi-check2-square"></i> Setujui
        </button>`;
    } else if (p.status === 'disetujui') {
      actionHTML = `<span class="status-badge status-disetujui">
        <i class="bi bi-check-circle-fill"></i> Disetujui</span>`;
    } else {
      actionHTML = `<span class="status-badge status-ditolak">
        <i class="bi bi-x-circle-fill"></i> Ditolak</span>`;
    }

    // Lampiran
    const lampiranHTML = p.lampiran
      ? `<div class="detail-value">
           <i class="bi bi-paperclip"></i> ${p.lampiran}
         </div>`
      : `<div class="detail-value" style="color:var(--muted);font-weight:500;font-style:normal;">
           Tidak ada
         </div>`;

    card.innerHTML = `
      <!-- ── Header ── -->
      <div class="card-header-row">
        <div class="card-avatar av-${p.divisi}">${p.inisial}</div>
        <div class="flex-grow-1 min-w-0">
          <div class="card-name">${p.nama}</div>
          <div class="card-dept">${p.dept} &bull; ${p.waktu}</div>
        </div>
        <div class="card-spacer"></div>

        <!-- Tag jenis + urgent -->
        <span class="tag ${getTagClass(p.jenis)}">
          <span class="tag-dot"></span>${LABEL_JENIS[p.jenis]}
        </span>
        ${p.urgent ? '<span class="tag tag-urgent">URGENT</span>' : ''}

        <!-- Tombol aksi -->
        <div class="card-actions">${actionHTML}</div>
      </div>

      <!-- ── Detail Tanggal · Durasi · Lampiran ── -->
      <div class="card-detail-row">
        <div class="detail-col">
          <label>Tanggal</label>
          <div class="detail-value">${p.tanggal}</div>
        </div>
        <div class="detail-col">
          <label>Durasi</label>
          <div class="detail-value">${p.durasi}</div>
        </div>
        <div class="detail-col lampiran">
          <label>Lampiran</label>
          ${lampiranHTML}
        </div>
      </div>

      <!-- ── Alasan ── -->
      <div class="card-alasan">${p.alasan}</div>
    `;

    list.appendChild(card);
  });

  // Event listener tombol per kartu
  list.querySelectorAll('.btn-act-tolak').forEach(btn => {
    btn.addEventListener('click', () => bukaModal('tolak', parseInt(btn.dataset.id)));
  });
  list.querySelectorAll('.btn-act-setujui').forEach(btn => {
    btn.addEventListener('click', () => bukaModal('setujui', parseInt(btn.dataset.id)));
  });
}

// ─────────────────────────────────────────────
// 6. MODAL KONFIRMASI
// ─────────────────────────────────────────────
let bsModal;

function bukaModal(type, id) {
  pendingAction = { type, id };

  const msgEl   = document.getElementById('modal-message');
  const titleEl = document.getElementById('modalLabel');
  const konfirm = document.getElementById('btnKonfirmAksi');

  const p = id ? dataPengajuan.find(x => x.id === id) : null;

  const configs = {
    tolak:        { title: 'Konfirmasi Tolak',    msg: `Tolak pengajuan izin dari <strong>${p?.nama}</strong>?`,    cls: 'danger',  label: 'Ya, Tolak' },
    setujui:      { title: 'Konfirmasi Setujui',  msg: `Setujui pengajuan izin dari <strong>${p?.nama}</strong>?`,  cls: 'success', label: 'Ya, Setujui' },
    tolakSemua:   { title: 'Tolak Semua Pending', msg: 'Tolak <strong>SEMUA</strong> pengajuan yang sedang pending?', cls: 'danger', label: 'Ya, Tolak Semua' },
    setujuiSemua: { title: 'Setujui Semua',       msg: 'Setujui <strong>SEMUA</strong> pengajuan yang sedang pending?', cls: 'success', label: 'Ya, Setujui Semua' },
  };

  const cfg = configs[type];
  if (!cfg) return;

  titleEl.textContent  = cfg.title;
  msgEl.innerHTML      = cfg.msg;
  konfirm.className    = `btn-modal-konfirm ${cfg.cls}`;
  konfirm.textContent  = cfg.label;

  bsModal.show();
}

// Eksekusi konfirmasi
function handleKonfirm() {
  if (!pendingAction) return;
  const { type, id } = pendingAction;

  const update = (fn) => { dataPengajuan = dataPengajuan.map(fn); };

  if      (type === 'tolak')        update(p => p.id === id          ? {...p, status:'ditolak'}   : p);
  else if (type === 'setujui')      update(p => p.id === id          ? {...p, status:'disetujui'} : p);
  else if (type === 'tolakSemua')   update(p => p.status === 'pending' ? {...p, status:'ditolak'}   : p);
  else if (type === 'setujuiSemua') update(p => p.status === 'pending' ? {...p, status:'disetujui'} : p);

  pendingAction = null;
  bsModal.hide();
  updateBadge();
  renderKartu();
}

// ─────────────────────────────────────────────
// 7. SEARCH
// ─────────────────────────────────────────────
document.getElementById('searchInput').addEventListener('input', function () {
  searchQuery = this.value.trim();
  renderKartu();
});

// ─────────────────────────────────────────────
// 8. FILTER DROPDOWN
// ─────────────────────────────────────────────
document.querySelectorAll('.filter-option').forEach(item => {
  item.addEventListener('click', function (e) {
    e.preventDefault();
    filterAktif = this.dataset.value;
    const label = document.getElementById('filterLabel');
    if (label) label.querySelector('span').textContent = this.textContent.trim();
    renderKartu();
  });
});

// ─────────────────────────────────────────────
// 9. BULK ACTIONS
// ─────────────────────────────────────────────
document.getElementById('btnTolakSemua').addEventListener('click', () => bukaModal('tolakSemua'));
document.getElementById('btnSetujuiSemua').addEventListener('click', () => bukaModal('setujuiSemua'));

// ─────────────────────────────────────────────
// 10. MOBILE SIDEBAR
// ─────────────────────────────────────────────
function initMobileSidebar() {
  const sidebar  = document.getElementById('sidebar');
  const overlay  = document.getElementById('sidebarOverlay');
  const btnOpen  = document.getElementById('btnMenuMobile');
  const btnClose = document.getElementById('sidebarToggle');

  const open  = () => { sidebar.classList.add('open'); overlay.classList.add('open'); document.body.style.overflow = 'hidden'; };
  const close = () => { sidebar.classList.remove('open'); overlay.classList.remove('open'); document.body.style.overflow = ''; };

  if (btnOpen)  btnOpen.addEventListener('click', open);
  if (btnClose) btnClose.addEventListener('click', close);
  if (overlay)  overlay.addEventListener('click', close);

  window.addEventListener('resize', () => { if (window.innerWidth >= 992) close(); });
}

// ─────────────────────────────────────────────
// 11. INIT
// ─────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  bsModal = new bootstrap.Modal(document.getElementById('modalKonfirmasi'));
  document.getElementById('btnKonfirmAksi').addEventListener('click', handleKonfirm);

  initMobileSidebar();
  updateBadge();
  renderKartu();
});
