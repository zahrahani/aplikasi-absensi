/* ═══════════════════════════════════════════
   jadwalKaryawan.js – CV. NAFIHAKA Creative
   Fitur: Tabel Jadwal, Filter Divisi/Shift/Status,
          Search, Modal Ubah Shift,
          Validasi Locked (sedang aktif shift)
════════════════════════════════════════════ */

'use strict';

// 1. KONFIGURASI API
const WEB_URI      = document.querySelector('meta[name="base-url"]').getAttribute('content');
const API_URI      = WEB_URI + 'api';
const API_JADWAL   = API_URI + '/jadwal';        // getJadwal()
const API_SIMPAN   = API_URI + '/jadwal/simpan'; // simpanJadwal()
const CSRF_TOKEN   = document.getElementById('token_csrf').value;

// 2. STATE
let dataKaryawan    = [];  // list karyawan + shift + status dari API
let dataShift       = [];  // list semua shift untuk pilihan modal
let filterDivisi    = 'semua';
let filterShift     = 'semua';
let filterStatus    = 'semua';
let searchQuery     = '';
let isLoading       = false;

// State modal
let pendingUserId   = null;
let selectedShiftId = null;

// 3. HELPERS
function getInisial(nama) {
    return nama.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase();
}

// function getAvatarClass(divisi) {
//     const map = {
//         'Human Resource': 'avatar-hrd',
//         'HRD':            'avatar-hrd',
//         'IT':             'avatar-it',
//         'Finance':        'avatar-finance',
//         'Marketing':      'avatar-marketing',
//         'Operations':     'avatar-operations',
//         'Design':         'avatar-design',
//         'Sales':          'avatar-sales',
//     };
//     return map[divisi] || 'avatar-it';
// }

function getShiftChipClass(namaShift) {
    if (!namaShift) return 'shift-kosong';
    const n = namaShift.toLowerCase();
    if (n.includes('pagi'))  return 'shift-pagi';
    if (n.includes('sore'))  return 'shift-sore';
    if (n.includes('malam')) return 'shift-malam';
    return 'shift-kosong';
}

function getShiftBadgeClass(namaShift) {
    if (!namaShift) return 'badge-libur';
    const n = namaShift.toLowerCase();
    if (n.includes('pagi'))  return 'badge-pagi';
    if (n.includes('sore'))  return 'badge-sore';
    if (n.includes('malam')) return 'badge-malam';
    return 'badge-libur';
}

// 4. FETCH DATA DARI CONTROLLER
async function fetchJadwal() {
    if (isLoading) return;
    isLoading = true;
    tampilkanLoading(true);

    try {
        const res  = await fetch(API_JADWAL, { credentials: 'include' });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);

        const json = await res.json();
        if (!json.data) throw new Error(json.errors_messages || 'Gagal memuat data');

        // Simpan ke state
        dataKaryawan = json.data.karyawan;
        dataShift    = json.data.shift;

        // Isi dropdown divisi & shift (sekali saja)
        if (json.data.divisi?.length) populateDivisiDropdown(json.data.divisi);
        if (dataShift.length)         populateShiftDropdown(dataShift);

        // Update stat cards
        updateStatCards();
        renderTabel();

    } catch (err) {
        console.error('[fetchJadwal]', err.message);
        tampilkanError(err.message);
    } finally {
        isLoading = false;
        tampilkanLoading(false);
    }
}

