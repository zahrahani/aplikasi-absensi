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
use Models\ApprovalHistory as ApprovalHistoryModel;

use Includes\FileHandler;


class AdminServices {
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

    private $fileHandler;

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
        $this->approvalHistoryModel = new ApprovalHistoryModel();
        $this->fileHandler = new FileHandler();
    }

    /**
     * Mengolah email menjadi username
     */
    public function getUsername($email_) {
    	$parsing = "@gmail.com";

    	$username = str_replace($parsing, "", $email_);

    	return $username;
    }

    /**
     * Mengolah password default berdasarkan nama yang di parsing
     */
    public function getPassword($name_) {
    	return password_hash($name_, PASSWORD_DEFAULT);
    }


    /**
     * Mengolah untuk mendapatkan user id selanjutnya
     */
    public function getUserId() {
    	$id = $this->penggunaModel->count() + 1;
    	$result = 'USR' . str_pad($id, 5, '0', STR_PAD_LEFT);

        return $result;
    }

    /**
     * tambah karyawan
     */
    public function tambahKaryawan ($errors, $old) {
    	    // Cek username sudah ada
        if ($this->userModel->usernameExists($this->getUsername($old['email'])) ) {
            $errors['username'] = 'Username sudah digunakan.';
        }

            // Cek email sudah ada
        if ($this->userModel->emailExists($old['email'])) {
            $errors['email'] = 'Email sudah digunakan.';
        }

        $idKaryawan = $this->getUserId();
        $berhasil = false;


        if (empty($errors)) {
            // Buat user baru

            $userId = $this->userModel->create([
                'username' => $this->getUsername($old['email']),
                'email' => $old['email'],
                'password' => $this->getPassword($this->getUsername($old['email'])),
                'nama_lengkap' => $old['nama_lengkap'],
                'user_id' => $idKaryawan
            ]);

            $karyawanId = $this->karyawanModel->create([
                'user_id' => $idKaryawan,
                'divisi_id' => $old['divisi'],
                'no_handphone' => $old['no_handphone'],
                'status' => 'Aktif',
                'alamat' => $old['alamat'],
                'jabatan_id' => $old['jabatan']
            ]);

            $jadwalKayawanId = $this->jadwalKaryawanModel->create([
                'user_id' => $idKaryawan,
                'shift_id' => 'S01'
            ]);

            $batasJamKerja = 7; // jam

            $jamMasuk = date('Y-m-d H:i:s');
            $jamPulang = date(
                'Y-m-d H:i:s',
                strtotime($jamMasuk . " +{$batasJamKerja} hours")
            );

            $insertAbsensi = $this->absensiModel->create([
                'user_id'           => $idKaryawan,
                'shift_id'          => 'S01',
                'jenis_id'          => 'J01',
                'tanggal'           => date('Y-m-d'),
                'jam_masuk'         => $jamMasuk,
                'jam_pulang'        => $jamPulang,
                'terlambat_menit'   => 0,
            ]);

            if ($userId && $karyawanId && $jadwalKayawanId && $insertAbsensi) {
                $berhasil = true;
            } else {
                $errors['general'] = 'Terjadi kesalahan saat registrasi karyawan.';
            }
        } else {
            $errors['general'] = 'Terjadi kesalahan saat registrasi karyawan.';
        }

        return [$errors, $berhasil];
    }

    /**
     * Update Karyawan
     */
    public function updateKaryawan ($errors, $old) {
        $user = $this->userModel->getByUserId($old['user_id']);
        // Cek username sudah 

        if ($this->userModel->usernameExists($this->getUsername($old['email']), $user['id']) ) {
            $errors['username'] = 'Username sudah digunakan.';
        }

                // Cek email sudah ada
        if ($this->userModel->emailExists($old['email'], $user['id']) ) {
            $errors['email'] = 'Email sudah digunakan.';
        }

        $idKaryawan = $old['user_id'];
        $berhasil = false;


        if (empty($errors)) {
            // Buat user baru

            $userId = $this->penggunaModel->update([
                'username' => $this->getUsername($old['email']),
                'email' => $old['email'],
                'nama_lengkap' => $old['nama_lengkap']
            ])->where('user_id', $idKaryawan)->execute();

            $karyawanId = $this->karyawanModel->update([
                'divisi_id' => $old['divisi'],
                'no_handphone' => $old['no_handphone'],
                'status' => $old['status'],
                'alamat' => $old['alamat'],
                'jabatan_id' => $old['jabatan']
            ])->where('user_id', $idKaryawan)->execute();

            if ($userId && $karyawanId) {
                $berhasil = true;
            } else {
                $errors['general'] = 'Terjadi kesalahan saat update karyawan.';
            }
        } else {
            $errors['general'] = 'Terjadi kesalahan saat update karyawan.';
        }

        return [$errors, $berhasil];
    }

    /**
     * Hapus Karyawan Karyawan
     */
    public function hapusKaryawan($errors, $request) {
        $berhasil  = true;
        $response = [];

        // Cek user ada atau tidak
        $user = $this->penggunaModel
        ->select(['user_id', 'foto_profil'])
        ->where('user_id', $request['user_id'])
        ->get();

        if (empty($user)) {
            $errors['errors_messages'] = 'Akun tidak ditemukan';
            $berhasil = false;
        }

        $userId  = $user[0]['user_id'];
        $fotoProfil = $user[0]['foto_profil'];

        // Hapus foto profil jika ada
        if (!is_null($fotoProfil)) {
            $this->fileHandler->delete($fotoProfil, 'profile');
        }

        $this->approvalHistoryModel
        ->delete()
        ->whereRaw("pengajuan_id IN (
            SELECT pengajuan_id FROM pengajuan_absensi WHERE user_id = '{$userId}'
        )")
        ->execute();

        $this->pengajuanAbsensiModel
        ->delete()
        ->where('user_id', $userId)
        ->execute();

        $this->absensiModel
        ->delete()
        ->where('user_id', $userId)
        ->execute();

        $this->jadwalKaryawanModel
        ->delete()
        ->where('user_id', $userId)
        ->execute();

        $this->karyawanModel
        ->delete()
        ->where('user_id', $userId)
        ->execute();

        $this->penggunaModel
        ->delete()
        ->where('user_id', $userId)
        ->execute();

        return [$errors, $berhasil];
    }

    /**
     * Logout Karyawan Karyawan
     */
    public function logoutKaryawan($errors, $request) {
        $berhasil  = true;
        $response = [];

        // Cek user ada atau tidak
        $user = $this->penggunaModel
        ->select(["*"])
        ->where('user_id', $request['user_id'])
        ->get();


        if (empty($user)) {
            $errors['errors_messages'] = 'Akun tidak ditemukan';
            $berhasil = false;
        }
        $userId  = $user[0]['user_id'];


        $this->penggunaModel
        ->update([
            'remember_token' => null
        ])
        ->where('user_id', $userId)
        ->execute();

        return [$errors, $berhasil];
    }

    /**
     * Mendapatkan Profile Admin
     */
    public function getProfileKaryawan($userId) {
        $admin = $this->penggunaModel->select([
            'users.nama_lengkap',
            'users.username',
            'users.email',
            'users.nama_lengkap',
            'users.foto_profil',
            'users.user_id'
        ])
        ->where('user_id', $userId)
        ->get()[0];


        return $admin;
    }

    /**
     * Proses data rekap per bulan
     */
    public function getRekapForMonth($errors, $bulan) {
        // Status awal response
        $status = 200;
        // Response awal
        $response = [];

        // Ambil & validasi parameter bulan (default: bulan sekarang)
        $bulan = $bulan ?? date('Y-m');
        if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            $bulan = date('Y-m');
        }

        [$year, $month] = explode('-', $bulan);
        $year  = (int) $year;
        $month = (int) $month;

        // Hitung total hari kerja (Senin–Jumat) di bulan tersebut
        $totalHariKerja = 0;
        $totalHari      = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        for ($d = 1; $d <= $totalHari; $d++) {
            $dayOfWeek = date('N', mktime(0, 0, 0, $month, $d, $year));
            if ($dayOfWeek <= 5) {
                $totalHariKerja++;
            }
        }

        // Ambil semua karyawan beserta divisi & jabatan
        $semuaKaryawan = $this->karyawanModel
        ->selectRaw('
            karyawan.user_id,
            users.nama_lengkap,
            users.created_at,
            divisi.divisi_id,
            divisi.nama_divisi,
            users.foto_profil
            ')
        ->join('users',  'users.user_id',    'karyawan.user_id')
        ->join('divisi', 'divisi.divisi_id', 'karyawan.divisi_id')
        ->where('users.role', 'user')
        ->get();

        // Ambil semua absensi bulan ini (semua karyawan sekaligus)
        //    Group by user_id & jenis_id agar bisa dihitung di PHP
        $semuaAbsensi = $this->absensiModel
        ->selectRaw("
            user_id,
            jenis_id,
            COUNT(*) AS jumlah
            ")
        ->whereRaw("MONTH(tanggal) = '{$month}'")
        ->whereRaw("YEAR(tanggal)  = '{$year}'")
        ->groupBy("user_id, jenis_id")
        ->get();

        // Mapping absensi per user_id agar mudah dicari
        //    Hasil: [ 'USR00002' => ['J01' => 3, 'J02' => 1, ...], ... ]
        $absensiMap = [];
        foreach ($semuaAbsensi as $row) {
            $absensiMap[$row['user_id']][$row['jenis_id']] = (int) $row['jumlah'];
        }

        // Ambil daftar divisi untuk dropdown filter di frontend
        $divisiList = $this->divisiModel
        ->select(['divisi_id', 'nama_divisi'])
        ->get();

        // Susun data rekap per karyawan
        $rekapKaryawan = [];
        foreach ($semuaKaryawan as $k) {
            $k['foto_profil'] = $k['foto_profil'] ? 'uploads/profile/' . $k['foto_profil']: null;


            $uid  = $k['user_id'];
            $data = $absensiMap[$uid] ?? [];

            // Hitung hari kerja efektif per karyawan
            $hariKerjaEfektif = $this->hitungHariKerjaEfektif(
                $year,
                $month,
                $k['created_at']  // ← tanggal bergabung dari users.created_at
            );

            // Jika belum bergabung di bulan ini, skip
            if ($hariKerjaEfektif === 0) continue;

            $hadir = (int) ($data['J01'] ?? 0);
            $telat = (int) ($data['J02'] ?? 0);
            $sakit = (int) ($data['J03'] ?? 0);
            $cuti  = (int) ($data['J04'] ?? 0);
            $izin  = $sakit + $cuti;

            $tercatat = $hadir + $telat + $izin;

            // Alpha dihitung dari hari kerja efektif, bukan total bulan
            $alpha = max(0, $hariKerjaEfektif - $tercatat);

            $pct = $hariKerjaEfektif > 0
            ? (int) round(($hadir + $telat) / $hariKerjaEfektif * 100)
            : 0;

            $rekapKaryawan[] = [
                'id'        => $uid,
                'nama'      => $k['nama_lengkap'],
                'foto_profil' => $k['foto_profil'],
                'nip'       => $uid,
                'divisi'    => $k['nama_divisi'],
                'divisi_id' => $k['divisi_id'],
                'hadir'     => $hadir,
                'terlambat' => $telat,
                'izin'      => $izin,
                'absen'     => $alpha,
                'pct'       => $pct,
                'hari_kerja_efektif' => $hariKerjaEfektif, // ← opsional, untuk info
            ];
        }

        // Membuat insight dashboard
        $totalKaryawan    = count($rekapKaryawan);
        $totalIzin        = 0;
        $totalPct         = 0;
        $totalTerlambat   = 0;
        $totalAlpha       = 0;

        foreach ($rekapKaryawan as $r) {
            $totalIzin      += $r['izin'];
            $totalPct       += $r['pct'];
            $totalTerlambat += $r['terlambat'];
            $totalAlpha     += $r['absen'];
        }

        $rataKehadiran = $totalKaryawan > 0 ? round($totalPct / $totalKaryawan, 1) : 0;
        $rataTerlambat = $totalKaryawan > 0 ? round($totalTerlambat / $totalKaryawan, 1) : 0;
        $rataAlpha = $totalKaryawan > 0 ? round($totalAlpha / $totalKaryawan, 1) : 0;



        // Response
        $status = 200;
        $response = [
            'messages' => 'Berhasil',
            'data'     => [
                'bulan'      => sprintf('%04d-%02d', $year, $month),
                'label'      => $this->getLabelBulan($year, $month),
                'hari_kerja' => $totalHariKerja,
                'karyawan'   => $rekapKaryawan,
                'divisi'     => $divisiList,
                'insight'    => [
                    'total_karyawan'  => $totalKaryawan,
                    'rata_kehadiran'  => $rataKehadiran,   // persen, misal 87.4
                    'rata_terlambat'  => $rataTerlambat,   // rata-rata hari terlambat
                    'rata_alpha'      => $rataAlpha,        // rata-rata hari alpha
                    'total_izin'      => $totalIzin,
                ]
            ]
        ];  



        return [$errors, $status, $response];
    }


    /**
     * Detail Absensi Satu Karyawan (Admin)
     * GET /rekap/detail?user_id=USR00002&bulan=2026-05
    */
    public function getRekapDetailKaryawan($errors, $userId, $bulan) {
        // Status awal response
        $status = 200;
        // Response awal
        $response = [];

        // Ambil & validasi parameter
        $targetUserId = $userId ?? null;
        $bulan        = $bulan ?? date('Y-m');

        if (!$targetUserId) {
            $errors['errors_messages'] = 'Parameter user_id wajib diisi';
            $status = 400;
            return [$errors, $status, $response];
        }

        if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            $bulan = date('Y-m');
        }

        [$year, $month] = explode('-', $bulan);
        $year  = (int) $year;
        $month = (int) $month;

        // Data karyawan + divisi + jabatan
        $dataKaryawan = $this->karyawanModel
        ->selectRaw('
            users.user_id,
            users.nama_lengkap,
            users.email,
            karyawan.no_handphone,
            karyawan.status,
            divisi.nama_divisi,
            jabatan.nama_jabatan,
            users.foto_profil
            ')
        ->join('users',   'users.user_id',      'karyawan.user_id')
        ->join('divisi',  'divisi.divisi_id',   'karyawan.divisi_id')
        ->join('jabatan', 'jabatan.jabatan_id', 'karyawan.jabatan_id')
        ->where('karyawan.user_id', $targetUserId)
        ->get();

        if (empty($dataKaryawan)) {
            $errors['errors_messages'] = 'Karyawan tidak ditemukan';
            $status = 404;
            return [$errors, $status, $response];
        }

        $karyawan = $dataKaryawan[0]; 

        // Rekap ringkasan absensi bulan ini
        $rekapBulanIni = $this->absensiModel
        ->selectRaw("
            COUNT(*) AS total,
            SUM(CASE WHEN jenis_id = 'J01' THEN 1 ELSE 0 END) AS hadir,
            SUM(CASE WHEN jenis_id = 'J02' THEN 1 ELSE 0 END) AS telat,
            SUM(CASE WHEN jenis_id = 'J03' THEN 1 ELSE 0 END) AS sakit,
            SUM(CASE WHEN jenis_id = 'J04' THEN 1 ELSE 0 END) AS cuti
            ")
        ->where('user_id', $targetUserId)
        ->whereRaw("MONTH(tanggal) = '{$month}'")
        ->whereRaw("YEAR(tanggal)  = '{$year}'")
        ->get();

        $rekap = $rekapBulanIni[0] ?? [
            'total' => 0, 'hadir' => 0, 'telat' => 0,
            'sakit' => 0, 'cuti'  => 0,
        ];

        // Hitung total hari kerja bulan ini
        $totalHariKerja      = 0;
        $hariKerjaSudahLewat = 0;
        $totalHari           = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $today               = date('Y-m-d');

        for ($d = 1; $d <= $totalHari; $d++) {
            $tanggal   = sprintf('%04d-%02d-%02d', $year, $month, $d);
            $dayOfWeek = date('N', mktime(0, 0, 0, $month, $d, $year));

            if ($dayOfWeek <= 5) {
                $totalHariKerja++;

                // Hitung hanya hari yang sudah lewat atau hari ini
                if ($tanggal <= $today) {
                    $hariKerjaSudahLewat++;
                }
            }
        }

        // Riwayat absensi harian bulan ini (untuk tabel di modal)
        $riwayatAbsensi = $this->absensiModel
        ->selectRaw('
            absensi.tanggal,
            absensi.jam_masuk,
            absensi.jam_pulang,
            absensi.terlambat_menit,
            jenis_absensi.nama_jenis,
            jenis_absensi.color_hex
            ')
        ->join('jenis_absensi', 'jenis_absensi.jenis_id', 'absensi.jenis_id')
        ->where('absensi.user_id', $targetUserId)
        ->whereRaw("MONTH(absensi.tanggal) = '{$month}'")
        ->whereRaw("YEAR(absensi.tanggal)  = '{$year}'")
        ->get();

        // Pengajuan absensi bulan ini
        $pengajuanBulanIni = $this->pengajuanAbsensiModel
        ->selectRaw('
            pengajuan_absensi.tanggal_mulai,
            pengajuan_absensi.tanggal_selesai,
            pengajuan_absensi.alasan,
            pengajuan_absensi.status_pengajuan,
            pengajuan_absensi.is_urgent,
            jenis_absensi.nama_jenis
            ')
        ->join('jenis_absensi', 'jenis_absensi.jenis_id', 'pengajuan_absensi.jenis_id')
        ->where('pengajuan_absensi.user_id', $targetUserId)
        ->whereRaw("MONTH(pengajuan_absensi.tanggal_mulai) = '{$month}'")
        ->whereRaw("YEAR(pengajuan_absensi.tanggal_mulai)  = '{$year}'")
        ->get();

        // Hitung durasi kerja rata-rata bulan ini
        $totalMenit = 0;
        $hariDenganPulang = 0;
        foreach ($riwayatAbsensi as $a) {
            if ($a['jam_masuk'] && $a['jam_pulang']) {
                $masuk  = new \DateTime($a['jam_masuk']);
                $pulang = new \DateTime($a['jam_pulang']);
                $totalMenit += $masuk->diff($pulang)->h * 60 + $masuk->diff($pulang)->i;
                $hariDenganPulang++;
            }
        }
        $rataRataDurasi = $hariDenganPulang > 0
        ? floor($totalMenit / $hariDenganPulang / 60) . ' jam ' .
        ((int)($totalMenit / $hariDenganPulang) % 60 > 0
            ? (int)($totalMenit / $hariDenganPulang) % 60 . ' menit'
            : '')
        : '–';

        // Format riwayat absensi untuk response
        $absensiFormatted = array_map(function ($a) {
            return [
                'tanggal'         => $a['tanggal'],
                'nama_jenis'      => $a['nama_jenis'],
                'color_hex'       => $a['color_hex'],
                'jam_masuk'       => $a['jam_masuk']
                ? date('H:i', strtotime($a['jam_masuk']))
                : null,
                'jam_pulang'      => $a['jam_pulang']
                ? date('H:i', strtotime($a['jam_pulang']))
                : null,
                'terlambat_menit' => (int) $a['terlambat_menit'],
            ];
        }, $riwayatAbsensi);

        // Izin = sakit + cuti
        $izin  = (int) $rekap['sakit'] + (int) $rekap['cuti'];

        // Alpha = hari kerja yang sudah lewat - semua kehadiran tercatat
        // Pakai $hariKerjaSudahLewat agar bulan yang belum selesai tidak salah hitung
        $alpha = max(0, $hariKerjaSudahLewat - (int) $rekap['total']);

        // Pct = (hadir + telat) / total hari kerja * 100
        $pct = $totalHariKerja > 0
        ? (int) round(
            ((int)$rekap['hadir'] + (int)$rekap['telat'])
            / $totalHariKerja * 100
        )
        : 0;

        // Response
        $status = 200;
        $response = [
            'messages' => 'Berhasil',
            'data'     => [
                'karyawan'  => [
                    'user_id'      => $karyawan['user_id'],
                    'nama_lengkap' => $karyawan['nama_lengkap'],
                    'email'        => $karyawan['email'],
                    'no_handphone' => $karyawan['no_handphone'],
                    'status'       => $karyawan['status'],
                    'nama_divisi'  => $karyawan['nama_divisi'],
                    'nama_jabatan' => $karyawan['nama_jabatan'],
                    'foto_profil' => $karyawan['foto_profil'] ? 'uploads/profile/' . $karyawan['foto_profil']: null
                ],
                'rekap_bulan_ini' => [
                    'hadir'           => (int) $rekap['hadir'],
                    'terlambat'       => (int) $rekap['telat'],
                    'izin'            => $izin,
                    'alpha'           => $alpha,
                    'pct'             => $pct,
                    'total_hari_kerja' => $totalHariKerja,
                    'rata_rata_durasi' => $rataRataDurasi,
                ],
                'absensi'   => $absensiFormatted,
                'pengajuan' => $pengajuanBulanIni,
            ],
        ];

        return [$errors, $status, $response];
    }


    /**
     * Helper: Label bulan Indonesia dari angka
     * Contoh: getLabelBulan(2026, 5) → "Mei 2026"
    */
    private function getLabelBulan(int $year, int $month): string {
        $namaBulan = [
            1  => 'Januari',   2  => 'Februari', 3  => 'Maret',
            4  => 'April',     5  => 'Mei',       6  => 'Juni',
            7  => 'Juli',      8  => 'Agustus',  9  => 'September',
            10 => 'Oktober',   11 => 'November', 12 => 'Desember',
        ];
        return ($namaBulan[$month] ?? $month) . ' ' . $year;
    }

    /**
     * Get Daftar Pengajuan Izin (Admin)
     * GET /api/validasi-izin
     * GET /api/validasi-izin?jenis=J03
    */
    public function getValidasiIzin($errors, $jenisFilter) {
        // Status awal response
        $status = 200;
        // Response awal
        $response = [];

        // Ambil parameter filter jenis (opsional)
        $jenisFilter = $jenisFilter;

        // 1. Ambil semua pengajuan yang pending beserta data karyawan
        $queryPengajuan = $this->pengajuanAbsensiModel
        ->selectRaw('
            pengajuan_absensi.pengajuan_id,
            pengajuan_absensi.user_id,
            pengajuan_absensi.jenis_id,
            pengajuan_absensi.tanggal_mulai,
            pengajuan_absensi.tanggal_selesai,
            pengajuan_absensi.alasan,
            pengajuan_absensi.lampiran,
            pengajuan_absensi.is_urgent,
            pengajuan_absensi.status_pengajuan,
            pengajuan_absensi.created_at,
            users.nama_lengkap,
            users.foto_profil,
            divisi.nama_divisi,
            jabatan.nama_jabatan,
            jenis_absensi.nama_jenis
            ')
        ->join('users',          'users.user_id',          'pengajuan_absensi.user_id')
        ->join('karyawan',       'karyawan.user_id',       'pengajuan_absensi.user_id')
        ->join('divisi',         'divisi.divisi_id',       'karyawan.divisi_id')
        ->join('jabatan',        'jabatan.jabatan_id',     'karyawan.jabatan_id')
        ->join('jenis_absensi',  'jenis_absensi.jenis_id', 'pengajuan_absensi.jenis_id')
        ->latest();

        // Filter jenis jika ada
        if ($jenisFilter) {
            $queryPengajuan = $queryPengajuan->where('pengajuan_absensi.jenis_id', $jenisFilter);
        }

        $dataPengajuan = $queryPengajuan->get();

        // 2. Format data untuk frontend
        $pengajuanFormatted = array_map(function ($p) {
            // Hitung durasi hari
            $mulai   = new \DateTime($p['tanggal_mulai']);
            $selesai = new \DateTime($p['tanggal_selesai']);
            $durasi  = (int) $mulai->diff($selesai)->days + 1;

            // Format waktu pengajuan (berapa lama yang lalu)
            $waktu = $this->formatWaktuLalu($p['created_at']);

            // Format tanggal tampilan
            $tanggalLabel = $p['tanggal_mulai'] === $p['tanggal_selesai']
            ? date('d M Y', strtotime($p['tanggal_mulai']))
            : date('d M', strtotime($p['tanggal_mulai'])) . ' – ' . date('d M Y', strtotime($p['tanggal_selesai']));

            // Inisial nama
            $kata    = explode(' ', trim($p['nama_lengkap']));
            $inisial = strtoupper(
                substr($kata[0], 0, 1) . (isset($kata[1]) ? substr($kata[1], 0, 1) : '')
            );

            return [
                'id'               => (int) $p['pengajuan_id'],
                'user_id'          => $p['user_id'],
                'nama'             => $p['nama_lengkap'],
                'foto_profil'      => $p['foto_profil'] ? 'uploads/profile/' . $p['foto_profil'] : null,
                'inisial'          => $inisial,
                'dept'             => $p['nama_divisi'] . ' – ' . $p['nama_jabatan'],
                'jenis_id'         => $p['jenis_id'],
                'jenis'            => strtolower($p['nama_jenis']),
                'nama_jenis'       => $p['nama_jenis'],
                'urgent'           => (bool) $p['is_urgent'],
                'tanggal'          => $tanggalLabel,
                'tanggal_mulai'    => $p['tanggal_mulai'],
                'tanggal_selesai'  => $p['tanggal_selesai'],
                'durasi'           => $durasi . ' Hari',
                'lampiran'         => $p['lampiran'],
                'alasan'           => $p['alasan'],
                'status'           => $p['status_pengajuan'],
                'waktu'            => $waktu,
            ];
        }, $dataPengajuan);

        // 3. Hitung total pending untuk badge
        $totalPending = count(array_filter($pengajuanFormatted, fn($p) => $p['status'] === 'pending'));

        // Response
        $status = 200;
        $response = [
            'messages'      => 'Berhasil',
            'data'          => [
                'pengajuan'     => $pengajuanFormatted,
                'total_pending' => $totalPending,
            ],
        ];

        return [$errors, $status, $response];
    }


    /**
     * Approve / Reject Satu Pengajuan (Admin)
     * POST /api/validasi-izin/aksi
     * Body: { pengajuan_id, aksi: 'approved'|'rejected', catatan_admin? }
    */
    public function aksiValidasiIzin($errors, $request) {
        // Status awal response
        $status = 200;
        // Response awal
        $response = [];
        
        // Ambil & validasi body request
        $body          = $request;
        $pengajuanId   = $body['pengajuan_id']  ?? null;
        $aksi          = $body['aksi']           ?? null; // 'approved' atau 'rejected'
        $catatanAdmin  = $body['catatan_admin']  ?? null;

        if (!$pengajuanId || !in_array($aksi, ['approved', 'rejected'])) {
            $errors['errors_messages'] = 'Parameter tidak valid';
            $status = 400;
            return [$errors, $status, $response];
        }

        // 1. Cek pengajuan ada & masih pending
        $pengajuan = $this->pengajuanAbsensiModel
        ->select(['pengajuan_id', 'user_id', 'jenis_id', 'tanggal_mulai', 'tanggal_selesai', 'status_pengajuan'])
        ->where('pengajuan_id', $pengajuanId)
        ->get();

        if (empty($pengajuan)) {
            $errors['errors_messages'] = 'Pengajuan tidak ditemukan';
            $status = 404;
            return [$errors, $status, $response];
        }

        $p = $pengajuan[0];

        if ($p['status_pengajuan'] !== 'pending') {
            $errors['errors_messages'] = 'Pengajuan sudah diproses sebelumnya';
            $status = 409;
            return [$errors, $status, $response];
        }

        $now = date('Y-m-d H:i:s');

        // 2. Update status pengajuan
        $this->pengajuanAbsensiModel
        ->update([
            'status_pengajuan' => $aksi,
            'approved_by'      => 1,
            'approved_at'      => $now,
            'catatan_admin'    => $catatanAdmin,
        ])
        ->where('pengajuan_id', $pengajuanId)
        ->execute();

        // 3. Jika approved buat record absensi untuk setiap hari di rentang tanggal
        if ($aksi === 'approved') {
            $this->buatAbsensiDariPengajuan($p);
        }

        // 4. Simpan ke approval_history
        $actionLabel = $aksi === 'approved' ? 'Pengajuan disetujui admin' : 'Pengajuan ditolak admin';

        $this->approvalHistoryModel->create([
            'pengajuan_id' => $pengajuanId,
            'action_type'  => $aksi,
            'action_label' => $actionLabel,
            'action_time'  => $now,
            'action_by'    => 1,
            'notes'        => $catatanAdmin ?? ($aksi === 'approved' ? 'Admin menyetujui pengajuan' : 'Admin menolak pengajuan'),
        ]);

        // Response
        $status = 200;
        $response = [
            'messages' => $aksi === 'approved' ? 'Pengajuan berhasil disetujui' : 'Pengajuan berhasil ditolak',
            'data'     => [
                'pengajuan_id' => (int) $pengajuanId,
                'aksi'         => $aksi,
            ],
        ];

        return [$errors, $status, $response];
    }


    /**
     * Bulk Approve / Reject Semua Pengajuan Pending (Admin)
     * POST /api/validasi-izin/bulk
     * Body: { aksi: 'approved'|'rejected', catatan_admin? }
    */
    public function bulkValidasiIzin($errors, $request) {
        // Status awal response
        $status = 200;
        // Response awal
        $response = [];

        // Ambil & validasi body request
        $body         = $request;
        $aksi         = $body['aksi']          ?? null;
        $catatanAdmin = $body['catatan_admin'] ?? null;

        if (!in_array($aksi, ['approved', 'rejected'])) {
            $errors['errors_messages'] = 'Parameter aksi tidak valid';
            $status = 400;
            return [$errors, $status, $response];
        }

        // 1. Ambil semua pengajuan yang masih pending
        $semuaPending = $this->pengajuanAbsensiModel
        ->select(['pengajuan_id', 'user_id', 'jenis_id', 'tanggal_mulai', 'tanggal_selesai'])
        ->where('status_pengajuan', 'pending')
        ->get();

        if (empty($semuaPending)) {
            $errors['errors_messages'] = 'Tidak ada pengajuan pending';
            $status = 404;
            return [$errors, $status, $response];
        }

        $now         = date('Y-m-d H:i:s');
        $actionLabel = $aksi === 'approved' ? 'Pengajuan disetujui admin (bulk)' : 'Pengajuan ditolak admin (bulk)';
        $diproses    = 0;

        // 2. Proses satu per satu
        foreach ($semuaPending as $p) {
            // Update status
            $this->pengajuanAbsensiModel
            ->update([
                'status_pengajuan' => $aksi,
                'approved_by'      => 1,
                'approved_at'      => $now,
                'catatan_admin'    => $catatanAdmin,
            ])
            ->where('pengajuan_id', $p['pengajuan_id'])
            ->execute();

            // Jika approved → buat record absensi
            if ($aksi === 'approved') {
                $this->buatAbsensiDariPengajuan($p);
            }

            // Simpan ke approval_history
            $this->approvalHistoryModel->create([
                'pengajuan_id' => $p['pengajuan_id'],
                'action_type'  => $aksi,
                'action_label' => $actionLabel,
                'action_time'  => $now,
                'action_by'    => 1,
                'notes'        => $catatanAdmin ?? $actionLabel,
            ]);

            $diproses++;
        }

        // Response
        $status = 200;
        $response = [
            'messages' => $diproses . ' pengajuan berhasil ' . ($aksi === 'approved' ? 'disetujui' : 'ditolak'),
            'data'     => [
                'aksi'     => $aksi,
                'diproses' => $diproses,
            ],
        ];

        return [$errors, $status, $response];
    }


    /**
     * Helper: Buat record absensi dari pengajuan yang approved
     * Looping setiap hari dari tanggal_mulai sampai tanggal_selesai
    */
    private function buatAbsensiDariPengajuan(array $p): void {
        // Ambil shift karyawan
        $jadwal = $this->jadwalKaryawanModel
        ->select(['shift_id'])
        ->where('user_id', $p['user_id'])
        ->get();

        $shiftId = $jadwal[0]['shift_id'] ?? 'S01';

        $mulai   = new \DateTime($p['tanggal_mulai']);
        $selesai = new \DateTime($p['tanggal_selesai']);

        // Loop setiap hari di rentang tanggal
        while ($mulai <= $selesai) {
            $tanggal = $mulai->format('Y-m-d');

            // Cek apakah sudah ada record absensi di tanggal ini
            $existing = $this->absensiModel
            ->select(['absensi_id'])
            ->where('user_id', $p['user_id'])
            ->where('tanggal',  $tanggal)
            ->get();

            // Jika belum ada, buat record baru
            if (empty($existing)) {
                $this->absensiModel->create([
                    'user_id'  => $p['user_id'],
                    'shift_id' => $shiftId,
                    'jenis_id' => $p['jenis_id'],
                    'tanggal'  => $tanggal,
                ]);
            }

            $mulai->modify('+1 day');
        }
    }


    /**
     * Helper: Format waktu pengajuan menjadi "X jam lalu" / "X hari lalu"
    */
    private function formatWaktuLalu(string $createdAt): string {
        $selisihDetik = time() - strtotime($createdAt);

        if ($selisihDetik < 3600) {
            $menit = max(1, (int) ($selisihDetik / 60));
            return $menit . ' menit lalu';
        }

        if ($selisihDetik < 86400) {
            $jam = (int) ($selisihDetik / 3600);
            return $jam . ' jam lalu';
        }

        $hari = (int) ($selisihDetik / 86400);
        return $hari . ' hari lalu';
    }

    /**
     * Helper: hITUNG HARI KERJA EFEKTIF PER KARYAWAN
     * Mempertimbangkan tanggal bergabung dan bulang yang dipilih
    */

    private function hitungHariKerjaEfektif($year, $month, $tanggalBergabung) {
        $totalHari     = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $bulanSelected = sprintf('%04d-%02d', $year, $month);
        $bulanBergabung= date('Y-m', strtotime($tanggalBergabung));

    // Tentukan tanggal mulai hitung
        if ($bulanBergabung === $bulanSelected) {
        // Bergabung di bulan yang sama  mulai dari tanggal bergabung
            $hariMulai = (int) date('j', strtotime($tanggalBergabung));
        } else if ($bulanBergabung > $bulanSelected) {
        // Belum bergabung di bulan ini  0 hari kerja
            return 0;
        } else {
        // Sudah bergabung sebelum bulan ini  mulai dari tanggal 1
            $hariMulai = 1;
        }

    // Hitung hari kerja dari hariMulai sampai akhir bulan
        $hariKerja = 0;
        for ($d = $hariMulai; $d <= $totalHari; $d++) {
            $dayOfWeek = date('N', mktime(0, 0, 0, $month, $d, $year));
            if ($dayOfWeek <= 5) {
                $hariKerja++;
            }
        }

        return $hariKerja;
    }


    /**
     * Get Jadwal Semua Karyawan (Admin)
     * GET /api/jadwal
    */
    public function getJadwal($errors) {
        // Status awal response
        $status = 200;
        // Response awal
        $response = [];

        $today = date('Y-m-d');

        // 1. Ambil semua karyawan beserta shift aktif mereka
        $semuaKaryawan = $this->karyawanModel
        ->selectRaw('
            karyawan.user_id,
            users.nama_lengkap,
            users.foto_profil,
            divisi.divisi_id,
            divisi.nama_divisi,
            jadwal_karyawan.shift_id,
            shift_kerja.nama_shift,
            shift_kerja.jam_masuk,
            shift_kerja.jam_pulang
            ')
        ->join('users',   'users.user_id',    'karyawan.user_id')
        ->join('divisi',  'divisi.divisi_id', 'karyawan.divisi_id')
        ->join('jadwal_karyawan', 'jadwal_karyawan.user_id', 'karyawan.user_id', 'LEFT')
        ->join('shift_kerja',     'shift_kerja.shift_id',    'jadwal_karyawan.shift_id', 'LEFT')
        ->where('users.role', 'user')
        ->latest()
        ->get();

        // 2. Ambil absensi hari ini untuk semua karyawan
        //    Untuk menentukan status: aktif / selesai / belum
        $absensiHariIni = $this->absensiModel
        ->select(['user_id', 'jam_masuk', 'jam_pulang'])
        ->where('tanggal', $today)
        ->get();

        // 3. Buat map absensi hari ini: user_id → { jam_masuk, jam_pulang }
        $absensiMap = [];
        foreach ($absensiHariIni as $a) {
            $absensiMap[$a['user_id']] = [
                'jam_masuk'  => $a['jam_masuk'],
                'jam_pulang' => $a['jam_pulang'],
            ];
        }

        // 4. Format data karyawan + tentukan status hari ini
        $karyawanFormatted = array_map(function ($k) use ($absensiMap) {
            $uid     = $k['user_id'];
            $absensi = $absensiMap[$uid] ?? null;

            // Tentukan status:
            // aktif   = sudah masuk tapi belum pulang (LOCKED)
            // selesai = sudah masuk dan sudah pulang
            // belum   = belum ada absensi hari ini
            if ($absensi && !empty($absensi['jam_masuk']) && empty($absensi['jam_pulang'])) {
                $status = 'aktif';
            } elseif ($absensi && !empty($absensi['jam_masuk']) && !empty($absensi['jam_pulang'])) {
                $status = 'selesai';
            } else {
                $status = 'belum';
            }

            return [
                'user_id'    => $uid,
                'nama'       => $k['nama_lengkap'],
                'divisi'     => $k['nama_divisi'],
                'divisi_id'  => $k['divisi_id'],
                'foto'       => $k['foto_profil']
                ? 'uploads/profile/' . $k['foto_profil']
                : null,
                'shift_id'   => $k['shift_id'],
                'nama_shift' => $k['nama_shift'],
                'jam_masuk'  => $k['jam_masuk']  ? substr($k['jam_masuk'],  0, 5) : null,
                'jam_pulang' => $k['jam_pulang'] ? substr($k['jam_pulang'], 0, 5) : null,
                'status'     => $status, // aktif | selesai | belum
            ];
        }, $semuaKaryawan);

        // 5. Ambil semua shift untuk pilihan di modal
        $semuaShift = $this->shiftKerjaModel
        ->select(['shift_id', 'nama_shift', 'jam_masuk', 'jam_pulang', 'keterangan'])
        ->get();

        $shiftFormatted = array_map(function ($s) {
            return [
                'shift_id'   => $s['shift_id'],
                'nama_shift' => $s['nama_shift'],
                'jam_masuk'  => substr($s['jam_masuk'],  0, 5),
                'jam_pulang' => substr($s['jam_pulang'], 0, 5),
                'keterangan' => $s['keterangan'],
            ];
        }, $semuaShift);

        // 6. Ambil daftar divisi untuk dropdown filter
        $divisiList = $this->divisiModel
        ->select(['divisi_id', 'nama_divisi'])
        ->get();

        // Response
        $status = 200;
        $response = [
            'messages' => 'Berhasil',
            'data'     => [
                'karyawan' => $karyawanFormatted,
                'shift'    => $shiftFormatted,
                'divisi'   => $divisiList,
            ],
        ];

        return [$errors, $status, $response];
    }


    /**
     * Simpan / Update Jadwal Shift Karyawan (Admin)
     * POST /api/jadwal/simpan
     * Body: { user_id, shift_id }
    */
    public function simpanJadwal($errors, $request) {
        // Status awal response
        $status = 200;
        // Response awal
        $response = [];

        // Ambil & validasi body request
        $body    = $request;
        $userId  = $body['user_id']  ?? null;
        $shiftId = $body['shift_id'] ?? null;

        if (!$userId || !$shiftId) {
            $errors['errors_messages'] = 'Parameter user_id dan shift_id wajib diisi';
            $status = 400;
            return [$errors, $status, $response];
        }

        // 1. Validasi: cek apakah karyawan sedang aktif shift hari ini
        //    Locked = sudah absen masuk tapi belum absen pulang
        $today = date('Y-m-d');

        $absensiHariIni = $this->absensiModel
        ->select(['absensi_id', 'jam_masuk', 'jam_pulang'])
        ->where('user_id', $userId)
        ->where('tanggal',  $today)
        ->get();

        if (!empty($absensiHariIni)) {
            $a = $absensiHariIni[0];

            // Jika sudah masuk tapi belum pulang → LOCKED, tidak boleh diubah
            if (!empty($a['jam_masuk']) && empty($a['jam_pulang'])) {
                $errors['errors_messages'] = 'Shift tidak dapat diubah karena karyawan sedang aktif shift (belum absen pulang)';
                $status = 409;
                return [$errors, $status, $response];
            }
        }

        // 2. Ambil data shift yang dipilih
        $shift = $this->shiftKerjaModel
        ->select(['shift_id', 'nama_shift', 'jam_masuk', 'jam_pulang'])
        ->where('shift_id', $shiftId)
        ->get();

        if (empty($shift)) {
            $errors['errors_messages'] = 'Shift tidak ditemukan';
            $status = 404;
            return [$errors, $status, $response];
        }

        $s = $shift[0];

        // 3. Cek apakah karyawan sudah punya jadwal permanen
        $jadwalAda = $this->jadwalKaryawanModel
        ->select(['jadwal_id'])
        ->where('user_id', $userId)
        ->get();

        if (!empty($jadwalAda)) {
            // Update shift yang ada
            $this->jadwalKaryawanModel
            ->update(['shift_id' => $shiftId])
            ->where('user_id', $userId)
            ->execute();
        } else {
            // Insert jadwal baru
            $this->jadwalKaryawanModel->create([
                'user_id'  => $userId,
                'shift_id' => $shiftId,
            ]);
        }

        // Response
        $status = 200;
        $response = [
            'messages' => 'Jadwal shift berhasil diperbarui',
            'data'     => [
                'user_id'    => $userId,
                'shift_id'   => $s['shift_id'],
                'nama_shift' => $s['nama_shift'],
                'jam_masuk'  => substr($s['jam_masuk'],  0, 5),
                'jam_pulang' => substr($s['jam_pulang'], 0, 5),
            ],
        ];

        return [$errors, $status, $response];
    }

    /**
     * Mendapatkan chart dashboard
    */
    public function getDashboardChart($errors) {
        $status   = 200;
        $response = [];
        $today    = date('Y-m-d');

        // Data mingguan
        $labels       = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $dataMingguan = [
            'labels' => $labels,
            'hadir'  => [],
            'lambat' => [],
            'absen'  => [],
        ];

        // Hitung tanggal Senin minggu ini sebagai acuan
        $seninMingguIni = date('Y-m-d', strtotime('monday this week'));

        for ($i = 0; $i <= 4; $i++) {

            // Tanggal hari ke-i dari Senin minggu ini
            $tanggal = date('Y-m-d', strtotime("$seninMingguIni +$i days"));

            // Jika hari belum tiba, isi 0 semua
            if ($tanggal > $today) {
                $dataMingguan['hadir'][]  = 0;
                $dataMingguan['lambat'][] = 0;
                $dataMingguan['absen'][]  = 0;
                continue;
            }

            // Hitung total karyawan yang sudah bergabung di tanggal tersebut
            $totalKaryawanTanggal = (int) ($this->karyawanModel
                ->selectRaw("COUNT(*) AS total")
                ->join('users', 'karyawan.user_id', 'users.user_id')
                ->whereRaw("DATE(users.created_at) <= '{$tanggal}'")
                ->get()[0]['total'] ?? 0);

            // Ambil rekap absensi di tanggal tersebut
            $rekap = $this->absensiModel
                ->selectRaw("
                    SUM(CASE WHEN jenis_id = 'J01' THEN 1 ELSE 0 END) AS hadir,
                    SUM(CASE WHEN jenis_id = 'J02' THEN 1 ELSE 0 END) AS lambat
                ")
                ->whereRaw("DATE(tanggal) = '{$tanggal}'")
                ->get()[0] ?? ['hadir' => 0, 'lambat' => 0];

            $hadir  = (int) ($rekap['hadir']  ?? 0);
            $lambat = (int) ($rekap['lambat'] ?? 0);

            // Absen dihitung dari karyawan yang sudah bergabung di tanggal tersebut
            $absen  = max(0, $totalKaryawanTanggal - $hadir - $lambat);

            $dataMingguan['hadir'][]  = $hadir;
            $dataMingguan['lambat'][] = $lambat;
            $dataMingguan['absen'][]  = $absen;
        }

        // Ambil rekap absensi hari ini
        $rekapHariIni = $this->absensiModel
            ->selectRaw("
                SUM(CASE WHEN jenis_id = 'J01' THEN 1 ELSE 0 END) AS hadir,
                SUM(CASE WHEN jenis_id = 'J02' THEN 1 ELSE 0 END) AS lambat
            ")
            ->whereRaw("DATE(tanggal) = '{$today}'")
            ->get()[0] ?? ['hadir' => 0, 'lambat' => 0];

        $hadirHariIni  = (int) ($rekapHariIni['hadir']  ?? 0);
        $lambatHariIni = (int) ($rekapHariIni['lambat'] ?? 0);

        // Hitung total karyawan yang sudah bergabung hari ini
        $totalKaryawanHariIni = (int) ($this->karyawanModel
            ->selectRaw("COUNT(*) AS total")
            ->join('users', 'karyawan.user_id', 'users.user_id')
            ->whereRaw("DATE(users.created_at) <= '{$today}'")
            ->get()[0]['total'] ?? 0);

        // Absen hari ini hanya dihitung jika ada yang sudah absen
        $absenHariIni = ($hadirHariIni + $lambatHariIni) > 0
            ? max(0, $totalKaryawanHariIni - $hadirHariIni - $lambatHariIni)
            : 0;

        // Hitung jumlah izin yang masih pending
        $izinPending = (int) ($this->pengajuanAbsensiModel
            ->selectRaw("COUNT(*) AS total")
            ->where('status_pengajuan', 'pending')
            ->get()[0]['total'] ?? 0);

        // Susun response
        $status   = 200;
        $response = [
            'messages' => 'Berhasil',
            'data'     => [
                'mingguan'       => $dataMingguan,
                'hari_ini'       => [
                    'hadir'  => $hadirHariIni,
                    'lambat' => $lambatHariIni,
                    'absen'  => $absenHariIni,
                ],
                'izin_pending'   => $izinPending,
                'total_karyawan' => $totalKaryawanHariIni,
            ],
        ];

        return [$errors, $status, $response];
    }

}