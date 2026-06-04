/* ═══════════════════════════════════════════
   karyawan.js – CV. NAFIHAKA Creative
   Fitur: Clock, Render Tabel, Search, Filter
          Divisi & Status, Pagination,
          Modal Detail, Modal Tambah/Edit
════════════════════════════════════════════ */

'use strict';

// ─────────────────────────────────────────────
// 2. DATA KARYAWAN
// ─────────────────────────────────────────────
let dataKaryawan = [];
const WEB_URI = document.querySelector('meta[name="base-url"]').getAttribute('content');
const API_URI = WEB_URI + 'api';
const KOMPONEN_URI = WEB_URI + 'api/komponen';

async function loadData() {
  let options = {
    method: 'POST',
    headers: {
      'Content-type' : 'application/json'
    },
    body: JSON.stringify({
      csrf_token : document.getElementById('token_csrf').value
    }),

  };

  try {
    const response = await fetch(`${KOMPONEN_URI}/karyawan`, options);
    dataKaryawan = await response.json();

    dataKaryawan = dataKaryawan.map(k => ({
      ...k,
      kehadiran: k.kehadiran ?? 0,
      izinDisetujui: k.izinDisetujui ?? 0,
      keterlambatan: k.keterlambatan ?? 0,
    }));


    updateStatCards();
    renderTabel();

  } catch (err) {
    console.log(err);
  }
}

loadData();


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
  };
  return map[divisi] || 'avatar-default';
}

