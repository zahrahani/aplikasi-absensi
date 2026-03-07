// Mengkatifkan mode ECMAScript 5+ agar penulisan kode pada javascript lebih ketat
'use strict';

// 1. REAL-TIME CLOCK & DATE
// Deklarasi konstanta hari untuk menampilkan hari
const HARI   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
// Deklarasi konstanta bulan untuk menampilkan bulan
const BULAN  = [
  'Januari','Februari','Maret','April','Mei','Juni',
  'Juli','Agustus','September','Oktober','November','Desember'
];


// Fungsi untuk update jam atau DateTimePicker
function updateClock() {
  // instansi objek Date untuk mendapatkan tanggal
  const now  = new Date();

  // Mulai menambahkan angka 0 setiap 1 digit dengan batas maksimal 2 karakter. Contoh 8 => 08 atau 22 => 22
  const pad  = (n) => String(n).padStart(2, '0');

  // Menambahkan Jam
  const jam = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
  document.getElementById('topbar-clock').textContent = jam;

  // Menambahkan Tanggal
  const tgl = `${HARI[now.getDay()]}, ${now.getDate()} ${BULAN[now.getMonth()]} ${now.getFullYear()}`;
  document.getElementById('topbar-date').textContent = tgl;
}

// Jalankan fungsi updateClock
updateClock();

// Menjalankan fungsi updateClock setiap 1000 milidetik sekali
setInterval(updateClock, 1000);


// 2. DATA KARYAWAN
// Menyiapkan data dummy karian dalam bentuk dictionary
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

// 3. STATE
// State awal berfungsi untuk menetapkan data awal rekap
// Bulan dimulai dari februari 2026
let filterBulan  = 'Februari 2026';
// Divisi yang di tampilkan semuanya
let filterDivisi = 'semua';
// Belum ada query pencarian
let searchQuery  = '';
// Dimulai dari halaman pertama
let currentPage  = 1;
// Setiap data perhalaman 8
const perPage    = 8;

// 4. HELPERS
// Berfungsi untuk mengatur class dari avatarnya guna membedakan css setiap avatar antar divisi
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

// Berfungsi menyiptakan inisial dari nama
function getInisial(nama) {
  // Membagi 2 string mengambil kata pertama lalu memotong string menjadi 2 karakter dan mengembalikan dalam huruf kapital
  return nama.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase();
}

// Berfungsi untuk mengatur tulisan persentase kehadiran
function getPctClass(pct) {
  // jika >= 90 diberi class pct-high
  if (pct >= 90) return 'pct-high';
  // jika >= 75 diberi class pct-medium
  if (pct >= 75) return 'pct-medium';
  // jika tidak memenuhi kedua itu diberikan class pct-low
  return 'pct-low';
}

// Berfungsi untuk membantu dalam query pencarian
function getFiltered() {
  // dari data dummy diberikan difilter
  return dataKaryawan.filter(k => {
    // filter divisi
    const matchDiv  = filterDivisi === 'semua' || k.divisi === filterDivisi;
    // Filter pencarian berdasarkan nama divisi dan nip
    const matchSrc  = k.nama.toLowerCase().includes(searchQuery) ||
                      k.nip.toLowerCase().includes(searchQuery)  ||
                      k.divisi.toLowerCase().includes(searchQuery);
    // Mengembalikan hasil filter dari pencarian divisi dan pencarian berdasarkan query
    return matchDiv && matchSrc;
  });
}

