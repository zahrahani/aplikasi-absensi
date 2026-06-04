'use strict';

// 1. KONFIGURASI


const WEB_URI      = document.querySelector('meta[name="base-url"]').getAttribute('content');
const API_URI      = WEB_URI + 'api';
const API_REKAP  = API_URI + '/rekap';         // getRekap()
const API_DETAIL = API_URI + '/rekap/detail';  // getDetailKaryawan()


// 2. STATE
let dataKaryawan     = [];
let filterBulan      = getBulanSekarang();   // "2026-05"
let filterBulanLabel = '';
let filterDivisi     = 'semua';
let searchQuery      = '';
let currentPage      = 1;
const perPage        = 8;
let isLoading        = false;

// 3. HELPERS TANGGAL
function getBulanSekarang() {
    const now = new Date();
    const mm  = String(now.getMonth() + 1).padStart(2, '0');
    return `${now.getFullYear()}-${mm}`;
}


// 4. FETCH REKAP DARI CONTROLLER
async function fetchRekap() {
    if (isLoading) return;
    isLoading = true;
    tampilkanLoading(true);

    try {
        const res  = await fetch(`${API_REKAP}?bulan=${filterBulan}`, { credentials: 'include' });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);

        const json = await res.json();
        if (!json.data) throw new Error(json.errors_messages || 'Gagal memuat data');



        // Ambil dari json.data sesuai struktur response controller
        dataKaryawan     = json.data.karyawan;
        filterBulanLabel = json.data.label;

        // Update label di halaman
        document.getElementById('table-title').textContent = `Rekap per Karyawan – ${filterBulanLabel}`;

        // Isi dropdown divisi dari response (sekali saja)
        if (json.data.divisi?.length) populateDivisiDropdown(json.data.divisi);

        
        currentPage = 1;
        renderTabel();

        // Update stat cards dari insight
        if (json.data.insight) updateStatCards(json.data.insight);
        

    } catch (err) {
        console.error('[fetchRekap]', err);
        tampilkanError(err.message);
    } finally {
        isLoading = false;
        tampilkanLoading(false);
    }
}

// 5. FETCH DETAIL KARYAWAN (untuk modal)
async function fetchDetailKaryawan(userId) {
    try {
        const res  = await fetch(`${API_DETAIL}?user_id=${userId}&bulan=${filterBulan}`, { credentials: 'include' });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);

        const json = await res.json();
        if (!json.data) throw new Error(json.errors_messages || 'Gagal memuat detail');

        // Render modal dari json.data sesuai struktur response controller
        renderModalDetail(json.data);

    } catch (err) {
        console.error('[fetchDetailKaryawan]', err);
        document.getElementById('modal-detail-body').innerHTML = `
            <div class="text-center text-danger py-3">
                <i class="bi bi-exclamation-circle me-2"></i>
                Gagal memuat detail: ${err.message}
            </div>`;
    }
}

// 6. DROPDOWN DIVISI DINAMIS
let divisiSudahDiisi = false;

function populateDivisiDropdown(divisiList) {
    if (divisiSudahDiisi) return;
    divisiSudahDiisi = true;

    // ⚠️ Sesuaikan selector ini dengan HTML dropdown divisi kamu
    const ul = document.querySelector('.dropdown-divisi-list');
    if (!ul) return;

    ul.querySelectorAll('[data-generated]').forEach(el => el.remove());

    divisiList.forEach(d => {
        const li = document.createElement('li');
        li.setAttribute('data-generated', '1');
        li.innerHTML = `
            <a class="filter-divisi dropdown-item" href="#"
               data-value="${d.divisi_id}"
               data-label="${d.nama_divisi}">
                ${d.nama_divisi}
            </a>`;
        ul.appendChild(li);
    });

    attachDivisiEvents();
}

