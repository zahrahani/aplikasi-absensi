/* ═══════════════════════════════════════════
   validasiIzin.js
   Fitur: Fetch API, Render Kartu, Search,
          Filter, Tolak/Setujui, Bulk Aksi, Modal
════════════════════════════════════════════ */

'use strict';

// ─────────────────────────────────────────────
// 1. KONFIGURASI API
// ─────────────────────────────────────────────
const API_URI         = document.querySelector('meta[name="base-url"]').getAttribute('content') + 'api';
const API_PENGAJUAN   = API_URI + '/validasi-izin';        // getPengajuanIzin()
const API_AKSI        = API_URI + '/validasi-izin/aksi';   // aksiPengajuanIzin()
const API_BULK        = API_URI + '/validasi-izin/bulk';   // bulkAksiPengajuanIzin()
const CSRF_TOKEN      = document.getElementById('token_csrf').value;
let filterStatus = 'pending'; // state tab aktif

// ─────────────────────────────────────────────
// 2. STATE
// ─────────────────────────────────────────────
let dataPengajuan = [];
let filterAktif   = 'semua';
let searchQuery   = '';
let isLoading     = false;

// State modal
let pendingAction = null; // { type: 'tolak'|'setujui'|'tolakSemua'|'setujuiSemua', id? }

// ─────────────────────────────────────────────
// 3. HELPERS
// ─────────────────────────────────────────────
// Map jenis_id database → value filter JS
const MAP_JENIS_FILTER = {
    'J03': 'sakit',
    'J04': 'cuti',
    'J05': 'wfh',
};

const LABEL_JENIS = {
    sakit:     'Sakit',
    cuti:      'Cuti',
    wfh:       'WFH',
};

function getTagClass(jenis) {
    const map = {
        sakit:     'tag-sakit',
        cuti:      'tag-cuti',
        wfh:       'tag-cuti',
    };
    return map[jenis] || 'tag-sakit';
}

function countPending() {
    return dataPengajuan.filter(p => p.status === 'pending').length;
}

function updateBadge() {
    const pending   = dataPengajuan.filter(p => p.status === 'pending').length;
    const disetujui = dataPengajuan.filter(p => p.status === 'approved').length;
    const ditolak   = dataPengajuan.filter(p => p.status === 'rejected').length;

    const badgeEl = document.getElementById('badge-count');
    if (badgeEl) badgeEl.textContent = pending;
    document.getElementById('badge-pending').textContent   = pending;
    document.getElementById('badge-disetujui').textContent = disetujui;
    document.getElementById('badge-ditolak').textContent   = ditolak;
}


function getInisial(nama) {
    return nama.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase();
}


// ─────────────────────────────────────────────
// 4. FETCH PENGAJUAN DARI CONTROLLER
// ─────────────────────────────────────────────
async function fetchPengajuan() {
    if (isLoading) return;
    isLoading = true;
    tampilkanLoading(true);

    try {
        const res  = await fetch(API_PENGAJUAN, { credentials: 'include' });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);

        const json = await res.json();
        if (!json.data) throw new Error(json.errors_messages || 'Gagal memuat data');

        // Simpan ke state
        dataPengajuan = json.data.pengajuan;

        updateBadge();
        renderKartu();

    } catch (err) {
        console.error('[fetchPengajuan]', err);
        tampilkanError(err.message);
    } finally {
        isLoading  = false;
        tampilkanLoading(false);
    }
}

// ─────────────────────────────────────────────
// 5. KIRIM AKSI KE CONTROLLER (approve/reject)
// ─────────────────────────────────────────────
async function kirimAksi(pengajuanId, aksi, catatanAdmin = null) {
    try {
        const res  = await fetch(API_AKSI, {
            method:      'POST',
            credentials: 'include',
            body: JSON.stringify({
                pengajuan_id:  pengajuanId,
                aksi:          aksi,
                catatan_admin: catatanAdmin,
            }),
        });

        // if (!res.ok) throw new Error(`HTTP ${res.status}`);

        const json = await res.json();
        if (!json.data) throw new Error(json.errors_messages || 'Aksi gagal');

        // Update state lokal agar tidak perlu fetch ulang
        dataPengajuan = dataPengajuan.map(p =>
            p.id === pengajuanId
                ? { ...p, status: aksi === 'approved' ? 'disetujui' : 'ditolak' }
                : p
        );

        updateBadge();
        renderKartu();

    } catch (err) {
        console.error('[kirimAksi]', err);
        alert("Gagal memuat : " + err.message);
    }
}

