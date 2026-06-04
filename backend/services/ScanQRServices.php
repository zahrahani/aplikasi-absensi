<?php 

namespace Services;

use Models\User as UserModel;
use Models\Karyawan as KaryawanModel;
use Models\Divisi as DivisiModel;
use Models\Pengguna as PenggunaModel;
use Models\Absensi as AbsensiModel;
use Models\PengajuanAbsensi as PengajuanAbsensiModel;
use Models\JadwalKaryawan as JadwalKaryawanModel;
use Models\ShiftKerja as ShiftKerjaModel;
use Models\JenisAbsensi as JenisAbsensiModel;

class ScanQRServices {
	private $userModel;
	private $karyawanModel;
	private $divisiModel;
    private $penggunaModel;
    private $absensiModel;
    private $pengajuanAbsensiModel;
    private $jadwalKaryawanModel;
    private $shiftKerjaModel;
    private $jenisAbsensiModel;
    private $approvalHistoryModel;


    /**
     * Constructor
     */
    public function __construct() {
    	$this->userModel = new UserModel();
    	$this->karyawanModel = new KaryawanModel();
    	$this->divisiModel = new DivisiModel();
        $this->penggunaModel = new PenggunaModel();
        $this->absensiModel = new AbsensiModel();
        $this->pengajuanAbsensiModel = new PengajuanAbsensiModel();
        $this->jadwalKaryawanModel = new JadwalKaryawanModel();
        $this->shiftKerjaModel = new ShiftKerjaModel();
        $this->jenisAbsensiModel = new JenisAbsensiModel();

    }