// 7. HELPERS UI
function tampilkanLoading(show) {
    const tbody = document.getElementById('table-body');
    if (show) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4 text-muted">
                    <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                    Memuat data...
                </td>
            </tr>`;
    }
}

function tampilkanError(pesan) {
    document.getElementById('table-body').innerHTML = `
        <tr>
            <td colspan="8" class="text-center py-4 text-danger">
                <i class="bi bi-exclamation-circle me-2"></i>
                ${pesan}
                <br>
                <button class="btn btn-sm btn-outline-primary mt-2"
                        onclick="fetchRekap()">
                    <i class="bi bi-arrow-clockwise"></i> Coba lagi
                </button>
            </td>
        </tr>`;
    document.getElementById('pagination-info').textContent = '';
    document.getElementById('pagination-btns').innerHTML   = '';
}


function getInisial(nama) {
    return nama.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase();
}

function getPctClass(pct) {
    if (pct >= 90) return 'pct-high';
    if (pct >= 75) return 'pct-medium';
    return 'pct-low';
}

// 8. FILTER LOKAL (search + divisi dari state)
function getFiltered() {
    return dataKaryawan.filter(k => {
        const matchDiv = filterDivisi === 'semua' || k.divisi === filterDivisi;
        const q        = searchQuery;
        const matchSrc = !q
            || k.nama.toLowerCase().includes(q)
            || k.nip.toLowerCase().includes(q)
            || k.divisi.toLowerCase().includes(q);
        return matchDiv && matchSrc;
    });
}

// 9. RENDER TABEL
function renderTabel() {
    const tbody      = document.getElementById('table-body');
    const emptyState = document.getElementById('empty-state');
    const filtered   = getFiltered();
    tbody.innerHTML  = '';

    if (filtered.length === 0) {
        emptyState.classList.remove('d-none');
        document.getElementById('pagination-info').textContent = '';
        document.getElementById('pagination-btns').innerHTML   = '';
        return;
    }
    emptyState.classList.add('d-none');

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
                    <div class="jadwal-avatar avatar-default">
                      ${k.foto_profil
                        ? `<img src="${k.foto_profil}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;"/>`
                        : getInisial(k.nama)}
                    </div>
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

    document.getElementById('pagination-info').textContent =
        `Menampilkan ${start + 1}–${Math.min(start + perPage, filtered.length)} dari ${filtered.length} karyawan`;

    renderPagination(totalPages);

    // Event tombol detail — fetch ke controller
    tbody.querySelectorAll('.btn-open-detail').forEach(btn => {
        btn.addEventListener('click', () => bukaModalDetail(btn.dataset.id));
    });
}

// 10. PAGINATION
function renderPagination(totalPages) {
    const container = document.getElementById('pagination-btns');
    container.innerHTML = '';

    const prev = document.createElement('button');
    prev.className = 'btn-page';
    prev.innerHTML = '<i class="bi bi-chevron-left"></i>';
    prev.disabled  = currentPage === 1;
    prev.addEventListener('click', () => { currentPage--; renderTabel(); });
    container.appendChild(prev);

    for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement('button');
        btn.className   = `btn-page${i === currentPage ? ' active' : ''}`;
        btn.textContent = i;
        btn.addEventListener('click', () => { currentPage = i; renderTabel(); });
        container.appendChild(btn);
    }

    const next = document.createElement('button');
    next.className = 'btn-page';
    next.innerHTML = '<i class="bi bi-chevron-right"></i>';
    next.disabled  = currentPage === totalPages;
    next.addEventListener('click', () => { currentPage++; renderTabel(); });
    container.appendChild(next);
}

// 11. MODAL DETAIL
const bsModalDetail = new bootstrap.Modal(document.getElementById('modalDetail'));

function bukaModalDetail(userId) {
    // Tampilkan modal dulu dengan loading, baru fetch
    document.getElementById('modalDetailLabel').textContent = 'Memuat detail...';
    document.getElementById('modal-detail-body').innerHTML  = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status"></div>
        </div>`;
    bsModalDetail.show();

    fetchDetailKaryawan(userId);
}