// ─────────────────────────────────────────────
// 6. KIRIM BULK AKSI KE CONTROLLER
// ─────────────────────────────────────────────
async function kirimBulkAksi(aksi, catatanAdmin = null) {
    try {
        const res  = await fetch(API_BULK, {
            method:      'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                aksi:          aksi,
                catatan_admin: catatanAdmin,
            }),
        });

        // if (!res.ok) throw new Error(`HTTP ${res.status}`);

        const json = await res.json();
        if (!json.data) throw new Error(json.errors_messages || 'Bulk aksi gagal');

        // Update semua yang pending di state lokal
        dataPengajuan = dataPengajuan.map(p =>
            p.status === 'pending'
                ? { ...p, status: aksi === 'approved' ? 'disetujui' : 'ditolak' }
                : p
        );

        updateBadge();
        renderKartu();

    } catch (err) {
        console.error('[kirimBulkAksi]', err.message);
        alert("Gagal memuat : " + err.message);
    }
}

// ─────────────────────────────────────────────
// 7. HELPERS UI
// ─────────────────────────────────────────────
function tampilkanLoading(show) {
    const list = document.getElementById('pengajuan-list');
    if (show) {
        list.innerHTML = `
            <div class="text-center py-5 text-muted">
                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                Memuat pengajuan...
            </div>`;
    }
}

function tampilkanError(pesan) {
    document.getElementById('pengajuan-list').innerHTML = `
        <div class="text-center py-5 text-danger">
            <i class="bi bi-exclamation-circle me-2"></i>${pesan}
            <br>
            <button class="btn btn-sm btn-outline-primary mt-2"
                    onclick="fetchPengajuan()">
                <i class="bi bi-arrow-clockwise"></i> Coba lagi
            </button>
        </div>`;
}