    public function hitungJarak($lat1, $lng1, $lat2, $lng2) {
        // $lat 1 dan $lng 1 punya user
        // $lat 2 dan $lng 2 punya kantor

        // radius bumi dalam meter
        $R = 6371000; 
        $phi1 = deg2rad($lat1);
        $phi2 = deg2rad($lat2);
        $dPhi    = deg2rad($lat2 - $lat1);
        $dLambda = deg2rad($lng2 - $lng1);

        $a = sin($dPhi / 2) * sin($dPhi / 2)
        + cos($phi1) * cos($phi2)
       * sin($dLambda / 2) * sin($dLambda / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        // dalam meter
        return $R * $c; 
    }


    /**
     * Validasi QR apakah sesuai
     */
    public function validateScanQr($errors, $qrCode, $latUser, $lngUser) {
        // status http
        $status = 200;

        // Membagi qrcode
        $parts = explode('|', $qrCode);

        if (count($parts) < 5) {
            $errors['errors_messages'] =  "Format token tidak valid";
            $status = 400;
            return [$errors, $status];
        }


        // Template QR CODE "ABSEN|1779547996|-7.459283|110.213478|705b1d0583004eb73de5db13e14608d07c591503673f1b1c6127f6a0a0a7ed6e"
        $barcode = $parts[0];
        $timestamp = $parts[1];
        $latKantor = $parts[2];
        $lngKantor = $parts[3];
        $signature = $parts[4];
        $batasJarak = 15; // dalam meter
        $batasTokenExpired = 10; // dalam detik

        $payload = $barcode . "|" . $timestamp;

        $serverSignature = hash(
            'sha256',
            \SECRET_QR
        );

        if(!hash_equals($serverSignature, $signature)) {
           $errors['errors_messages'] =  "Token tidak valid";
           $status = 404;
           return [$errors, $status];
        }

        if(time() - $timestamp > $batasTokenExpired) {
            $errors['errors_messages'] =  "Token sudah expired";
            $status = 403;
            return [$errors, $status];
        }

        $jarak = $this->hitungJarak($latUser, $lngUser, $latKantor, $lngKantor);

        if ($jarak > $batasJarak) {
            $errors['errors_messages'] =  "Lokasi kamu terlalu jauh, batas jarak maksimal adalah $batasJarak m";
            $status = 400;
            return [$errors, $status];
        }

        return [$errors, $status];
    }

    /**
     * Validasi Apakah sesuai untuk scan
     */
    public function validateAbsensi($errors, $remember_token) {

    // status response
    $status = 200;

    // cek user berdasarkan token
    $user = $this->penggunaModel
        ->select(['*'])
        ->where('remember_token', $remember_token)
        ->get();

    if (empty($user)) {
        $errors['errors_messages'] = "User tidak ditemukan";
        return [$errors, 404];
    }

    $user = $user[0];


    
    // Cek data karyawan
    $karyawan = $this->karyawanModel
        ->select(['*'])
        ->where('user_id', $user['user_id'])
        ->get();

    if (empty($karyawan)) {
        $errors['errors_messages'] = "Data karyawan tidak ditemukan";
        return [$errors, 404];
    }

    $karyawan = $karyawan[0];


    // Cek jadwal hari ini
    $jadwal = $this->jadwalKaryawanModel
        ->select([
            'jadwal_karyawan.*',
            'shift_kerja.nama_shift',
            'shift_kerja.jam_masuk',
            'shift_kerja.jam_pulang',
            'shift_kerja.batas_telat'
        ])
        ->join(
            'shift_kerja',
            'jadwal_karyawan.shift_id',
            'shift_kerja.shift_id'
        )
        ->where(
            'jadwal_karyawan.user_id',
            $user['user_id']
        )->get();

    if (empty($jadwal)) {
        $errors['errors_messages'] = "Tidak ada jadwal kerja hari ini";
        return [$errors, 404];
    }

    $jadwal = $jadwal[0];


    // 1. Cek apakah ada absensi aktif (masuk tapi belum pulang) ─
    $absensiAktif = $this->absensiModel
        ->select(['*'])
        ->where('user_id', $user['user_id'])
        ->whereRaw("jam_pulang IS NULL")
        ->whereRaw("jam_masuk >= DATE_SUB(NOW(), INTERVAL 24 HOUR)")
        ->get();

    // 2. Cek apakah sudah absensi lengkap hari ini
    $absensiSelesai = $this->absensiModel
        ->select(['*'])
        ->where('user_id', $user['user_id'])
        ->whereRaw("DATE(tanggal) = CURDATE()")
        ->whereRaw("jam_pulang IS NOT NULL")
        ->get();

    // Logika

    // Sudah absen masuk DAN pulang hari ini → tolak
    if (!empty($absensiSelesai)) {
        $errors['errors_messages'] = 'Kamu sudah melakukan absensi masuk dan pulang hari ini';
        return [$errors, 400];
    }

    // Ada absensi aktif (masuk belum pulang) → proses absen pulang
    if (!empty($absensiAktif)) {
        $absensi = $absensiAktif[0];

        // Cek minimal jam kerja
        $jamMasuk      = strtotime($absensi['jam_masuk']);
        $jamSekarang   = time();
        $selisihDetik  = $jamSekarang - $jamMasuk;
        $totalJamKerja = floor($selisihDetik / 3600);
        $batasJamKerja = 0; // dalam jam

        if ($totalJamKerja < $batasJamKerja) {
            $sisaJam = $batasJamKerja - $totalJamKerja;
            $errors['errors_messages'] = "Belum bisa absensi pulang, minimal kerja {$batasJamKerja} jam. "
                . "Anda baru bekerja selama {$totalJamKerja} jam";
            return [$errors, 400];
        }

        // Update jam pulang
        $updatePulang = $this->absensiModel
            ->update(['jam_pulang' => date('Y-m-d H:i:s')])
            ->where('absensi_id', $absensi['absensi_id'])
            ->execute();

        if (!$updatePulang) {
            $errors['errors_messages'] = 'Gagal melakukan absensi pulang';
            return [$errors, 500];
        }

        return [$errors, 200];
    }


    // Cek pengajuan yang sudah di approve
    $pengajuan = $this->pengajuanAbsensiModel
        ->select([
            'pengajuan_absensi.*',
            'jenis_absensi.nama_jenis'
        ])
        ->join(
            'jenis_absensi',
            'pengajuan_absensi.jenis_id',
            'jenis_absensi.jenis_id'
        )
        ->where(
            'pengajuan_absensi.user_id',
            $user['user_id']
        )
        ->where(
            'status_pengajuan',
            'approved'
        )
        ->whereRaw("
            CURDATE()
            BETWEEN tanggal_mulai
            AND tanggal_selesai
        ")
        ->get();

    // Jika ada pengajuan
    if (!empty($pengajuan)) {
        $pengajuan = $pengajuan[0];
        $errors['errors_messages'] = "Kamu sedang {$pengajuan['nama_jenis']}";
        return [$errors, 400];
    }


    // Cek pengajuan yang sudah di approve
    $pengajuan = $this->pengajuanAbsensiModel
        ->select([
            'pengajuan_absensi.*',
            'jenis_absensi.nama_jenis'
        ])
        ->join(
            'jenis_absensi',
            'pengajuan_absensi.jenis_id',
            'jenis_absensi.jenis_id'
        )
        ->where(
            'pengajuan_absensi.user_id',
            $user['user_id']
        )
        ->where(
            'status_pengajuan',
            'pending'
        )
        ->whereRaw("
            CURDATE()
            BETWEEN tanggal_mulai
            AND tanggal_selesai
        ")
        ->get();
        // Jika ada pengajuan
        if (!empty($pengajuan)) {
            $pengajuan = $pengajuan[0];
            $errors['errors_messages'] = "Kamu sedang melakukan pengajuan, hapus terlebih dahulu pengajuan yang pending";
            return [$errors, 400];
        }



    // Cek telat atau tidak
    $jamSekarang = date('H:i:s');

    $jenisId = 'J01'; // hadir
    $terlambatMenit = 0;

    // Jika telat hitung menit telatnya
    if ( strtotime($jamSekarang) > strtotime($jadwal['batas_telat']) ) {
        $jenisId = 'J02';
        $selisihDetik = strtotime($jamSekarang) - strtotime($jadwal['jam_masuk']);
        $terlambatMenit = floor($selisihDetik / 60);
    }

    // Insert absensi masuk
    $insertAbsensi = $this->absensiModel
        ->create([
            'user_id' => $user['user_id'],
            'shift_id' => $jadwal['shift_id'],
            'jenis_id' => $jenisId,
            'tanggal' => date('Y-m-d'),
            'jam_masuk' => date('Y-m-d H:i:s'),
            'jam_pulang' => null,
            'terlambat_menit' => $terlambatMenit,
        ]);

    // Berhasil absensi masuk
    return [$errors, 200];
    }

}