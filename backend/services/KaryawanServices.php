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


class KaryawanServices {
	private $userModel;
	private $karyawanModel;
	private $divisiModel;
    private $penggunaModel;
    private $absensiModel;
    private $pengajuanAbsensiModel;
    private $jadwalKaryawanModel;
    private $shiftKerjaModel;
    private $approvalHistoryModel;
    private $jenisAbsensiModel;
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
     * Dashboard
    */
    public function dashboard($errors, $remember_token) {
            // Status awal response
        $status = 200;
            // Response awal
        $response = [];

            // Cari user berdasarkan token
        $user = $this->penggunaModel
        ->select(['user_id', 'nama_lengkap', 'role', 'created_at'])
        ->where('remember_token', $remember_token)
        ->get();

        if (empty($user)) {
            $errors['errors_messages'] = 'Sesi tidak valid, silakan login ulang';
            $status = 401;
            return [$errors, $status, $response];
        }

        $user    = $user[0];
        $userId  = $user['user_id'];
        $today   = date('Y-m-d');
        $month   = date('m');
        $year    = date('Y');

            // 1. Absensi hari ini
        $absensiHariIni = $this->absensiModel
        ->select([
            'absensi.jam_masuk',
            'absensi.jam_pulang',
            'absensi.terlambat_menit',
            'jenis_absensi.nama_jenis',
            'shift_kerja.jam_masuk    AS shift_masuk',
            'shift_kerja.jam_pulang   AS shift_pulang',
            'shift_kerja.batas_telat',
        ])
        ->join('jenis_absensi', 'absensi.jenis_id',  'jenis_absensi.jenis_id')
        ->join('shift_kerja',   'absensi.shift_id',  'shift_kerja.shift_id')
        ->where('absensi.user_id',  $userId)
        ->where('absensi.tanggal',  $today)
        ->get();

        $absensi = $absensiHariIni[0] ?? null;

            // 2. Jadwal & shift hari ini
        $jadwal = $this->jadwalKaryawanModel
        ->select([
            'jadwal_karyawan.shift_id',
            'shift_kerja.nama_shift',
            'shift_kerja.jam_masuk',
            'shift_kerja.jam_pulang',
            'shift_kerja.batas_telat',
        ])
        ->join('shift_kerja', 'jadwal_karyawan.shift_id', 'shift_kerja.shift_id')
        ->where('jadwal_karyawan.user_id', $userId)
        ->get();

        $shiftHariIni = $jadwal[0] ?? null;

            // 3. Rekap absensi bulan ini
        $rekapBulanIni = $this->absensiModel
        ->selectRaw("
            COUNT(*) AS total,
            SUM(CASE WHEN jenis_id = 'J01' THEN 1 ELSE 0 END) AS hadir,
            SUM(CASE WHEN jenis_id = 'J02' THEN 1 ELSE 0 END) AS telat,
            SUM(CASE WHEN jenis_id = 'J03' THEN 1 ELSE 0 END) AS sakit,
            SUM(CASE WHEN jenis_id = 'J04' THEN 1 ELSE 0 END) AS cuti,
            SUM(CASE WHEN jenis_id = 'J05' THEN 1 ELSE 0 END) AS wfh
            ")
        ->where('user_id', $userId)
        ->whereRaw("MONTH(tanggal) = '{$month}'")
        ->whereRaw("YEAR(tanggal)  = '{$year}'")
        ->get();

        $rekap = $rekapBulanIni[0] ?? [
            'hadir' => 0, 'telat' => 0,
            'sakit' => 0, 'cuti'  => 0, 'wfh' => 0,
        ];

        //  4. Hitung hari kerja bulan ini 
        $totalHariKerja      = 0;
        $hariKerjaSudahLewat = 0;
        $totalHari           = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $today               = date('Y-m-d');
        $tanggalBergabung    = date('Y-m-d', strtotime($user['created_at']));

        // Tentukan hariMulai berdasarkan tanggal bergabung
        $bulanBergabung  = date('Y-m', strtotime($tanggalBergabung));
        $bulanSelected   = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT);

        if ($bulanBergabung === $bulanSelected) {
            $hariMulai = (int) date('j', strtotime($tanggalBergabung));
        } else if ($bulanBergabung > $bulanSelected) {
            $hariMulai = $totalHari + 1; // belum bergabung, 0 hari kerja
        } else {
            $hariMulai = 1;
        }

        for ($d = $hariMulai; $d <= $totalHari; $d++) {
            $tanggal   = "$year-$month-" . str_pad($d, 2, '0', STR_PAD_LEFT);
            $dayOfWeek = date('N', mktime(0, 0, 0, $month, $d, $year));

            if ($dayOfWeek <= 5) {
                $totalHariKerja++;
                if ($tanggal <= $today) {
                    $hariKerjaSudahLewat++;
                }
            }
        }

            // 5. Tentukan status absen
            // Status: "Absen Masuk" / "Absen Pulang" / "Sudah Absen"
        $statusAbsen = 'Absen Masuk';
        if ($absensi) {
            $statusAbsen = is_null($absensi['jam_pulang'])
            ? 'Absen Pulang'
            : 'Sudah Absen';
        }

            // 6. Hitung durasi kerja
        $durasi = '00:00';
        if ($absensi && $absensi['jam_masuk'] && $absensi['jam_pulang']) {
            $masuk  = new \DateTime($absensi['jam_masuk']);
            $pulang = new \DateTime($absensi['jam_pulang']);
            $diff   = $masuk->diff($pulang);
            $durasi = $diff->h . ' jam ' . ($diff->i > 0 ? $diff->i . ' menit' : '');
        }

            // Response
        $status = 200;
        $response = [
            'messages' => 'Berhasil',
            'data'     => [
                'status_absen'     => $statusAbsen,
                'absensi_hari_ini' => [
                    //  Cek $absensi null dulu sebelum akses key-nya
                    'jam_masuk'       => ($absensi && $absensi['jam_masuk'])
                    ? date('H:i', strtotime($absensi['jam_masuk']))
                    : null,
                    'jam_pulang'      => ($absensi && $absensi['jam_pulang'])
                    ? date('H:i', strtotime($absensi['jam_pulang']))
                    : null,
                    'status'          => $absensi['nama_jenis']      ?? null,
                    'terlambat_menit' => $absensi['terlambat_menit'] ?? 0,
                    'durasi'          => $durasi,
                ],
                    // Shift dari jadwal_karyawan
                'shift' => [
                    'nama'       => $shiftHariIni['nama_shift'] ?? null,
                    'jam_masuk'  => ($shiftHariIni && $shiftHariIni['jam_masuk'])
                    ? date('H:i', strtotime($shiftHariIni['jam_masuk']))
                    : null,
                    'jam_pulang' => ($shiftHariIni && $shiftHariIni['jam_pulang'])
                    ? date('H:i', strtotime($shiftHariIni['jam_pulang']))
                    : null,
                    'batas_telat'=> ($shiftHariIni && $shiftHariIni['batas_telat'])
                    ? date('H:i', strtotime($shiftHariIni['batas_telat']))
                    : null,
                ],
                'rekap_bulan_ini' => [
                    'hadir'            => (int) $rekap['hadir'],
                    'telat'            => (int) $rekap['telat'],
                    'sakit'            => (int) $rekap['sakit'],
                    'izin'             => (int) ($rekap['cuti'] + $rekap['wfh']),
                    'alpha'            => max(0, $hariKerjaSudahLewat - (int) $rekap['total']),
                    'total_hari_kerja' => $totalHariKerja,
                ],
            ],
        ];

        return [$errors, $status, $response];
    }