function formatTanggal(str) {
  const BULAN  = [
    'Januari','Februari','Maret','April','Mei','Juni',
    'Juli','Agustus','September','Oktober','November','Desember'];

    if (!str) return '—';
    const d = new Date(str);
    return `${d.getDate()} ${BULAN[d.getMonth()]} ${d.getFullYear()}`;
  }

  function getStatusBadge(status) {
    const map = {
      Aktif:    { cls: 'status-aktif',    icon: 'bi-circle-fill', label: 'Aktif'     },
      Cuti:     { cls: 'status-cuti',     icon: 'bi-moon-fill',   label: 'Cuti'       },
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
    k.user_id.toLowerCase().includes(q) ||
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
            <div class="jadwal-avatar avatar-default">
                ${k.foto_profil
                    ? `<img src="${WEB_URI + k.foto_profil}" alt="${k.nama}"/>`
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
    <div class="p-3">

  <!-- Header -->
  <div class="d-flex align-items-center gap-3 mb-4">

    <div class="modal-avatar avatar-default">
      ${k.foto_profil
        ? `<img src="${k.foto_profil}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;"/>`
        : getInisial(k.nama)}
    </div>

    <div>
      <div class="fw-bold fs-5 modal-karyawan-name">
        ${k.nama}
      </div>

      <div class="text-muted small modal-karyawan-sub">
        ${k.jabatan} &bull; ${k.divisi}
      </div>

      <div class="mt-2">
        ${getStatusBadge(k.status)}
      </div>
    </div>

  </div>

  <!-- Detail -->
  <div class="vstack gap-3">

    <div class="border rounded-3 p-3">
      <div class="text-muted small mb-1">
        <i class="bi bi-person-vcard me-2"></i>NPK
      </div>
      <div class="fw-semibold">${k.user_id}</div>
    </div>

    <div class="border rounded-3 p-3">
      <div class="text-muted small mb-1">
        <i class="bi bi-envelope me-2"></i>Email
      </div>
      <div class="fw-semibold">${k.email}</div>
    </div>

    <div class="border rounded-3 p-3">
      <div class="text-muted small mb-1">
        <i class="bi bi-telephone me-2"></i>No. HP
      </div>
      <div class="fw-semibold">${k.hp}</div>
    </div>

    <div class="border rounded-3 p-3">
      <div class="text-muted small mb-1">
        <i class="bi bi-calendar-event me-2"></i>Tanggal Bergabung
      </div>
      <div class="fw-semibold">
        ${formatTanggal(k.bergabung)}
      </div>
    </div>

    <div class="border rounded-3 p-3">
      <div class="text-muted small mb-1">
        <i class="bi bi-geo-alt me-2"></i>Alamat
      </div>
      <div class="fw-semibold">
        ${k.alamat}
      </div>
    </div>

  </div>

</div>
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


    fetch(`${KOMPONEN_URI}/showDivisi`).then((e) => e.json()).then(function (e) {
      let selectDivisi = document.getElementById('formDivisi');
      
      selectDivisi.innerHTML = "";


      e.forEach(divisi => {

        let option = document.createElement("option");


        option.value = divisi.divisi_id;
        option.textContent = divisi.nama_divisi;


        if ( k.divisi_id === divisi.divisi_id ) {
          option.setAttribute('selected', true);
        }

        selectDivisi.appendChild(option);

      });

    })

    let options = {
      method: 'POST',
      headers: {
        'Content-type' : 'application/json'
      },
      body: JSON.stringify({
        divisi_id: k.divisi_id
      }),
    }

    fetch(`${KOMPONEN_URI}/showJabatan`, options)
    .then(res => res.json())
    .then(data => {
      let selectJabatan = document.getElementById('formJabatan');
      selectJabatan.innerHTML = "";

      data.forEach(jabatan => {
        let option = document.createElement("option");

        option.value = jabatan.jabatan_id;       
        option.textContent = jabatan.nama_jabatan;

        if (k.jabatan_id === jabatan.jabatan_id) {
          option.selected = true;
        }

        selectJabatan.appendChild(option);
      });
    });

    document.getElementById('formNama').value    = k.nama;
    document.getElementById('formuser_id').value     = k.user_id;
    document.getElementById('formDivisi').value  = k.divisi;
    document.getElementById('formHp').value      = k.hp;
    document.getElementById('formEmail').value   = k.email;
    document.getElementById('formTanggal').value = k.bergabung.split(" ")[0];
    document.getElementById('formStatus').value  = k.status;
    document.getElementById('formAlamat').value  = k.alamat;
  } 
  bsModalForm.show();
}

// Validasi & Simpan Form
document.getElementById('btnSimpan').addEventListener('click', () => {
  const nama    = document.getElementById('formNama').value.trim();
  const user_id     = document.getElementById('formuser_id').value.trim();
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
  if (!nama || !user_id || !divisi || !jabatan || !hp || !email || !tanggal) {
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
      ? { ...k, nama, user_id, divisi, jabatan, hp, email, bergabung: tanggal, status, alamat, inisial }
      : k
      );
  } else {
    // Tambah data baru
    const newId = Math.max(...dataKaryawan.map(k => k.id)) + 1;
    dataKaryawan.push({
      id: newId,
      nama, user_id, divisi, jabatan, hp, email,
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
// 13. Modal Hapus
// ─────────────────────────────────────────────

const modalHapus  = new bootstrap.Modal(document.getElementById('modalHapus'));

document.getElementById('btnModalHapus').addEventListener('click', () => {
  if (!selectedId) return;
  bsModalDetail.hide();
  setTimeout(() => bukaHapus(selectedId), 300);

});


function bukaHapus(id) {
  let hapusListener = null;

  const k = dataKaryawan.find(x => x.id === id);
  if (!k) return;
  document.getElementById('hapus-nama').textContent = k.nama;
  document.getElementById('hapus-id').value         = k.id;
    
  document.getElementById('input_user_id').value = k.user_id;

  modalHapus.show();
}

let selectDivisi = document.getElementById('formDivisi');
let selectJabatan = document.getElementById('formJabatan');

selectDivisi.addEventListener('change', async function (e) {
  let options = {
    method: 'POST',
    headers: {
      'Content-type' : 'application/json'
    },
    body: JSON.stringify({
      divisi_id: selectDivisi.value
    }),
  }

  try {

    let response = await fetch(`${KOMPONEN_URI}/showJabatan`, options);
    let dataJabatan = await response.json();

    selectJabatan.innerHTML = "";

    dataJabatan.forEach(jabatan => {

      let option = document.createElement("option");

      option.value = jabatan.jabatan_id;
      option.textContent = jabatan.nama_jabatan;

      selectJabatan.appendChild(option);

    });

  } catch (Err) {
    console.log(Err);
  }
});

// ─────────────────────────────────────────────
// 13. Modal Logout
// ─────────────────────────────────────────────
const modalLogout = new bootstrap.Modal(document.getElementById('modalLogout'));

document.getElementById('btnModalLogout').addEventListener('click', () => {
  if (!selectedId) return;
  bsModalDetail.hide();
  setTimeout(() => bukaLogout(selectedId), 300);
});

function bukaLogout(id) {
  const k = dataKaryawan.find(x => x.id === id);
  if (!k) return;
  document.getElementById('logout_nama').textContent = k.nama;
  document.getElementById('logout_id').value         = k.id;
    
  document.getElementById('input_user_id_logout').value = k.user_id;

  modalLogout.show();
}




// 3. HELPERS
function getInisial(nama) {
    return nama.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase();
}