function renderModalDetail(data) {
    // Destructure dari json.data sesuai struktur response controller
    const k    = data.karyawan;
    const rekap = data.rekap_bulan_ini;
    const ab   = data.absensi   || [];
    const pj   = data.pengajuan || [];

    document.getElementById('modalDetailLabel').textContent =
        `Detail Kehadiran – ${k.nama_lengkap}`;

    // Baris riwayat absensi harian
    const rowsAbsensi = ab.length
        ? ab.map(a => `
            <tr>
                <td>${a.tanggal}</td>
                <td>
                    <span style="
                        background:${a.color_hex}22;
                        color:${a.color_hex};
                        padding:2px 8px;
                        border-radius:6px;
                        font-size:.8rem;
                        font-weight:600;">
                        ${a.nama_jenis}
                    </span>
                </td>
                <td>${a.jam_masuk  ?? '–'}</td>
                <td>${a.jam_pulang ?? '–'}</td>
                <td>${a.terlambat_menit > 0 ? a.terlambat_menit + ' mnt' : '–'}</td>
            </tr>`).join('')
        : `<tr><td colspan="5" class="text-center text-muted">Belum ada data absensi</td></tr>`;

    document.getElementById('modal-detail-body').innerHTML = `
        <!-- Info karyawan -->
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="karyawan-avatar avatar-default"
                 style="width:52px;height:52px;font-size:1rem;">
              ${k.foto_profil
                ? `<img src="${WEB_URI + k.foto_profil}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;"/>`
                : getInisial(k.nama_lengkap)}
            </div>
            <div>
                <div style="font-size:1.05rem;font-weight:800;color:var(--text);">
                    ${k.nama_lengkap}
                </div>
                <div style="font-size:.8rem;color:var(--muted);">
                    ${k.user_id} &bull; ${k.nama_divisi} &bull; ${k.nama_jabatan}
                </div>
            </div>
        </div>

        <!-- Stat grid — pakai rekap_bulan_ini dari controller -->
        <div class="detail-grid">
            <div class="detail-stat-box">
                <div class="detail-stat-number" style="color:var(--green);">${rekap.hadir}</div>
                <div class="detail-stat-label">Hari Hadir</div>
            </div>
            <div class="detail-stat-box">
                <div class="detail-stat-number" style="color:#f59e0b;">${rekap.terlambat}</div>
                <div class="detail-stat-label">Terlambat</div>
            </div>
            <div class="detail-stat-box">
                <div class="detail-stat-number" style="color:var(--navy);">${rekap.izin}</div>
                <div class="detail-stat-label">Izin / Sakit</div>
            </div>
            <div class="detail-stat-box">
                <div class="detail-stat-number ${getPctClass(rekap.pct)}">${rekap.pct}%</div>
                <div class="detail-stat-label">% Kehadiran</div>
            </div>
            <div class="detail-stat-box">
                <div class="detail-stat-number" style="color:#e03b3b;">${rekap.alpha}</div>
                <div class="detail-stat-label">Tidak Hadir (Alpha)</div>
            </div>
        </div>

        <!-- Info tambahan -->
        <div style="background:var(--blue-light);border-radius:10px;padding:14px 16px;margin-bottom:1rem;">
            <div class="detail-info-row">
                <span>Periode Laporan</span>
                <span>${filterBulanLabel}</span>
            </div>
            <div class="detail-info-row">
                <span>Divisi</span>
                <span>${k.nama_divisi}</span>
            </div>
            <div class="detail-info-row">
                <span>Rata-rata Durasi Kerja</span>
                <span>${rekap.rata_rata_durasi}</span>
            </div>
            <div class="detail-info-row">
                <span>Status Kehadiran</span>
                <span class="${getPctClass(rekap.pct)}">
                    ${rekap.pct >= 90 ? 'Sangat Baik' : rekap.pct >= 75 ? 'Cukup Baik' : 'Perlu Perhatian'}
                </span>
            </div>
        </div>

        <!-- Riwayat absensi harian -->
        <div style="font-weight:700;margin-bottom:.5rem;">Riwayat Absensi</div>
        <div style="overflow-x:auto;">
            <table class="table table-sm table-hover" style="font-size:.85rem;">
                <thead>
                    <tr>
                        <th>Tanggal</th><th>Status</th>
                        <th>Masuk</th><th>Pulang</th><th>Telat</th>
                    </tr>
                </thead>
                <tbody>${rowsAbsensi}</tbody>
            </table>
        </div>

        <!-- Pengajuan bulan ini (jika ada) -->
        ${pj.length ? `
        <div style="font-weight:700;margin:.75rem 0 .5rem;">Pengajuan Bulan Ini</div>
        ${pj.map(p => `
            <div style="
                border-left:3px solid ${p.status_pengajuan === 'approved' ? '#22c55e' : p.status_pengajuan === 'rejected' ? '#ef4444' : '#f59e0b'};
                padding:8px 12px;
                background:var(--blue-light);
                border-radius:0 8px 8px 0;
                margin-bottom:6px;
                font-size:.85rem;">
                <strong>${p.nama_jenis}</strong>
                <span style="float:right;text-transform:capitalize;
                    color:${p.status_pengajuan === 'approved' ? '#22c55e' : p.status_pengajuan === 'rejected' ? '#ef4444' : '#f59e0b'};">
                    ${p.status_pengajuan}
                </span>
                <div style="color:var(--muted);">${p.tanggal_mulai} – ${p.tanggal_selesai}</div>
                <div>${p.alasan}</div>
            </div>`).join('')}` : ''}
    `;
}