// 5. SIMPAN JADWAL KE CONTROLLER
async function simpanJadwal(userId, shiftId) {
    const btnSimpan       = document.getElementById('btnSimpanShift');
    btnSimpan.disabled    = true;
    btnSimpan.textContent = 'Menyimpan...';

    try {
        const res  = await fetch(API_SIMPAN, {
            method:      'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ user_id: userId, shift_id: shiftId }),
        });

        const json = await res.json();
        if (!res.ok || !json.data) throw new Error(json.errors_messages || 'Gagal menyimpan');

        // Update state lokal tanpa fetch ulang
        dataKaryawan = dataKaryawan.map(k =>
            k.user_id === userId
                ? { ...k, shift_id: json.data.shift_id, nama_shift: json.data.nama_shift,
                          jam_masuk: json.data.jam_masuk, jam_pulang: json.data.jam_pulang }
                : k
        );

        bsModalAturShift.hide();
        updateStatCards();
        renderTabel();

    } catch (err) {
        console.error('[simpanJadwal]', err.message);
        alert('Gagal menyimpan: ' + err.message);
    } finally {
        btnSimpan.disabled    = false;
        btnSimpan.innerHTML   = '<i class="bi bi-check-lg me-1"></i> Simpan Shift';
    }
}

// 6. UPDATE STAT CARDS
function updateStatCards() {
    const total   = dataKaryawan.length;
    const aktif   = dataKaryawan.filter(k => k.status === 'aktif').length;
    const selesai = dataKaryawan.filter(k => k.status === 'selesai').length;
    const belum   = dataKaryawan.filter(k => k.status === 'belum').length;

    document.getElementById('stat-total').textContent   = total;
    document.getElementById('stat-aktif').textContent   = aktif;
    document.getElementById('stat-selesai').textContent = selesai;
    document.getElementById('stat-belum').textContent   = belum;
}

// 7. RENDER TABEL
function renderTabel() {
    const tbody      = document.getElementById('jadwal-table-body');
    const emptyState = document.getElementById('empty-state');

    // Filter lokal
    const filtered = dataKaryawan.filter(k => {
        const matchDiv    = filterDivisi === 'semua' || k.divisi_id === filterDivisi;
        const matchShift  = filterShift  === 'semua' || k.shift_id  === filterShift;
        const matchStatus = filterStatus === 'semua' || k.status    === filterStatus;
        const matchSearch = !searchQuery
            || k.nama.toLowerCase().includes(searchQuery)
            || k.divisi.toLowerCase().includes(searchQuery)
            || k.user_id.toLowerCase().includes(searchQuery);
        return matchDiv && matchShift && matchStatus && matchSearch;
    });

    tbody.innerHTML = '';

    if (filtered.length === 0) {
        emptyState.classList.remove('d-none');
        return;
    }
    emptyState.classList.add('d-none');

    filtered.forEach((k, idx) => {
        const tr         = document.createElement('tr');
        tr.style.animationDelay = `${idx * 0.03}s`;

        // Chip shift
        const chipCls  = getShiftChipClass(k.nama_shift);
        const shiftHTML = k.nama_shift
            ? `<span class="shift-chip ${chipCls}">
                   <i class="bi bi-clock"></i>
                   ${k.nama_shift}
                   <span class="shift-chip-time">${k.jam_masuk}–${k.jam_pulang}</span>
               </span>`
            : `<span class="shift-chip shift-kosong">Belum ada shift</span>`;

        // Badge status hari ini
        let statusHTML = '';
        if (k.status === 'aktif') {
            statusHTML = `<span class="status-aktif">
                <i class="bi bi-circle-fill" style="font-size:.5rem;"></i> Sedang Shift
            </span>`;
        } else if (k.status === 'selesai') {
            statusHTML = `<span class="status-selesai">
                <i class="bi bi-check-circle-fill"></i> Sudah Pulang
            </span>`;
        } else {
            statusHTML = `<span class="status-belum">
                <i class="bi bi-dash-circle"></i> Belum Masuk
            </span>`;
        }

        // Tombol ubah — disabled jika sedang aktif shift
        const locked    = k.status === 'aktif';
        const btnHTML   = `
            <button class="btn-ubah-shift${locked ? ' locked' : ''}"
                    data-user-id="${k.user_id}"
                    ${locked ? 'disabled title="Tidak dapat diubah, karyawan sedang aktif shift"' : ''}>
                <i class="bi bi-pencil-fill"></i>
                ${locked ? 'Terkunci' : 'Ubah Shift'}
            </button>`;

        tr.innerHTML = `
            <td>
                <div class="karyawan-cell">
                    <div class="jadwal-avatar avatar-default">
                        ${k.foto
                            ? `<img src="${WEB_URI + k.foto}" alt="${k.nama}"/>`
                            : getInisial(k.nama)
                        }
                    </div>
                    <div>
                        <div class="karyawan-nama">${k.nama}</div>
                        <div class="karyawan-nip">${k.user_id}</div>
                    </div>
                </div>
            </td>
            <td><span class="divisi-badge">${k.divisi}</span></td>
            <td>${shiftHTML}</td>
            <td style="font-weight:600;">${k.jam_masuk ?? '–'}</td>
            <td style="font-weight:600;">${k.jam_pulang ?? '–'}</td>
            <td>${statusHTML}</td>
            <td>${btnHTML}</td>
        `;

        tbody.appendChild(tr);
    });

    // Event tombol ubah shift
    tbody.querySelectorAll('.btn-ubah-shift:not(.locked)').forEach(btn => {
        btn.addEventListener('click', () => bukaModalShift(btn.dataset.userId));
    });
}

