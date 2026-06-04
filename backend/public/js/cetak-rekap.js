'use strict';

const WEB_URI  = document.querySelector('meta[name="base-url"]').getAttribute('content');
const API_URI  = WEB_URI + 'api';
const API_REKAP = API_URI + '/rekap';

// Ambil parameter dari URL
const params   = new URLSearchParams(window.location.search);
const bulan    = params.get('bulan')  || getBulanSekarang();
const divisi   = params.get('divisi') || 'semua';
const divisiLabel = params.get('divisi_label') || 'Semua Divisi';

function getBulanSekarang() {
  const now = new Date();
  return `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;
}

function formatTanggalIndonesia(dateStr) {
  const bulanNama = [
    '', 'Januari','Februari','Maret','April','Mei','Juni',
    'Juli','Agustus','September','Oktober','November','Desember'
  ];
  const [year, month] = dateStr.split('-');
  return `${bulanNama[parseInt(month)]} ${year}`;
}

function formatTanggalHariIni() {
  const bulanNama = [
    'Januari','Februari','Maret','April','Mei','Juni',
    'Juli','Agustus','September','Oktober','November','Desember'
  ];
  const now = new Date();
  return `${now.getDate()} ${bulanNama[now.getMonth()]} ${now.getFullYear()}`;
}

function generateNoDok(bulanStr) {
  const [year, month] = bulanStr.split('-');
  return `NK/RK/${year}/${month}`;
}

function getPctClass(pct) {
  if (pct >= 90) return 'pct-high';
  if (pct >= 75) return 'pct-med';
  return 'pct-low';
}

function getBarColor(pct) {
  if (pct >= 90) return '#3DB562';
  if (pct >= 75) return '#d97706';
  return '#e03b3b';
}

// Isi header informasi
function isiHeader(label, hariKerja) {
  const periodeLabel = formatTanggalIndonesia(bulan);

  document.getElementById('doc-no').textContent      = generateNoDok(bulan);
  document.getElementById('doc-periode').textContent  = periodeLabel;
  document.getElementById('doc-tanggal').textContent  = formatTanggalHariIni();
  document.getElementById('info-periode').textContent = periodeLabel;
  document.getElementById('info-hari-kerja').textContent = hariKerja + ' hari';
  document.getElementById('info-divisi').textContent  = divisiLabel;
}

// Isi insight cards
function isiInsight(insight) {
  document.getElementById('ins-total').textContent     = insight.total_karyawan;
  document.getElementById('ins-kehadiran').textContent = insight.rata_kehadiran + '%';
  document.getElementById('ins-terlambat').textContent = insight.rata_terlambat;
  document.getElementById('ins-alpha').textContent     = insight.rata_alpha;
  document.getElementById('ins-izin').textContent      = insight.total_izin;
  document.getElementById('info-total-karyawan').textContent =
    insight.total_karyawan + ' karyawan';
}

// Render tabel karyawan
function renderTabel(karyawan) {
  const tbody = document.getElementById('tabel-body');

  // Filter divisi jika bukan semua
  const filtered = divisi === 'semua'
    ? karyawan
    : karyawan.filter(k => k.divisi_id === divisi || k.divisi === divisiLabel);

  if (filtered.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="8" style="text-align:center;padding:20px;color:#9ca3af">
          Tidak ada data karyawan
        </td>
      </tr>`;
    return;
  }

  tbody.innerHTML = filtered.map((k, i) => `
    <tr>
      <td>${i + 1}</td>
      <td>
        <strong>${k.nama}</strong><br>
        <span style="color:#9ca3af;font-size:10px">${k.nip}</span>
      </td>
      <td><span class="badge-divisi">${k.divisi}</span></td>
      <td style="text-align:center">${k.hadir}</td>
      <td style="text-align:center">${k.terlambat}</td>
      <td style="text-align:center">${k.izin}</td>
      <td style="text-align:center">${k.absen}</td>
      <td style="text-align:center">
        <span class="${getPctClass(k.pct)}">${k.pct}%</span>
        <div class="bar-wrap">
          <div class="bar-fill" style="width:${k.pct}%;background:${getBarColor(k.pct)}"></div>
        </div>
      </td>
    </tr>
  `).join('');
}

// Fetch data dari API
async function loadData() {
  try {
    const res  = await fetch(`${API_REKAP}?bulan=${bulan}`, { credentials: 'include' });
    if (!res.ok) throw new Error('Gagal memuat data');

    const json = await res.json();
    if (!json.data) throw new Error(json.errors_messages || 'Data kosong');

    const data = json.data;

    isiHeader(data.label, data.hari_kerja);
    isiInsight(data.insight);
    renderTabel(data.karyawan);

  } catch (err) {
    document.getElementById('tabel-body').innerHTML = `
      <tr>
        <td colspan="8" style="text-align:center;padding:20px;color:#e03b3b">
          Gagal memuat data: ${err.message}
        </td>
      </tr>`;
    console.error(err);
  }
}

loadData();