// 5. RENDER TABEL
// Render tabel untuk menampilkan seluruh data karyawan
function renderTabel() {
  // Mendapatkan elemen tabel yang nantinya akan di isi
  const tbody      = document.getElementById('table-body');
  const emptyState = document.getElementById('empty-state');
  const filtered   = getFiltered();
  // Mengosongkan table
  tbody.innerHTML = '';

  // Jika data karyawan kosong maka akan menampilkan bahwa tabel kosong
  if (filtered.length === 0) {
    emptyState.classList.remove('d-none');
    document.getElementById('pagination-info').textContent = '';
    document.getElementById('pagination-btns').innerHTML = '';
    return;
  }
  emptyState.classList.add('d-none');

  // Pagination slice
  // Mengatur pagination untuk membatasi jumlah data karyawan per halaman
  // jumlah halaman adalah jumlah data dibagi total data perhalaman
  const totalPages = Math.ceil(filtered.length / perPage);
  // jika halaman sekarang lebih besar dari total halaman maka kembali ke halman pertama
  if (currentPage > totalPages) currentPage = 1;

  // total index mulai dari halman sekarang dikurang 1 dikali total perhalaman
  const start = (currentPage - 1) * perPage;
  // memotong seluruh data karyawan dan hanya menampilkan dari awal index mulai sampai index terakhir setiap batas halaman
  const slice = filtered.slice(start, start + perPage);
  // menampilkan isi dari data karyawan yang sudah dipotong
  slice.forEach((k, idx) => {
    // membuat element <tr>
    const tr = document.createElement('tr');
    tr.style.animationDelay = `${idx * 0.04}s`;
    // mengisi table tr
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
    // element yang dibuat di tambahkan menjadi anak dari elemen tbody untuk ditampilkan
    tbody.appendChild(tr);
  });

  // Pagination info
  document.getElementById('pagination-info').textContent =
    `Menampilkan ${start + 1}–${Math.min(start + perPage, filtered.length)} dari ${filtered.length} karyawan`;

  // menampilkan html dan css dari pagination
  renderPagination(totalPages);

  // Event tombol detail
  tbody.querySelectorAll('.btn-open-detail').forEach(btn => {
    // jika tombol detail di klik maka akan membuka modal
    btn.addEventListener('click', () => bukaModalDetail(parseInt(btn.dataset.id)));
  });
}

// 6. PAGINATION
// berfungsi untuk menampilkan keterangan pagination
function renderPagination(totalPages) {
  // mendapatkan container dari pagination
  const container = document.getElementById('pagination-btns');
  container.innerHTML = '';

  // Prev
  // berfungsi untuk mengatur tombol previous
  const btnPrev = document.createElement('button');
  btnPrev.className = 'btn-page';
  btnPrev.innerHTML = '<i class="bi bi-chevron-left"></i>';
  btnPrev.disabled = currentPage === 1;
  // jika tombol previous di klik maka akan pindah ke halman sebelumnya
  btnPrev.addEventListener('click', () => { currentPage--; renderTabel(); });
  // menampilkan tombol previous dengan menjadikan anak dari container pagination
  container.appendChild(btnPrev);

  // Page numbers
  // Berfungsi untuk mengatur penomoran pagination
  for (let i = 1; i <= totalPages; i++) {
    const btn = document.createElement('button');
    btn.className = `btn-page${i === currentPage ? ' active' : ''}`;
    btn.textContent = i;
    btn.addEventListener('click', () => { currentPage = i; renderTabel(); });
    container.appendChild(btn);
  }

  // Next
  // berfungsi untuk mengatur tombol next
  const btnNext = document.createElement('button');
  btnNext.className = 'btn-page';
  btnNext.innerHTML = '<i class="bi bi-chevron-right"></i>';
  btnNext.disabled = currentPage === totalPages;
  // jika tombol next di klik maka akan pindah ke halamn selanjutnya
  btnNext.addEventListener('click', () => { currentPage++; renderTabel(); });
  // menampilkan tombol next dengan menjadikan anak dari container pagination                   
  container.appendChild(btnNext);
}

// 7. MODAL DETAIL
// Mendapatkan melemen modal detail dari bootstrap
const bsModalDetail = new bootstrap.Modal(document.getElementById('modalDetail'));

// berfungsi membuka modal detail
function bukaModalDetail(id) {
  // mendapatakan data karyawan
  const k = dataKaryawan.find(x => x.id === id);
  // jika ternyata data karyawan tidak ada maka tidak mengembalikan apa apa
  if (!k) return;
  // jika ada maka akan menampilkan sega informasi dari karyawan
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

  // menampilkan modal detail
  bsModalDetail.show();
}

// 8. FILTER BULAN
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

// 9. FILTER DIVISI
document.querySelectorAll('.filter-divisi').forEach(item => {
  item.addEventListener('click', function (e) {
    e.preventDefault();
    filterDivisi = this.dataset.value;
    document.getElementById('filterDivisiLabel').textContent = this.textContent;
    currentPage = 1;
    renderTabel();
  });
});

// 10. SEARCH
document.getElementById('searchInput').addEventListener('input', function () {
  searchQuery = this.value.toLowerCase().trim();
  currentPage = 1;
  renderTabel();
});

// 11. EXPORT PDF (print halaman)
document.getElementById('btnExport').addEventListener('click', () => {
  window.print();
});

// 12. SIDEBAR ACTIVE STATE
document.querySelectorAll('.sidebar-item').forEach(item => {
  item.addEventListener('click', function (e) {
    e.preventDefault();
    document.querySelectorAll('.sidebar-item').forEach(el => el.classList.remove('active'));
    this.classList.add('active');
  });
});

// 13. INIT
renderTabel();
