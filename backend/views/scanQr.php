<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Scan QR Absensi</title>

  <link rel="stylesheet" href="<?= pathCss('scanQr') ?>"/>
  <meta name="qr_token" content="<?= SECRET_QR ?>">


</head>
<body>

  <div class="container">

    <!-- HEADER -->
    <div class="header">

      <div class="company">
        CV. NAFIHAKA Creative
      </div>

      <div class="header-right">
        <div id="dateText"></div>
        <div class="clock" id="clock"></div>
      </div>

    </div>

    <!-- CONTENT -->
    <div class="content">

      <!-- LEFT -->
      <div class="left-card">

        <div class="scanner-label">
          SCAN UNTUK ABSENSI
        </div>

        <div class="scan-title">
          Arahkan Kamera ke QR Code
        </div>

        <div class="scan-subtitle">
          QR otomatis berubah setiap 5 detik
        </div>

        <div class="qr-wrapper">
          <img
          id="qrImage"
          src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=TOKEN_1"
          />
        </div>

        <div class="progress">
          <div class="progress-bar" id="progressBar"></div>
        </div>

        <div class="expired-text" id="expiredText">
          refresh dalam 5 detik
        </div>

        <div class="info-box">

          <div class="info-item">
            <div class="info-label">SHIFT</div>
            <div class="info-value">SHIFT PAGI</div>
          </div>

          <div class="info-item">
            <div class="info-label">RUANG</div>
            <div class="info-value">BLOK - 12D</div>
          </div>

          <div class="info-item">
            <div class="info-label">TANGGAL</div>
            <div class="info-value">Rabu, 25 Feb 2026</div>
          </div>

          <div class="status">
            AKTIF
          </div>

        </div>

      </div>

      <!-- RIGHT -->
      <div class="right-side">

        <!-- RECAP -->
        <div class="card">

          <div class="card-title">
            REKAP HARI INI
          </div>

          <div class="recap-grid">

            <div class="recap-item">
              <div class="recap-number green">221</div>
              <div class="recap-label">Hadir</div>
            </div>

            <div class="recap-item">
              <div class="recap-number red">18</div>
              <div class="recap-label">Terlambat</div>
            </div>

            <div class="recap-item">
              <div class="recap-number blue">10</div>
              <div class="recap-label">Izin</div>
            </div>

            <div class="recap-item">
              <div class="recap-number orange">221</div>
              <div class="recap-label">Pulang</div>
            </div>

          </div>

        </div>

        <!-- SCAN TERBARU -->
        <div class="card">

          <div class="card-title">
            SCAN TERBARU
          </div>

          <div class="scan-item">

            <div class="scan-left">
              <div class="avatar">LN</div>

              <div>
                <div class="scan-name">Lutfi Nugraha</div>
                <div class="scan-time">08:00</div>
              </div>
            </div>

            <div class="badge success">
              MASUK
            </div>

          </div>

          <div class="scan-item">

            <div class="scan-left">
              <div class="avatar">LA</div>

              <div>
                <div class="scan-name">Lingga Aulia</div>
                <div class="scan-time">08:10</div>
              </div>
            </div>

            <div class="badge warning">
              TERLAMBAT
            </div>

          </div>

          <div class="scan-item">

            <div class="scan-left">
              <div class="avatar">LN</div>

              <div>
                <div class="scan-name">Lutfi Nugraha</div>
                <div class="scan-time">08:40</div>
              </div>
            </div>

            <div class="badge success">
              MASUK
            </div>

          </div>

          <div class="scan-item" style="border:none;">

            <div class="scan-left">
              <div class="avatar">LA</div>

              <div>
                <div class="scan-name">Lingga Aulia</div>
                <div class="scan-time">09:00</div>
              </div>
            </div>

            <div class="badge warning">
              TERLAMBAT
            </div>

          </div>

        </div>

      </div>

    </div>

  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>
  <script>
    // JAM
    function updateClock(){
      const now = new Date();

      const time = now.toLocaleTimeString('id-ID');
      const date = now.toLocaleDateString('id-ID', {
        weekday:'long',
        day:'numeric',
        month:'long',
        year:'numeric'
      });

      document.getElementById('clock').innerHTML = time;
      document.getElementById('dateText').innerHTML = date;
    }

    setInterval(updateClock, 1000);
    updateClock();
    
    // Kode Secret
    const SECRET = document.querySelector('meta[name="qr_token"]').getAttribute('content');

    // Generate Token
    function generateToken(lat, lng) {
      const timestamp = Math.floor(Date.now() / 1000);
      const barcode = "ABSEN";

      const payload = `${barcode}|${timestamp}|${lat.toFixed(6)}|${lng.toFixed(6)}`;
      const signature = CryptoJS.SHA256(SECRET).toString();
      return `${payload}|${signature}`;
    }

    // Update QR
    function updateQR(lat, lng) {
      const token = generateToken(lat, lng);
      document.getElementById('qrImage').src =
      `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(token)}`;
    }

  function startQR() {
    if (!navigator.geolocation) {
      alert('Browser tidak mendukung geolocation');
      return;
    }
    navigator.geolocation.getCurrentPosition(
      (pos) => {
        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;
        updateQR(lat, lng);
      setInterval(() => updateQR(lat, lng), 5000); // lat/lng kantor tetap, hanya timestamp yang berubah
    },
    (err) => {
      alert('Gagal ambil lokasi: ' + err.message);
    },
    { enableHighAccuracy: true }
    );
  }

  startQR();
</script>

</body>
</html>