// 8. MODAL UBAH SHIFT
const bsModalAturShift = new bootstrap.Modal(document.getElementById('modalAturShift'));

function bukaModalShift(userId) {
    const k       = dataKaryawan.find(x => x.user_id === userId);
    if (!k) return;

    pendingUserId   = userId;
    selectedShiftId = k.shift_id ?? null;

    // Isi info karyawan
    const avatarEl = document.getElementById('modal-avatar');
    avatarEl.className = `modal-karyawan-avatar avatar-default`;
    avatarEl.innerHTML = k.foto
        ? `<img src="${k.foto}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;"/>`
        : getInisial(k.nama);

    document.getElementById('modal-nama').textContent = k.nama;
    document.getElementById('modal-meta').textContent = `${k.user_id} · ${k.divisi}`;

    // Shift saat ini
    document.getElementById('modal-shift-sekarang').textContent =
        k.nama_shift ?? 'Belum ada shift';
    const chipEl = document.getElementById('modal-shift-sekarang-chip');
    chipEl.innerHTML = k.nama_shift
        ? `<span class="shift-chip ${getShiftChipClass(k.nama_shift)}" style="font-size:.75rem;padding:4px 10px;">
               ${k.jam_masuk} – ${k.jam_pulang}
           </span>`
        : '';

    // Alert locked — tidak akan muncul karena tombol ubah sudah disabled saat locked
    // tapi tetap ada sebagai pengaman
    document.getElementById('modal-alert-locked').classList.add('d-none');

    // Tombol simpan
    const btnSimpan       = document.getElementById('btnSimpanShift');
    btnSimpan.disabled    = !selectedShiftId;
    btnSimpan.innerHTML   = '<i class="bi bi-check-lg me-1"></i> Simpan Shift';

    // Render pilihan shift
    const grid = document.getElementById('shift-option-grid');
    grid.innerHTML = dataShift.map(s => {
        const isSelected = s.shift_id === selectedShiftId;
        return `
            <div class="shift-option-card${isSelected ? ' selected' : ''}"
                 data-shift-id="${s.shift_id}">
                <div class="shift-option-name">${s.nama_shift}</div>
                <div class="shift-option-time">${s.jam_masuk} – ${s.jam_pulang}</div>
                <span class="shift-option-badge ${getShiftBadgeClass(s.nama_shift)}">
                    ${s.keterangan ?? ''}
                </span>
            </div>`;
    }).join('');

    // Event pilih shift
    grid.querySelectorAll('.shift-option-card').forEach(card => {
        card.addEventListener('click', function () {
            grid.querySelectorAll('.shift-option-card').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            selectedShiftId    = this.dataset.shiftId;
            btnSimpan.disabled = false;
        });
    });

    bsModalAturShift.show();
}