// 12. EVENT: FILTER BULAN
function attachBulanEvents() {
    const bagian    = getBulanSekarang().split('-');
    const tahunAwal = bagian[0];
    const bulanAwal = bagian[1];

    const inputTahun    = document.getElementById('filterTahunInput');
    const labelBtn      = document.getElementById('filterBulanLabel');

    // Set nilai awal tahun
    inputTahun.value = tahunAwal;

    // Set active item & label sesuai bulan sekarang
    function setAktifBulan(value) {
        document.querySelectorAll('.filter-bulan-item').forEach(item => {
            item.classList.toggle('active', item.dataset.value === value);
        });
        const aktif = document.querySelector(`.filter-bulan-item[data-value="${value}"]`);
        if (aktif) labelBtn.textContent = aktif.textContent;
    }

    // Init — aktifkan bulan sekarang
    setAktifBulan(bulanAwal);

    // Event klik item bulan
    document.querySelectorAll('.filter-bulan-item').forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            const bulan = this.dataset.value;
            const tahun = inputTahun.value;
            if (!tahun || tahun < 2020 || tahun > 2099) return;

            setAktifBulan(bulan);
            filterBulan = `${tahun}-${bulan}`;
            currentPage = 1;
            fetchRekap();
        });
    });

    // Event ganti tahun
    inputTahun.addEventListener('change', function () {
        const bulanAktif = document.querySelector('.filter-bulan-item.active');
        const bulan      = bulanAktif ? bulanAktif.dataset.value : bulanAwal;
        const tahun      = this.value;
        if (!tahun || tahun < 2020 || tahun > 2099) return;

        filterBulan = `${tahun}-${bulan}`;
        currentPage = 1;
        fetchRekap();
    });
}
attachBulanEvents();

// 13. EVENT: FILTER DIVISI
function attachDivisiEvents() {
    document.querySelectorAll('.filter-divisi').forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            filterDivisi = this.dataset.value; // divisi_id atau "semua"
            document.getElementById('filterDivisiLabel').textContent =
                this.dataset.label || this.textContent;
            currentPage = 1;
            renderTabel(); // filter lokal, tidak fetch ulang
        });
    });
}
attachDivisiEvents();

// 14. EVENT: SEARCH
document.getElementById('searchInput').addEventListener('input', function () {
    searchQuery = this.value.toLowerCase().trim();
    currentPage = 1;
    renderTabel();
});

// 15. EVENT: EXPORT
document.getElementById('btnExport').addEventListener('click', () => window.print());

// 16. Insight di card 
function updateStatCards(insight) {

    document.getElementById('stat-total').textContent     = insight.total_karyawan;
    document.getElementById('stat-kehadiran').textContent = insight.rata_kehadiran + '%';
    document.getElementById('stat-terlambat').textContent = insight.rata_terlambat + '%';
    document.getElementById('stat-alpha').textContent     = insight.rata_alpha + '%';
    document.getElementById('stat-izin').textContent      = insight.total_izin;
}


// 16. INIT
fetchRekap();


document.getElementById('btnExport').addEventListener('click', () => {
  // Kirim bulan, divisi, dan label divisi ke halaman cetak
  const divisiParam      = filterDivisi;
  const divisiLabelParam = document.getElementById('filterDivisiLabel').textContent.trim();

  const url = `${WEB_URI}cetak-rekap`
    + `?bulan=${filterBulan}`
    + `&divisi=${encodeURIComponent(divisiParam)}`
    + `&divisi_label=${encodeURIComponent(divisiLabelParam)}`;

  window.open(url, '_blank');
});