    /**
     * Rekap Laporan pribadi
    */
    public function rekapLaporan($errors, $remember_token, $month, $year) {
    // Status awal response
        $status   = 200;
        $response = [];

        // Cari user berdasarkan token 
        $user = $this->penggunaModel
        ->select(['user_id', 'nama_lengkap', 'role', 'created_at'])
        ->where('remember_token', $remember_token)
        ->get();

        if (empty($user)) {
            $errors['errors_messages'] = 'Sesi tidak valid, silakan login ulang';
            $status = 401;
            return [$errors, $status, $response];
        }

        $userId = $user[0]['user_id'];
        $today  = date('Y-m-d');

        // 1. Rekap statistik
        $rekapRaw = $this->absensiModel
        ->selectRaw("
            SUM(CASE WHEN jenis_id = 'J01' THEN 1 ELSE 0 END) AS hadir,
            SUM(CASE WHEN jenis_id = 'J02' THEN 1 ELSE 0 END) AS telat,
            SUM(CASE WHEN jenis_id = 'J03' THEN 1 ELSE 0 END) AS sakit,
            SUM(CASE WHEN jenis_id = 'J04' THEN 1 ELSE 0 END) AS cuti,
            SUM(CASE WHEN jenis_id = 'J05' THEN 1 ELSE 0 END) AS wfh,
            COUNT(*) AS total
            ")
        ->where('user_id', $userId)
        ->whereRaw("MONTH(tanggal) = '{$month}'")
        ->whereRaw("YEAR(tanggal)  = '{$year}'")
        ->get();

        $rekap = $rekapRaw[0] ?? [
            'hadir' => 0, 'telat' => 0, 'sakit' => 0,
            'cuti'  => 0, 'wfh'   => 0, 'total' => 0,
        ];

        // 2. Hitung hari kerja & alpha 
        $totalHariKerja      = 0;
        $hariKerjaSudahLewat = 0;
        $totalHari           = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $bulanIni            = date('Y-m') === "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT);
        $tanggalBergabung    = date('Y-m-d', strtotime($user[0]['created_at']));

        // Tentukan hariMulai berdasarkan tanggal bergabung
        $bulanBergabung  = date('Y-m', strtotime($tanggalBergabung));
        $bulanSelected   = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT);

        if ($bulanBergabung === $bulanSelected) {
            $hariMulai = (int) date('j', strtotime($tanggalBergabung));
        } else if ($bulanBergabung > $bulanSelected) {
            // Belum bergabung di bulan ini
            $hariMulai = $totalHari + 1;
        } else {
            $hariMulai = 1;
        }

        for ($d = $hariMulai; $d <= $totalHari; $d++) {
            $tanggal   = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-" . str_pad($d, 2, '0', STR_PAD_LEFT);
            $dayOfWeek = date('N', mktime(0, 0, 0, $month, $d, $year));

            if ($dayOfWeek <= 5) {
                $totalHariKerja++;
                if (!$bulanIni || $tanggal <= $today) {
                    $hariKerjaSudahLewat++;
                }
            }
        }

        // 3. Data absensi per hari
        $absensiList = $this->absensiModel
        ->selectRaw("
            absensi.tanggal,
            absensi.jam_masuk,
            absensi.jam_pulang,
            absensi.terlambat_menit,
            jenis_absensi.nama_jenis,
            shift_kerja.nama_shift
            ")
        ->join('jenis_absensi', 'absensi.jenis_id', 'jenis_absensi.jenis_id')
        ->join('shift_kerja',   'absensi.shift_id', 'shift_kerja.shift_id')
        ->where('absensi.user_id', $userId)
        ->whereRaw("MONTH(absensi.tanggal) = '{$month}'")
        ->whereRaw("YEAR(absensi.tanggal)  = '{$year}'")
        ->latest()
        ->get();

        // 4. Hitung total jam kerja & kelompokkan per minggu
        $totalMenit     = 0;
        $attendanceData = [];

        foreach ($absensiList as $row) {
        // Hitung durasi
            $durasi = '-';
            if ($row['jam_masuk'] && $row['jam_pulang']) {
                $masuk  = new \DateTime($row['jam_masuk']);
                $pulang = new \DateTime($row['jam_pulang']);
                $diff   = $masuk->diff($pulang);
                $menit  = ($diff->h * 60) + $diff->i;
                $totalMenit += $menit;
                $durasi = $diff->h . 'j ' . str_pad($diff->i, 2, '0', STR_PAD_LEFT) . 'm';
            }

        // Kelompokkan per minggu
            $dayOfMonth = (int) date('j', strtotime($row['tanggal']));
            $weekNumber = ceil($dayOfMonth / 7);
            $weekLabel  = "Minggu ke $weekNumber";

            $attendanceData[$weekLabel][] = [
                'day'     => date('d', strtotime($row['tanggal'])),
                'dayName' => strtoupper(substr(
                    ['', 'Sen','Sel','Rab','Kam','Jum','Sab','Min']
                    [date('N', strtotime($row['tanggal']))], 0, 3)),
                'masuk'   => $row['jam_masuk']
                ? date('H:i', strtotime($row['jam_masuk']))
                : '--:--',
                'pulang'  => $row['jam_pulang']
                ? date('H:i', strtotime($row['jam_pulang']))
                : '--:--',
                'status'  => $row['nama_jenis'],
                'duration'=> $durasi,
            ];
        }

        // 5. Format total jam kerja
        $totalJam      = intdiv($totalMenit, 60);
        $sisaMenit     = $totalMenit % 60;
        $totalJamKerja = "{$totalJam}j " . str_pad($sisaMenit, 2, '0', STR_PAD_LEFT) . "m";

        // Response 
        $status   = 200;
        $response = [
            'messages' => 'Berhasil',
            'data'     => [
                'rekap' => [
                    'hadir' => (int) $rekap['hadir'],
                    'telat' => (int) $rekap['telat'],
                    'sakit' => (int) $rekap['sakit'],
                    'izin'  => (int) ($rekap['cuti'] + $rekap['wfh']),
                    'alpha' => max(0, $hariKerjaSudahLewat - (int) $rekap['total']),
                    'total_hari_kerja' => $totalHariKerja,
                ],
                'total_jam_kerja' => $totalJamKerja,
                    // cast ke object agar JSON selalu {} bukan []
                'attendance_data' => empty($attendanceData) 
                ? new \stdClass() 
                : $attendanceData,
            ],
        ];

        return [$errors, $status, $response];
    }

    /**
     * Mendapatkan pengajuan karyawan untuk pribadi di sendiri
    */
    public function getPengajuan($errors, $remember_token) {
        $status   = 200;
        $response = [];

    // Auth
        $user = $this->penggunaModel
        ->select(['user_id', 'nama_lengkap'])
        ->where('remember_token', $remember_token)
        ->get();

        if (empty($user)) {
            $errors['errors_messages'] = 'Sesi tidak valid, silakan login ulang';
            $status = 401;
            return [$errors, $status, $response];
        }

        $userId = $user[0]['user_id'];

    // Ambil semua pengajuan + jenis absensi
        $pengajuanList = $this->pengajuanAbsensiModel
        ->selectRaw("
            pengajuan_absensi.pengajuan_id,
            pengajuan_absensi.jenis_id,
            pengajuan_absensi.tanggal_mulai,
            pengajuan_absensi.tanggal_selesai,
            pengajuan_absensi.alasan,
            pengajuan_absensi.is_urgent,
            pengajuan_absensi.status_pengajuan,
            pengajuan_absensi.catatan_admin,
            pengajuan_absensi.created_at,
            jenis_absensi.nama_jenis,
            jenis_absensi.color_hex,
            jenis_absensi.icon_name
            ")
        ->join('jenis_absensi',
            'pengajuan_absensi.jenis_id',
            'jenis_absensi.jenis_id')
        ->where('pengajuan_absensi.user_id', $userId)
        ->latest()
        ->get();

    // Untuk setiap pengajuan, ambil approval history-nya
        $result = [];
        foreach ($pengajuanList as $row) {
            $history = $this->approvalHistoryModel
            ->select([
                'action_type',
                'action_label',
                'action_time',
                'notes',
            ])
            ->where('pengajuan_id', $row['pengajuan_id'])
            ->get();

        // Format tanggal
            $tglMulai   = $row['tanggal_mulai']
            ? date('d M Y', strtotime($row['tanggal_mulai']))
            : '-';
            $tglSelesai = $row['tanggal_selesai']
            ? date('d M Y', strtotime($row['tanggal_selesai']))
            : '-';
            $createdAt  = $row['created_at']
            ? date('d M Y, H:i', strtotime($row['created_at']))
            : '-';

        // Format action_time di history
            $historyFormatted = array_map(function($h) {
                $h['action_time'] = $h['action_time']
                ? date('d M, H:i', strtotime($h['action_time']))
                : '-';
                return $h;
            }, $history);

            $result[] = [
                'pengajuan_id'    => $row['pengajuan_id'],
                'jenis_id'        => $row['jenis_id'],
                'nama_jenis'      => $row['nama_jenis'],
                'color_hex'       => $row['color_hex'],
                'icon_name'       => $row['icon_name'],
                'tanggal_mulai'   => $tglMulai,
                'tanggal_selesai' => $tglSelesai,
                'alasan'          => $row['alasan']       ?? '-',
                'is_urgent'       => (int) $row['is_urgent'],
                'status_pengajuan'=> $row['status_pengajuan'],
                'catatan_admin'   => $row['catatan_admin'] ?? '-',
                'created_at'      => $createdAt,
                'approval_history'=> $historyFormatted,
            ];
        }

        $status   = 200;
        $response = [
            'messages' => 'Berhasil',
            'data'     => $result,
        ];

        return [$errors, $status, $response];
    }

    /**
     * Membuat pengajuan karyawan untuk pribadi di sendiri
    */
    public function buatPengajuan($errors, $remember_token, $request) {
        $status   = 200;
        $response = [];

    // Auth
        $user = $this->penggunaModel
        ->select(['user_id'])
        ->where('remember_token', $remember_token)
        ->get();

        if (empty($user)) {
            $errors['errors_messages'] = 'Sesi tidak valid, silakan login ulang';
            $status = 401;
            return [$errors, $status, $response];
        }

        $userId = $user[0]['user_id'];

    // Validasi input
        if (empty($request['jenis_id'])) {
            $errors['errors_messages'] = 'Jenis pengajuan harus diisi';
            $status = 422;
            return [$errors, $status, $response];
        }

        if (empty($request['tanggal_mulai'])) {
            $errors['errors_messages'] = 'Tanggal mulai harus diisi';
            $status = 422;
            return [$errors, $status, $response];
        }


        // Insert pengajuan
        // Validasi tanggal bentrok 
        $tglMulai = $this->parseTanggal($request['tanggal_mulai']);
        $tglSelesai = $this->parseTanggal($request['tanggal_selesai']);

        if (!$tglMulai) {
            $errors['errors_messages'] = 'Format tanggal mulai tidak valid';
            $status = 422;
            return [$errors, $status, $response];
        }

        if (!$tglSelesai) {
            $errors['errors_messages'] = 'Format tanggal selesai tidak valid';
            $status = 422;  
            return [$errors, $status, $response];
        }

        if ($tglSelesai < $tglMulai) {
            $errors['errors_messages'] = 'Format tanggal selesai tidak valid';
            $status = 422;
            return [$errors, $status, $response];
        }

        if ($tglMulai < date("Y-m-d")) {
            $errors['errors_messages'] = 'Format tanggal selesai tidak valid';
            $status = 422;
            return [$errors, $status, $response];
        }

        // Cek sudah absensi atau belum
        $absensi = $this->absensiModel
            ->select(['*'])
            ->where('user_id', $userId)
            ->whereRaw("
                tanggal = {$tglMulai}
            ")
            ->get();


        // Jika sudah absensi
        if (!empty($absensi)) {

            $absensi = $absensi[0];

            // jika sudah absensi pulang
            if ($absensi['jam_pulang'] != null) {
                $errors['errors_messages'] = "Kamu sudah melakukan absensi hari ini";
                return [$errors, 422, $response];
            }

            $errors['errors_messages'] = "Kamu sedang bekerja saat ini tidak bisa melakukan pengajuan";
            return [$errors, 422, $response];
        }

        // Cek apakah status pending dia ada 3 atau tidak
        $bentrok = $this->pengajuanAbsensiModel
        ->selectRaw("
            pengajuan_absensi.user_id,
            count(*) as jumlah
            ")
        ->where('pengajuan_absensi.user_id', $userId)
        ->where("pengajuan_absensi.status_pengajuan", "pending")
        ->groupBy("pengajuan_absensi.user_id")
        ->get();

        if ( !empty($bentrok) ) {
            if ($bentrok[0]['jumlah'] >= 3) {
                $errors['errors_messages'] = "Batas pengajuan absensi 3 kali sampai kamu atau admin yang menyetujui atau membatalkan pengajuan tersebut";
                return [$errors, 422, $response];
            }
        }

        // Cek apakah ada pengajuan yang bentrok (pending atau approved)
        $bentrok = $this->pengajuanAbsensiModel
        ->selectRaw("
            pengajuan_absensi.pengajuan_id,
            pengajuan_absensi.status_pengajuan,
            pengajuan_absensi.tanggal_mulai,
            pengajuan_absensi.tanggal_selesai,
            jenis_absensi.nama_jenis
            ")
        ->join('jenis_absensi',
            'pengajuan_absensi.jenis_id',
            'jenis_absensi.jenis_id')
        ->where('pengajuan_absensi.user_id', $userId)
        ->whereRaw("pengajuan_absensi.status_pengajuan IN ('pending', 'approved')")
        ->whereRaw("
            '{$tglMulai}' <= pengajuan_absensi.tanggal_selesai
            AND '{$tglSelesai}' >= pengajuan_absensi.tanggal_mulai
            ")
        ->get();


        if (!empty($bentrok)) {
            $existing       = $bentrok[0];
            $statusLama     = $existing['status_pengajuan'];
            $namaJenisLama  = $existing['nama_jenis'];
            $tglMulaiLama   = date('d M Y', strtotime($existing['tanggal_mulai']));
            $tglSelesaiLama = $existing['tanggal_selesai']
            ? date('d M Y', strtotime($existing['tanggal_selesai']))
            : $tglMulaiLama;

            if ($statusLama === 'pending') {
                $errors['errors_messages'] =
                "Anda sudah memiliki pengajuan {$namaJenisLama} pada {$tglMulaiLama} – {$tglSelesaiLama} "
                . "yang masih menunggu persetujuan. Batalkan pengajuan tersebut terlebih dahulu "
                . "sebelum membuat pengajuan baru pada tanggal yang sama.";
            } else {
                $errors['errors_messages'] =
                "Pengajuan {$namaJenisLama} pada {$tglMulaiLama} – {$tglSelesaiLama} "
                . "sudah disetujui. Anda tidak dapat mengajukan permohonan baru "
                . "pada rentang tanggal yang sama.";
            }

            $status = 422;
            return [$errors, $status, $response];
        }




    // Insert pengajuan
        $inserted = $this->pengajuanAbsensiModel->create([
            'user_id'          => $userId,
            'jenis_id'         => $request['jenis_id'],
            'tanggal_mulai'    => $tglMulai,
            'tanggal_selesai'  => $tglSelesai,
            'alasan'           => $request['alasan']          ?? null,
            'is_urgent'        => $request['is_urgent']       ?? 0,
            'status_pengajuan' => 'pending',
        ]);

        if (!$inserted) {
            $errors['errors_messages'] = 'Gagal membuat pengajuan';
            $status = 500;
            return [$errors, $status, $response];
        }

    // Ambil ID yang baru dibuat
        $pengajuanId = $this->pengajuanAbsensiModel->lastInsertId();

    // Insert approval history — created
        $this->approvalHistoryModel->create([
            'pengajuan_id' => $pengajuanId,
            'action_type'  => 'created',
            'action_label' => 'Pengajuan dibuat oleh karyawan',
            'action_time'  => date('Y-m-d H:i:s'),
            'action_by'    => null,
            'notes'        => 'Pengajuan dibuat dari aplikasi',
        ]);

        $status   = 200;
        $response = [
            'messages' => 'Pengajuan berhasil diajukan',
        ];

        return [$errors, $status, $response];
    }

    /**
     * Membatalkan pengajuan karyawan untuk pribadi di sendiri
    */
    public function batalPengajuan($errors, $remember_token, $pengajuanId) {
        $status   = 200;
        $response = [];

    // Auth
        $user = $this->penggunaModel
        ->select(['user_id'])
        ->where('remember_token', $remember_token)
        ->get();

        if (empty($user)) {
            $errors['errors_messages'] = 'Sesi tidak valid, silakan login ulang';
            $status = 401;
            return [$errors, $status, $response];
        }

        $userId = $user[0]['user_id'];


    // Cek pengajuan milik user & masih pending
        $pengajuan = $this->pengajuanAbsensiModel
        ->select(['pengajuan_id', 'status_pengajuan'])
        ->where('pengajuan_id', $pengajuanId)
        ->where('user_id', $userId)
        ->get();

        if (empty($pengajuan)) {
            $errors['errors_messages'] = 'Pengajuan tidak ditemukan';
            $status = 404;
            return [$errors, $status, $response];
        }

        if ($pengajuan[0]['status_pengajuan'] !== 'pending') {
            $errors['errors_messages'] = 'Pengajuan sudah diproses, tidak dapat dibatalkan';
            $status = 422;
            return [$errors, $status, $response];
        }

    // Delete pengajuanAbsensi
        $this->pengajuanAbsensiModel
        ->delete()
        ->where('pengajuan_id', $pengajuanId)
        ->execute();

        // Delete approval history
        $this->approvalHistoryModel
        ->delete()
        ->where("pengajuan_id", $pengajuanId)
        ->execute();

        $status   = 200;
        $response = ['messages' => 'Pengajuan berhasil dibatalkan'];

        return [$errors, $status, $response];
    }


    /**
     * Mendapatkan profile pribadi
    */
    public function getProfileKaryawan($errors, $remember_token) {
        $status   = 200;
        $response = [];

        $user = $this->penggunaModel
        ->select(['*'])
        ->where('remember_token', $remember_token)
        ->get();

        $user   = $user[0];
        $userId = $user['user_id'];

        // Ambil data karyawan + divisi + jabatan
        $karyawan = $this->karyawanModel
        ->selectRaw("
            karyawan.user_id,
            karyawan.no_handphone,
            karyawan.status,
            karyawan.alamat,
            divisi.nama_divisi,
            jabatan.nama_jabatan
            ")
        ->join('divisi',  'karyawan.divisi_id',  'divisi.divisi_id')
        ->join('jabatan', 'karyawan.jabatan_id', 'jabatan.jabatan_id')
        ->where('karyawan.user_id', $userId)
        ->get();

        if (empty($karyawan)) {
            $errors['errors_messages'] = 'Data karyawan tidak ditemukan';
            $status = 404;
            return [$errors, $status, $response];
        }

        $karyawan = $karyawan[0];

        $status   = 200;
        $response = [
            'messages' => 'Berhasil',
            'data'     => [
                'user_id'      => $user['user_id'],
                'username'     => $user['username'],
                'email'        => $user['email'],
                'nama_lengkap' => $user['nama_lengkap'],
                'role'         => $user['role'],
                'foto_profil'  => $user['foto_profil']
                ? 'uploads/profile/' . $user['foto_profil']
                : null,
                'no_handphone' => $karyawan['no_handphone'],
                'status'       => $karyawan['status'],
                'alamat'       => $karyawan['alamat'],
                'nama_divisi'  => $karyawan['nama_divisi'],
                'nama_jabatan' => $karyawan['nama_jabatan'],
            ],
        ];

        return [$errors, $status, $response];
    }

    /**
     * update profile pribadi
    */
    public function updateProfileKaryawan($errors, $remember_token, $request) {
        $status   = 200;
        $response = [];

        $user = $this->penggunaModel
        ->select(['user_id', 'username', 'email'])
        ->where('remember_token', $remember_token)
        ->get();

        $userId = $user[0]['user_id'];

    // Validasi username unik
        if (!empty($request['username'])) {
            $cekUsername = $this->penggunaModel
            ->select(['user_id'])
            ->where('username', $request['username'])
            ->whereRaw("user_id != '{$userId}'")
            ->get();

            if (!empty($cekUsername)) {
                $errors['errors_messages'] = 'Username sudah digunakan';
                $status = 422;
                return [$errors, $status, $response];
            }
        }

    // Validasi email unik─
        if (!empty($request['email'])) {
            $cekEmail = $this->penggunaModel
            ->select(['user_id'])
            ->where('email', $request['email'])
            ->whereRaw("user_id != '{$userId}'")
            ->get();

            if (!empty($cekEmail)) {
                $errors['errors_messages'] = 'Email sudah digunakan';
                $status = 422;
                return [$errors, $status, $response];
            }
        }

    // Update tabel users
        $dataUsers = [];
        if (!empty($request['nama_lengkap'])) $dataUsers['nama_lengkap'] = $request['nama_lengkap'];
        if (!empty($request['username']))     $dataUsers['username']     = $request['username'];
        if (!empty($request['email']))        $dataUsers['email']        = $request['email'];

        if (!empty($dataUsers)) {
            $this->penggunaModel
            ->update($dataUsers)
            ->where('user_id', $userId)
            ->execute();
        }

    // Update tabel karyawan─
        $dataKaryawan = [];
        if (!empty($request['no_handphone'])) $dataKaryawan['no_handphone'] = $request['no_handphone'];
        if (!empty($request['alamat']))       $dataKaryawan['alamat']       = $request['alamat'];

        if (!empty($dataKaryawan)) {
            $this->karyawanModel
            ->update($dataKaryawan)
            ->where('user_id', $userId)
            ->execute();
        }

        $status   = 200;
        $response = ['messages' => 'Profil berhasil diperbarui'];

        return [$errors, $status, $response];
    }

    /**
     * update foto profile pribadi
    */
    public function updateFotoProfileKaryawan($errors, $remember_token) {
        $status   = 200;
        $response = [];

    // Auth
        $user = $this->penggunaModel
        ->select(['user_id', 'foto_profil'])
        ->where('remember_token', $remember_token)
        ->get();

        $userId    = $user[0]['user_id'];
        $fotoLama  = $user[0]['foto_profil'];
        $folder    = 'profile';

    // Cek apakah ada file yang dikirim
        if (!isset($_FILES['foto_profil']) || 
            $_FILES['foto_profil']['error'] === UPLOAD_ERR_NO_FILE) {
            $errors['errors_messages'] = 'File foto tidak ditemukan';
        $status = 400;
        return [$errors, $status, $response];
        }

        // Upload via fileHandler
        $uploadResult = $this->fileHandler->upload($_FILES['foto_profil'], $folder);

        if (!$uploadResult) {
            $errors['errors_messages'] = $this->fileHandler->getFirstError();
            $status = 422;
            return [$errors, $status, $response];
        }

        $namaFile = $uploadResult;

        // Hapus foto lama jika ada
        if (!is_null($fotoLama)) {
            $this->fileHandler->delete($fotoLama, $folder);
        }

        // Update database─
        $berhasil = $this->penggunaModel
        ->update(['foto_profil' => $namaFile])
        ->where('user_id', $userId)
        ->execute();

        if (!$berhasil) {
            $errors['errors_messages'] = 'Gagal menyimpan foto profil';
            $status = 500;
            return [$errors, $status, $response];
        }

        $status   = 200;
        $response = [
            'messages'   => 'Foto profil berhasil diperbarui',
            'foto_profil' => 'uploads/profile/' . $namaFile,
        ];

        return [$errors, $status, $response];
        }

    /**
     * ganti password pribadi
    */
    public function gantiPasswordKaryawan($errors, $remember_token, $request) {
        $status   = 200;
        $response = [];

        $user = $this->penggunaModel
        ->select(['user_id', 'password'])
        ->where('remember_token', $remember_token)
        ->get();

        if (empty($user)) {
            $errors['errors_messages'] = 'Sesi tidak valid, silakan login ulang';
            $status = 401;
            return [$errors, $status, $response];
        }

        $userId          = $user[0]['user_id'];
        $passwordLama    = $user[0]['password'];

    // Validasi input
        if (empty($request['password_lama'])) {
            $errors['errors_messages'] = 'Password lama harus diisi';
            $status = 422;
            return [$errors, $status, $response];
        }

        if (empty($request['password_baru'])) {
            $errors['errors_messages'] = 'Password baru harus diisi';
            $status = 422;
            return [$errors, $status, $response];
        }

        if (strlen($request['password_baru']) < 8) {
            $errors['errors_messages'] = 'Password baru minimal 8 karakter';
            $status = 422;
            return [$errors, $status, $response];
        }

        if ($request['password_baru'] !== $request['konfirmasi_password']) {
            $errors['errors_messages'] = 'Konfirmasi password tidak cocok';
            $status = 422;
            return [$errors, $status, $response];
        }

    // Verifikasi password lama
        if (!password_verify($request['password_lama'], $passwordLama)) {
            $errors['errors_messages'] = 'Password lama tidak sesuai';
            $status = 422;
            return [$errors, $status, $response];
        }

    // Update password─
        $this->penggunaModel
        ->update(['password' => password_hash($request['password_baru'], PASSWORD_BCRYPT)])
        ->where('user_id', $userId)
        ->execute();

        $status   = 200;
        $response = ['messages' => 'Password berhasil diperbarui'];

        return [$errors, $status, $response];
    }

    /**
     * Api untuk logout
    */
    public function logout($errors, $remember_token) {
        $status   = 200;
        $response = [];

        $user = $this->penggunaModel
            ->select(['user_id'])
            ->where('remember_token', $remember_token)
            ->get();

        if (empty($user)) {
            $errors['errors_messages'] = 'Sesi tidak valid';
            $status = 401;
            return [$errors, $status, $response];
        }

        // ✅ Set remember_token menjadi null
        $this->userModel
            ->update(['remember_token' => null])
            ->where('user_id', $user[0]['user_id'])
            ->execute();

        $status   = 200;
        $response = ['messages' => 'Logout berhasil'];

        return [$errors, $status, $response];
    }



    /*
    * Helper untuk parsing tanggal
    */
    public function parseTanggal($tanggal) {

        $formats = [
            'Y-m-d', // 2026-05-29
            'd-m-Y', // 29-05-2026
            'd/m/Y', // 29/05/2026
            'm-d-Y', // 05-29-2026
            'm/d/Y', // 05/29/2026
        ];

        foreach ($formats as $format) {

            $date = \DateTime::createFromFormat($format, $tanggal);

            if (
                $date &&
                $date->format($format) === $tanggal
            ) {
                return $date->format('Y-m-d');
            }
        }

        return false;
    }
}