// Tombol simpan modal
document.getElementById('btnSimpanShift').addEventListener('click', async () => {
    if (!pendingUserId || !selectedShiftId) return;
    await simpanJadwal(pendingUserId, selectedShiftId);
    pendingUserId   = null;
    selectedShiftId = null;
});

// 9. DROPDOWN DINAMIS
let divisiSudahDiisi = false;
let shiftSudahDiisi  = false;

function populateDivisiDropdown(divisiList) {
    if (divisiSudahDiisi) return;
    divisiSudahDiisi = true;

    const ul = document.getElementById('filterDivisiMenu');
    divisiList.forEach(d => {
        const li = document.createElement('li');
        li.innerHTML = `
            <a class="dropdown-item filter-divisi-item" href="#"
               data-value="${d.divisi_id}" data-label="${d.nama_divisi}">
                ${d.nama_divisi}
            </a>`;
        ul.appendChild(li);
    });
    attachDivisiEvents();
}

function populateShiftDropdown(shiftList) {
    if (shiftSudahDiisi) return;
    shiftSudahDiisi = true;

    const ul = document.getElementById('filterShiftMenu');
    shiftList.forEach(s => {
        const li = document.createElement('li');
        li.innerHTML = `
            <a class="dropdown-item filter-shift-item" href="#"
               data-value="${s.shift_id}" data-label="${s.nama_shift}">
                ${s.nama_shift}
            </a>`;
        ul.appendChild(li);
    });
    attachShiftEvents();
}

// 10. HELPERS UI
function tampilkanLoading(show) {
    const tbody = document.getElementById('jadwal-table-body');
    if (show) {
        tbody.innerHTML = Array.from({ length: 6 }, () => `
            <tr>
                <td>
                    <div class="karyawan-cell">
                        <div class="skeleton" style="width:38px;height:38px;border-radius:50%;"></div>
                        <div>
                            <div class="skeleton" style="width:100px;height:12px;margin-bottom:5px;"></div>
                            <div class="skeleton" style="width:70px;height:10px;"></div>
                        </div>
                    </div>
                </td>
                ${Array.from({ length: 6 }, () =>
                    `<td><div class="skeleton" style="width:80px;height:28px;border-radius:6px;"></div></td>`
                ).join('')}
            </tr>`).join('');
    }
}

function tampilkanError(pesan) {
    document.getElementById('jadwal-table-body').innerHTML = `
        <tr>
            <td colspan="7" class="text-center py-5 text-danger">
                <i class="bi bi-exclamation-circle me-2"></i>${pesan}
                <br>
                <button class="btn btn-sm btn-outline-primary mt-2"
                        onclick="fetchJadwal()">
                    <i class="bi bi-arrow-clockwise"></i> Coba lagi
                </button>
            </td>
        </tr>`;
}

// 11. EVENT: FILTER DIVISI
function attachDivisiEvents() {
    document.querySelectorAll('.filter-divisi-item').forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelectorAll('.filter-divisi-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            filterDivisi = this.dataset.value;
            document.getElementById('filterDivisiLabel').textContent =
                this.dataset.label || this.textContent;
            renderTabel();
        });
    });
}
attachDivisiEvents();

// 12. EVENT: FILTER SHIFT
function attachShiftEvents() {
    document.querySelectorAll('.filter-shift-item').forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelectorAll('.filter-shift-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            filterShift = this.dataset.value;
            document.getElementById('filterShiftLabel').textContent =
                this.dataset.label || this.textContent;
            renderTabel();
        });
    });
}
attachShiftEvents();

// 13. EVENT: FILTER STATUS
document.querySelectorAll('.filter-status-item').forEach(item => {
    item.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelectorAll('.filter-status-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
        filterStatus = this.dataset.value;
        document.getElementById('filterStatusLabel').textContent = this.textContent;
        renderTabel();
    });
});

// 14. EVENT: SEARCH
document.getElementById('searchInput').addEventListener('input', function () {
    searchQuery = this.value.toLowerCase().trim();
    renderTabel();
});

// 15. INIT
fetchJadwal();

