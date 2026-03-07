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

  // Menambahkan waktu keterangan
  document.getElementById('dateDisplay').textContent = `${HARI[now.getDay()]}, ${now.getDate()} ${BULAN[now.getMonth()]} ${now.getFullYear()}`;
}

// Jalankan fungsi updateClock
updateClock();

// Menjalankan fungsi updateClock setiap 1000 milidetik sekali
setInterval(updateClock, 1000);