// ─────────────────────────────────────────────
// 8. RENDER KARTU
// ─────────────────────────────────────────────
function renderKartu() {
    const list       = document.getElementById('pengajuan-list');
    const emptyState = document.getElementById('empty-state');

    // Filter + Search lokal
    const filtered = dataPengajuan.filter(p => {
        const matchStatus = p.status === filterStatus;  // ← tambahkan ini
        const matchFilter = filterAktif === 'semua' || p.jenis === filterAktif;
        const matchSearch = !searchQuery
            || p.nama.toLowerCase().includes(searchQuery)
            || p.dept.toLowerCase().includes(searchQuery)
            || p.alasan.toLowerCase().includes(searchQuery);
        return matchStatus && matchFilter && matchSearch;
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

        // Tombol aksi atau badge status
        const actionHTML = p.status === 'pending'
            ? `<button class="btn-tolak btn-action-tolak" data-id="${p.id}">
                   <i class="bi bi-x-lg"></i> Tolak
               </button>
               <button class="btn-setujui btn-action-setujui" data-id="${p.id}">
                   <i class="bi bi-check2-square"></i> Setujui
               </button>`
            : p.status === 'approved'
            ? `<span class="status-badge status-disetujui"><i class="bi bi-check-circle-fill"></i> Disetujui</span>`
            : `<span class="status-badge status-ditolak"><i class="bi bi-x-circle-fill"></i> Ditolak</span>`;

        // Lampiran
        const lampiranHTML = p.lampiran
            ? `<span class="detail-value">
                   <i class="bi bi-paperclip"></i> ${p.lampiran}
               </span>`
            : `<span class="detail-value" style="color:var(--muted);font-weight:500;">Tidak ada</span>`;

        card.innerHTML = `
            <!-- Header Row -->
            <div class="card-header-row">
                <div class="karyawan-cell">
                    <div class="jadwal-avatar avatar-default">
                      ${p.foto_profil
                        ? `<img src="${p.foto_profil}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;"/>`
                        : getInisial(p.nama)}
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="card-name">${p.nama}</div>
                    <div class="card-dept">${p.dept} &bull; ${p.waktu}</div>
                </div>
                <span class="tag ${getTagClass(p.jenis)}">● ${LABEL_JENIS[p.jenis] ?? p.nama_jenis}</span>
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
            <div class="card-alasan">"${p.alasan}"</div>
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
// 9. MODAL KONFIRMASI
// ─────────────────────────────────────────────
const modalEl    = document.getElementById('modalKonfirmasi');
const modalMsg   = document.getElementById('modal-message');
const btnKonfirm = document.getElementById('btnKonfirmAksi');
const bsModal    = new bootstrap.Modal(modalEl);

function bukaModal(type, id) {
    pendingAction = { type, id };

    if (type === 'tolak') {
        const p = dataPengajuan.find(x => x.id === id);
        modalMsg.textContent   = `Tolak pengajuan izin dari ${p.nama}?`;
        btnKonfirm.className   = 'btn-modal-konfirm danger';
        btnKonfirm.textContent = 'Ya, Tolak';
    } else if (type === 'setujui') {
        const p = dataPengajuan.find(x => x.id === id);
        modalMsg.textContent   = `Setujui pengajuan izin dari ${p.nama}?`;
        btnKonfirm.className   = 'btn-modal-konfirm';
        btnKonfirm.textContent = 'Ya, Setujui';
    } else if (type === 'tolakSemua') {
        modalMsg.textContent   = `Tolak SEMUA pengajuan yang sedang pending?`;
        btnKonfirm.className   = 'btn-modal-konfirm danger';
        btnKonfirm.textContent = 'Ya, Tolak Semua';
    } else if (type === 'setujuiSemua') {
        modalMsg.textContent   = `Setujui SEMUA pengajuan yang sedang pending?`;
        btnKonfirm.className   = 'btn-modal-konfirm';
        btnKonfirm.textContent = 'Ya, Setujui Semua';
    }

    bsModal.show();
}

// Konfirmasi aksi — kirim ke controller via API
btnKonfirm.addEventListener('click', async () => {
    if (!pendingAction) return;
    const { type, id } = pendingAction;

    // Disable tombol saat loading
    btnKonfirm.disabled    = true;
    btnKonfirm.textContent = 'Memproses...';

    if (type === 'tolak') {
        await kirimAksi(id, 'rejected');
    } else if (type === 'setujui') {
        await kirimAksi(id, 'approved');
    } else if (type === 'tolakSemua') {
        await kirimBulkAksi('rejected');
    } else if (type === 'setujuiSemua') {
        await kirimBulkAksi('approved');
    }

    pendingAction          = null;
    btnKonfirm.disabled    = false;
    bsModal.hide();

    // window.location.reload()
});

// ─────────────────────────────────────────────
// 10. SEARCH
// ─────────────────────────────────────────────
document.getElementById('searchInput').addEventListener('input', function () {
    searchQuery = this.value.toLowerCase().trim();
    renderKartu();
});

// ─────────────────────────────────────────────
// 11. FILTER DROPDOWN
// ─────────────────────────────────────────────
document.querySelectorAll('.filter-option').forEach(item => {
    item.addEventListener('click', function (e) {
        e.preventDefault();
        filterAktif = this.dataset.value;
        document.getElementById('filterLabel').textContent = this.textContent;
        renderKartu(); // filter lokal, tidak fetch ulang
    });
});

// ─────────────────────────────────────────────
// 12. BULK ACTIONS
// ─────────────────────────────────────────────
document.getElementById('btnTolakSemua').addEventListener('click', () => {
    bukaModal('tolakSemua');
});

document.getElementById('btnSetujuiSemua').addEventListener('click', () => {
    bukaModal('setujuiSemua');
});

// ─────────────────────────────────────────────
// 13. INIT
// ─────────────────────────────────────────────
// ─────────────────────────────────────────────
// EVENT: TAB STATUS
// ─────────────────────────────────────────────
document.querySelectorAll('#tabStatus .nav-link').forEach(tab => {
    tab.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelectorAll('#tabStatus .nav-link').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        filterStatus = this.dataset.status;
        renderKartu();
    });
});

fetchPengajuan();

