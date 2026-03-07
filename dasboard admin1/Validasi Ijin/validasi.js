// Mengkatifkan mode ECMAScript 5+ agar penulisan kode pada javascript lebih ketat
'use strict';

// 1. REAL-TIME CLOCK & DATE
// Deklarasi konstanta hari untuk menampilkan hari
const HARI  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
// Deklarasi konstanta bulan untuk menampilkan bulan
const BULAN = ['Januari','Februari','Maret','April','Mei','Juni',
               'Juli','Agustus','September','Oktober','November','Desember'];

// Fungsi untuk update jam atau DateTimePicker
function updateClock() {
  // instansi objek Date untuk mendapatkan tanggal
  const now = new Date();

  // Mulai menambahkan angka 0 setiap 1 digit dengan batas maksimal 2 karakter. Contoh 8 => 08 atau 22 => 22
  const pad = (n) => String(n).padStart(2, '0');

  // Menambahkan Jam
  document.getElementById('topbar-clock').textContent =
    `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;

  // Menambahkan Tanggal
  document.getElementById('topbar-date').textContent =
    `${HARI[now.getDay()]}, ${now.getDate()} ${BULAN[now.getMonth()]} ${now.getFullYear()}`;
}

// Jalankan fungsi updateClock
updateClock();

// Menjalankan fungsi updateClock setiap 1000 milidetik sekali
setInterval(updateClock, 1000);


// 2. DATA PENGAJUAN IZIN
// Menyiapkan data dummy pengajuan izin dalam bentuk array object
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
];

// 3. STATE
// State awal berfungsi untuk menetapkan kondisi awal filter dan pencarian
// Filter jenis izin dimulai dari semua
let filterAktif = 'semua';
// Belum ada query pencarian
let searchQuery = '';
// State modal digunakan untuk menyimpan aksi yang akan dijalankan
let pendingAction = null; // { type: 'tolak'|'setujui'|'tolakSemua'|'setujuiSemua', id? }

// 4. HELPERS

// Deklarasi konstanta label jenis izin dalam bentuk objek
const LABEL_JENIS = {
  sakit:     'Sakit',
  cuti:      'Cuti',
  dinas:     'Dinas Luar',
  keperluan: 'Keperluan Pribadi',
};

// Berfungsi untuk menentukan class css dari tag jenis izin
function getTagClass(jenis) {
  const map = { sakit: 'tag-sakit', cuti: 'tag-cuti', dinas: 'tag-dinas', keperluan: 'tag-keperluan' };
  return map[jenis] || 'tag-sakit';
}

// Berfungsi untuk menentukan simbol pada tag jenis izin
function getTagDot(jenis) {
  const dots = { sakit: '●', cuti: '●', dinas: '●', keperluan: '●' };
  return dots[jenis] || '●';
}

// Berfungsi untuk menghitung jumlah pengajuan yang masih pending
function countPending() {
  return dataPengajuan.filter(p => p.status === 'pending').length;
}

// Berfungsi untuk memperbarui jumlah badge pengajuan
function updateBadge() {
  const n = countPending();

  // Menampilkan jumlah pengajuan pending pada badge
  document.getElementById('badge-count').textContent = n;

  // Menampilkan keterangan jumlah pengajuan pada subtitle
  document.getElementById('subtitle-count').textContent =
    `${n} Pengajuan menunggu persetujuan`;
}


// 5. RENDER KARTU
// Render kartu untuk menampilkan seluruh data pengajuan izin
function renderKartu() {

  // Mendapatkan elemen container list pengajuan yang nantinya akan di isi dengan data list pengajuan
  const list = document.getElementById('pengajuan-list');
  const emptyState = document.getElementById('empty-state');

  // Filter + Search
  // dari data dummy diberikan difilter
  const filtered = dataPengajuan.filter(p => {

    // filter berdasarkan jenis izin
    const matchFilter = filterAktif === 'semua' || p.jenis === filterAktif;

    // Filter pencarian berdasarkan nama departemen dan alasan
    const matchSearch = p.nama.toLowerCase().includes(searchQuery) ||
                        p.dept.toLowerCase().includes(searchQuery) ||
                        p.alasan.toLowerCase().includes(searchQuery);

    // Mengembalikan hasil filter dari jenis izin dan query pencarian
    return matchFilter && matchSearch;
  });

  // Mengosongkan container sebelum data ditampilkan ulang
  list.innerHTML = '';

  // Jika data pengajuan kosong maka akan menampilkan bahwa data kosong
  if (filtered.length === 0) {
    emptyState.classList.remove('d-none');
    return;
  }

  emptyState.classList.add('d-none');

  // menampilkan isi dari data pengajuan yang sudah difilter
  filtered.forEach((p, idx) => {

    // membuat element div kartu
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

    // Jika terdapat lampiran maka tampilkan lampiran
    const lampiranHTML = p.lampiran
      ? `<span class="detail-value">
           <i class="bi bi-paperclip"></i> ${p.lampiran}
         </span>`
      : `<span class="detail-value" style="color:var(--muted);font-weight:500;">Tidak ada</span>`;

    // Mengisi isi kartu pengajuan
    card.innerHTML = `
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
    `;

    // element yang dibuat ditambahkan menjadi anak dari container list
    list.appendChild(card);
  });

  // Event tombol aksi pada setiap kartu
  list.querySelectorAll('.btn-action-tolak').forEach(btn => {
    btn.addEventListener('click', () => bukaModal('tolak', parseInt(btn.dataset.id)));
  });

  list.querySelectorAll('.btn-action-setujui').forEach(btn => {
    btn.addEventListener('click', () => bukaModal('setujui', parseInt(btn.dataset.id)));
  });
}


// 6. SEARCH
// Berfungsi untuk melakukan pencarian data pengajuan
document.getElementById('searchInput').addEventListener('input', function () {

  // Mengambil nilai input pencarian dan mengubahnya menjadi huruf kecil
  searchQuery = this.value.toLowerCase().trim();

  // Menampilkan ulang kartu berdasarkan hasil pencarian
  renderKartu();
});


// 7. FILTER DROPDOWN
document.querySelectorAll('.filter-option').forEach(item => {
  item.addEventListener('click', function (e) {

    e.preventDefault();

    // Mengubah jenis filter yang aktif
    filterAktif = this.dataset.value;

    // Mengubah label filter yang ditampilkan
    document.getElementById('filterLabel').textContent = this.textContent;

    // Menampilkan ulang kartu berdasarkan filter
    renderKartu();
  });
});


// 8. BULK ACTIONS
// Jika tombol tolak semua ditekan maka membuka modal konfirmasi
document.getElementById('btnTolakSemua').addEventListener('click', () => {
  bukaModal('tolakSemua');
});

// Jika tombol setujui semua ditekan maka membuka modal konfirmasi
document.getElementById('btnSetujuiSemua').addEventListener('click', () => {
  bukaModal('setujuiSemua');
});


// 9. SIDEBAR ACTIVE STATE
// Mengatur menu sidebar yang sedang aktif
document.querySelectorAll('.sidebar-item').forEach(item => {
  item.addEventListener('click', function (e) {

    e.preventDefault();

    // Menghapus class active pada semua sidebar
    document.querySelectorAll('.sidebar-item').forEach(el => el.classList.remove('active'));

    // Menambahkan class active pada sidebar yang diklik
    this.classList.add('active');
  });
});


// 10. INIT
// Menjalankan fungsi awal saat halaman pertama kali dibuka
updateBadge();
renderKartu();