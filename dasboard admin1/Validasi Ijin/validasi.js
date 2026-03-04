/* ═══════════════════════════════════════════
   validasi.js – CV. NAFIHAKA Creative
   Fitur: Clock, Render Kartu, Search, Filter,
          Tolak/Setujui per kartu, Bulk Aksi, Modal
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
// 2. DATA PENGAJUAN IZIN
// ─────────────────────────────────────────────
let dataPengajuan = [
  {
    id: 1,
    nama: 'Karezu Shiro',
    inisial: 'KS',
    dept: 'HRD – HR Specialist',
    waktu: '2 jam lalu',
    jenis: 'sakit',
    urgent: true,
    tanggal: '25 – 27 Feb 2026',
    durasi: '2 Hari',
    lampiran: 'Surat Dokter',
    alasan: '" Demam tinggi dan perlu istirahat, sudah konsultasi ke dokter"',
    status: 'pending',
  },
  {
    id: 2,
    nama: 'Rania Putri',
    inisial: 'RP',
    dept: 'Marketing – Content Creator',
    waktu: '3 jam lalu',
    jenis: 'cuti',
    urgent: false,
    tanggal: '28 Feb – 1 Mar 2026',
    durasi: '2 Hari',
    lampiran: 'Formulir Cuti',
    alasan: '" Keperluan keluarga yang tidak dapat ditunda"',
    status: 'pending',
  },
  {
    id: 3,
    nama: 'Dimas Arya',
    inisial: 'DA',
    dept: 'IT – Frontend Developer',
    waktu: '5 jam lalu',
    jenis: 'dinas',
    urgent: true,
    tanggal: '26 Feb 2026',
    durasi: '1 Hari',
    lampiran: 'Surat Tugas',
    alasan: '" Pertemuan klien di Jakarta untuk presentasi proyek baru"',
    status: 'pending',
  },
  {
    id: 4,
    nama: 'Siti Aulia',
    inisial: 'SA',
    dept: 'Finance – Accounting Staff',
    waktu: '1 hari lalu',
    jenis: 'keperluan',
    urgent: false,
    tanggal: '27 Feb 2026',
    durasi: '1 Hari',
    lampiran: null,
    alasan: '" Mengurus administrasi kependudukan yang sudah mendesak"',
    status: 'pending',
  },
  {
    id: 5,
    nama: 'Budi Santoso',
    inisial: 'BS',
    dept: 'Operations – Logistics',
    waktu: '1 hari lalu',
    jenis: 'sakit',
    urgent: false,
    tanggal: '25 Feb 2026',
    durasi: '1 Hari',
    lampiran: 'Surat Dokter',
    alasan: '" Flu berat disertai batuk, disarankan dokter untuk istirahat"',
    status: 'pending',
  },
  {
    id: 6,
    nama: 'Hana Wijaya',
    inisial: 'HW',
    dept: 'Design – UI/UX Designer',
    waktu: '2 hari lalu',
    jenis: 'cuti',
    urgent: false,
    tanggal: '1 – 3 Mar 2026',
    durasi: '3 Hari',
    lampiran: 'Formulir Cuti',
    alasan: '" Liburan tahunan yang sudah direncanakan sejak lama"',
    status: 'pending',
  },
  {
    id: 7,
    nama: 'Ahmad Fauzi',
    inisial: 'AF',
    dept: 'Sales – Account Manager',
    waktu: '2 hari lalu',
    jenis: 'dinas',
    urgent: true,
    tanggal: '26 – 27 Feb 2026',
    durasi: '2 Hari',
    lampiran: 'Surat Tugas',
    alasan: '" Kunjungan klien prioritas di Surabaya untuk negosiasi kontrak"',
    status: 'pending',
  },
  {
    id: 8,
    nama: 'Maya Lestari',
    inisial: 'ML',
    dept: 'HR – Recruitment Staff',
    waktu: '3 hari lalu',
    jenis: 'sakit',
    urgent: false,
    tanggal: '25 Feb 2026',
    durasi: '1 Hari',
    lampiran: 'Surat Dokter',
    alasan: '" Migrain berat, tidak bisa bekerja di depan layar"',
    status: 'pending',
  },
  {
    id: 9,
    nama: 'Rizki Maulana',
    inisial: 'RM',
    dept: 'IT – Backend Developer',
    waktu: '3 hari lalu',
    jenis: 'keperluan',
    urgent: false,
    tanggal: '28 Feb 2026',
    durasi: '1 Hari',
    lampiran: null,
    alasan: '" Acara pernikahan saudara di luar kota"',
    status: 'pending',
  },
  {
    id: 10,
    nama: 'Dewi Kartika',
    inisial: 'DK',
    dept: 'Finance – Tax Consultant',
    waktu: '4 hari lalu',
    jenis: 'cuti',
    urgent: false,
    tanggal: '2 – 4 Mar 2026',
    durasi: '3 Hari',
    lampiran: 'Formulir Cuti',
    alasan: '" Istirahat tahunan sesuai hak cuti yang belum diambil"',
    status: 'pending',
  },
];

// State filter & search
let filterAktif = 'semua';
let searchQuery = '';

// State modal
let pendingAction = null; // { type: 'tolak'|'setujui'|'tolakSemua'|'setujuiSemua', id? }


// ─────────────────────────────────────────────
// 3. HELPERS
// ─────────────────────────────────────────────
const LABEL_JENIS = {
  sakit:     'Sakit',
  cuti:      'Cuti',
  dinas:     'Dinas Luar',
  keperluan: 'Keperluan Pribadi',
};

function getTagClass(jenis) {
  const map = { sakit: 'tag-sakit', cuti: 'tag-cuti', dinas: 'tag-dinas', keperluan: 'tag-keperluan' };
  return map[jenis] || 'tag-sakit';
}

function getTagDot(jenis) {
  const dots = { sakit: '●', cuti: '●', dinas: '●', keperluan: '●' };
  return dots[jenis] || '●';
}

function countPending() {
  return dataPengajuan.filter(p => p.status === 'pending').length;
}

function updateBadge() {
  const n = countPending();
  document.getElementById('badge-count').textContent = n;
  document.getElementById('subtitle-count').textContent =
    `${n} Pengajuan menunggu persetujuan`;
}


// ─────────────────────────────────────────────
// 4. RENDER KARTU
// ─────────────────────────────────────────────
function renderKartu() {
  const list = document.getElementById('pengajuan-list');
  const emptyState = document.getElementById('empty-state');

  // Filter + Search
  const filtered = dataPengajuan.filter(p => {
    const matchFilter = filterAktif === 'semua' || p.jenis === filterAktif;
    const matchSearch = p.nama.toLowerCase().includes(searchQuery) ||
                        p.dept.toLowerCase().includes(searchQuery) ||
                        p.alasan.toLowerCase().includes(searchQuery);
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
    card.style.animationDelay = `${idx * 0.06}s`;

    // Status: jika sudah diproses tampilkan badge status, bukan tombol
    const actionHTML = p.status === 'pending'
      ? `<button class="btn-tolak btn-action-tolak" data-id="${p.id}">
           <i class="bi bi-x-lg"></i> Tolak
         </button>
         <button class="btn-setujui btn-action-setujui" data-id="${p.id}">
           <i class="bi bi-check2-square"></i> Setujui
         </button>`
      : p.status === 'disetujui'
        ? `<span class="status-badge status-disetujui"><i class="bi bi-check-circle-fill"></i> Disetujui</span>`
        : `<span class="status-badge status-ditolak"><i class="bi bi-x-circle-fill"></i> Ditolak</span>`;

    const lampiranHTML = p.lampiran
      ? `<span class="detail-value">
           <i class="bi bi-paperclip"></i> ${p.lampiran}
         </span>`
      : `<span class="detail-value" style="color:var(--muted);font-weight:500;">Tidak ada</span>`;

    card.innerHTML = `
      <!-- Header Row -->
      <div class="card-header-row">
        <div class="card-avatar">${p.inisial}</div>
        <div class="flex-grow-1">
          <div class="card-name">${p.nama}</div>
          <div class="card-dept">${p.dept} &bull; ${p.waktu}</div>
        </div>
        <span class="tag ${getTagClass(p.jenis)}">${getTagDot(p.jenis)} ${LABEL_JENIS[p.jenis]}</span>
        ${p.urgent ? '<span class="tag tag-urgent">URGENT</span>' : ''}
        <div class="d-flex gap-2 ms-1">
          ${actionHTML}
        </div>
      </div>

      <!-- Detail Row -->
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

      <!-- Alasan -->
      <div class="card-alasan">${p.alasan}</div>
    `;

    list.appendChild(card);
  });

  // Pasang event listener tombol per kartu
  list.querySelectorAll('.btn-action-tolak').forEach(btn => {
    btn.addEventListener('click', () => bukaModal('tolak', parseInt(btn.dataset.id)));
  });
  list.querySelectorAll('.btn-action-setujui').forEach(btn => {
    btn.addEventListener('click', () => bukaModal('setujui', parseInt(btn.dataset.id)));
  });
}


// ─────────────────────────────────────────────
// 5. MODAL KONFIRMASI
// ─────────────────────────────────────────────
const modalEl     = document.getElementById('modalKonfirmasi');
const modalMsg    = document.getElementById('modal-message');
const btnKonfirm  = document.getElementById('btnKonfirmAksi');
const bsModal     = new bootstrap.Modal(modalEl);

function bukaModal(type, id) {
  pendingAction = { type, id };

  if (type === 'tolak') {
    const p = dataPengajuan.find(x => x.id === id);
    modalMsg.textContent = `Tolak pengajuan izin dari ${p.nama}?`;
    btnKonfirm.className = 'btn-modal-konfirm danger';
    btnKonfirm.textContent = 'Ya, Tolak';
  } else if (type === 'setujui') {
    const p = dataPengajuan.find(x => x.id === id);
    modalMsg.textContent = `Setujui pengajuan izin dari ${p.nama}?`;
    btnKonfirm.className = 'btn-modal-konfirm';
    btnKonfirm.textContent = 'Ya, Setujui';
  } else if (type === 'tolakSemua') {
    modalMsg.textContent = `Tolak SEMUA pengajuan yang sedang pending?`;
    btnKonfirm.className = 'btn-modal-konfirm danger';
    btnKonfirm.textContent = 'Ya, Tolak Semua';
  } else if (type === 'setujuiSemua') {
    modalMsg.textContent = `Setujui SEMUA pengajuan yang sedang pending?`;
    btnKonfirm.className = 'btn-modal-konfirm';
    btnKonfirm.textContent = 'Ya, Setujui Semua';
  }

  bsModal.show();
}

btnKonfirm.addEventListener('click', () => {
  if (!pendingAction) return;
  const { type, id } = pendingAction;

  if (type === 'tolak') {
    dataPengajuan = dataPengajuan.map(p => p.id === id ? { ...p, status: 'ditolak' } : p);
  } else if (type === 'setujui') {
    dataPengajuan = dataPengajuan.map(p => p.id === id ? { ...p, status: 'disetujui' } : p);
  } else if (type === 'tolakSemua') {
    dataPengajuan = dataPengajuan.map(p => p.status === 'pending' ? { ...p, status: 'ditolak' } : p);
  } else if (type === 'setujuiSemua') {
    dataPengajuan = dataPengajuan.map(p => p.status === 'pending' ? { ...p, status: 'disetujui' } : p);
  }

  pendingAction = null;
  bsModal.hide();
  updateBadge();
  renderKartu();
});


// ─────────────────────────────────────────────
// 6. SEARCH
// ─────────────────────────────────────────────
document.getElementById('searchInput').addEventListener('input', function () {
  searchQuery = this.value.toLowerCase().trim();
  renderKartu();
});


// ─────────────────────────────────────────────
// 7. FILTER DROPDOWN
// ─────────────────────────────────────────────
document.querySelectorAll('.filter-option').forEach(item => {
  item.addEventListener('click', function (e) {
    e.preventDefault();
    filterAktif = this.dataset.value;
    document.getElementById('filterLabel').textContent = this.textContent;
    renderKartu();
  });
});


// ─────────────────────────────────────────────
// 8. BULK ACTIONS
// ─────────────────────────────────────────────
document.getElementById('btnTolakSemua').addEventListener('click', () => {
  bukaModal('tolakSemua');
});

document.getElementById('btnSetujuiSemua').addEventListener('click', () => {
  bukaModal('setujuiSemua');
});


// ─────────────────────────────────────────────
// 9. SIDEBAR ACTIVE STATE
// ─────────────────────────────────────────────
document.querySelectorAll('.sidebar-item').forEach(item => {
  item.addEventListener('click', function (e) {
    e.preventDefault();
    document.querySelectorAll('.sidebar-item').forEach(el => el.classList.remove('active'));
    this.classList.add('active');
  });
});


// ─────────────────────────────────────────────
// 10. INIT
// ─────────────────────────────────────────────
updateBadge();
renderKartu();
