<?php
/**
 * =====================================================
 * CONTROLLER: AuthController
 * Menangani autentikasi (login, register, logout)
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */
namespace Controllers;

use Models\User as UserModel;
use Models\Karyawan as KaryawanModel;
use Models\Divisi as DivisiModel;
use Models\Jabatan as JabatanModel;
use Models\Pengguna as PenggunaModel;
use Models\JenisAbsensi as JenisAbsensiModel;


use Services\AdminServices;

class AdminController {
	private $userModel;
    private $karyawanModel;
    private $divisiModel;
    private $jabatanModel;
    private $penggunaModel;
    private $jenisAbsensiModel;
    private $adminServices;

    /**
     * Constructor
     */
    public function __construct() {
        $this->userModel = new UserModel();
        $this->karyawanModel = new KaryawanModel();
        $this->divisiModel = new DivisiModel();
        $this->jabatanModel = new JabatanModel();
        $this->penggunaModel = new PenggunaModel();
        $this->jenisAbsensiModel = new JenisAbsensiModel();
        $this->adminServices = new AdminServices();
    }

    /**
     * Halaman Dashboard
     */
    public function dashboardView() {
     \viewWithMainTemplate('admin/dashboard', [
      'active' => 'dashboard'
  ]);
 }

   	/**
     * Halaman Rekap
     */
   	public function rekapView() {


   		\viewWithMainTemplate('admin/rekapLaporan', [
   			'active' => 'rekapLaporan',
            'divisis' => $this->divisiModel->select(['*'])->get(),
   		]);
   	}

    /**
     * Halaman Create Karyawan
     */
    public function jadwalKaryawan() {
        $old = $_SESSION['old_messages'] ?? '';

        \viewWithMainTemplate('admin/jadwalKaryawan', [
            'active' => 'jadwalKaryawan',
            'old' => $old
        ]);
    }


   	/**
     * Halaman Validasi
     */
   	public function validasiIzinView() {
        $jenisAbsensi = $this->jenisAbsensiModel->select(['jenis_id', 'nama_jenis'])
        ->where('jenis_id', '!=', 'J01')
        ->where('jenis_id', '!=', 'J02')
        ->get();


   		\viewWithMainTemplate('admin/validasiIzin', [
   			'active' => 'validasiIzin',
            'jenisAbsensi' => $jenisAbsensi
   		]);
   	}

    /**
     * Halaman Karyawan
     */
    public function karyawanView() {
        \viewWithMainTemplate('admin/karyawan', [
            'active' => 'karyawan',
            'divisis' => $this->divisiModel->select(['*'])->get(),
            'jabatans' => $this->jabatanModel->select(['*'])->get(),
        ]);
    }


    /**
     * Halaman Jabatan Divisi
     */
    // public function DivisiJabatanView() {
    //     \viewWithMainTemplate('admin/divisiJabatan', [
    //         'active' => 'divisiJabatan',
    //     ]);
    // }

    /**
     * Halaman Create Karyawan
     */
    public function kCreateView() {
        $old = $_SESSION['old_messages'] ?? '';

        \viewWithMainTemplate('admin/karyawan/create', [
            'active' => 'karyawan',
            'divisis' => $this->divisiModel->select(['*'])->get(),
            'jabatans' => $this->jabatanModel->select(['*'])->get(),
            'old' => $old
        ]);
    }

    /**
     * Halaman Create Karyawan Post
     */
    public function kCreatePost() {
        $errors = [];
        $old = [
            'nama_lengkap' => sanitize($_POST['nama_lengkap'] ?? ''),
            'divisi' => sanitize($_POST['divisi'] ?? ''),
            'jabatan' => sanitize($_POST['jabatan'] ?? ''),
            'no_handphone' => sanitize($_POST['no_handphone'] ?? ''),
            'email' => sanitize($_POST['email'] ?? ''),
            'status' => sanitize($_POST['status'] ?? ''),
            'alamat' => sanitize($_POST['alamat'] ?? '')
        ];

        if ( !validateCSRFToken($_POST['_token']) ) {
            return redirect(\BASE_URL . 'karyawan');    
        }

        // Validasi
        $validator = \validate($_POST);
        $validator->required('nama_lengkap', 'Nama lengkap wajib diisi.')
        ->minLength('nama_lengkap', 3, 'Nama lengkap minimal 3 karakter.')
        ->maxLength('nama_lengkap', 100, 'Nama lengkap maksimal 100 karakter.')
        ->string('nama_lengkap')
        ->required('email', 'Email wajib diisi.')
        ->email('email', 'Format email tidak valid.')
        ->required('divisi', 'Divisi wajib diisi.')
        ->required('jabatan', 'Jabatan wajib diisi.')
        ->required('no_handphone', 'No handphone wajib diisi')
        ->required('status', 'Status wajib diisi')
        ->required('alamat', 'Alamat wajib diisi');


        if ($validator->isValid()) {

            list($errors, $berhasil) = $this->adminServices->tambahKaryawan($errors, $old);

            if (!$berhasil) {
                $errors['general'] = "Terjadi kesalahan saat registrasi karyawan.";
                $_SESSION['errors_messages'] = $errors;
                $_SESSION['old_messages'] = $old;
                \setFlashMessage('danger', $errors['general']);
                return \redirect(\BASE_URL . 'karyawan/create');
            }
        } else {
            $errors = $validator->getErrors();
            $errors['general'] = "Terjadi kesalahan saat registrasi karyawan.";
            $_SESSION['errors_messages'] = $errors;
            $_SESSION['old_messages'] = $old;
            \setFlashMessage('danger', $errors['general']);
            return redirect(\BASE_URL . 'karyawan/create');            
        }

        \setFlashMessage('success', "Akun karyawan berhasil dibuat!");
        $_SESSION['errors_messages'] = "";
        $_SESSION['old_messages'] = "";
        return redirect(\BASE_URL . 'karyawan');
    }

    /**
     * hapus karyawan
     */
    public function hapusKaryawan() {
        $errors = [];
        $old = [
            'user_id' => sanitize($_POST['user_id'] ?? '')
        ];

        list($errors, $berhasil) = $this->adminServices->hapusKaryawan($errors, $old);

    
        if (!$berhasil) {
            $errors['general'] = "Terjadi kesalahan saat menghapus karyawan.";
            $_SESSION['errors_messages'] = $errors;
            $_SESSION['old_messages'] = $old;
            \redirect(\BASE_URL . 'karyawan');
        }
        

        $_SESSION['errors_messages'] = "";
        $_SESSION['old_messages'] = "";
        redirect(\BASE_URL . 'karyawan'); 
    }

    /**
     * logout karyawan
     */
    public function logoutKaryawan() {
        $errors = [];
        $old = [
            'user_id' => sanitize($_POST['user_id'] ?? '')
        ];

        list($errors, $berhasil) = $this->adminServices->logoutKaryawan($errors, $old);

    
        if (!$berhasil) {
            $errors['general'] = "Terjadi kesalahan saat menglogout karyawan.";
            $_SESSION['errors_messages'] = $errors;
            $_SESSION['old_messages'] = $old;
            \redirect(\BASE_URL . 'karyawan');
        }
        

        $_SESSION['errors_messages'] = "";
        $_SESSION['old_messages'] = "";
        redirect(\BASE_URL . 'karyawan'); 
    }

    /**
     * post update karyawan
     */
    public function updateKaryawan () {
       $errors = [];
       $old = [
        'nama_lengkap' => sanitize($_POST['nama_lengkap'] ?? ''),
        'divisi' => sanitize($_POST['divisi'] ?? ''),
        'jabatan' => sanitize($_POST['jabatan'] ?? ''),
        'no_handphone' => sanitize($_POST['no_handphone'] ?? ''),
        'email' => sanitize($_POST['email'] ?? ''),
        'status' => sanitize($_POST['status'] ?? ''),
        'alamat' => sanitize($_POST['alamat'] ?? ''),
        'user_id' => sanitize($_POST['user_id'] ?? '')];

        if ( !validateCSRFToken($_POST['_token']) ) {
            return redirect(\BASE_URL . 'karyawan');    
        }

        // Validasi
        $validator = \validate($_POST);
        $validator->required('nama_lengkap', 'Nama lengkap wajib diisi.')
        ->minLength('nama_lengkap', 3, 'Nama lengkap minimal 3 karakter.')
        ->maxLength('nama_lengkap', 100, 'Nama lengkap maksimal 100 karakter.')
        ->string('nama_lengkap')
        ->required('email', 'Email wajib diisi.')
        ->email('email', 'Format email tidak valid.')
        ->required('divisi', 'Divisi wajib diisi.')
        ->required('jabatan', 'Jabatan wajib diisi.')
        ->required('no_handphone', 'No handphone wajib diisi')
        ->required('status', 'Status wajib diisi')
        ->required('alamat', 'Alamat wajib diisi');

        if ($validator->isValid()) {

            list($errors, $berhasil) = $this->adminServices->updateKaryawan($errors, $old);

            if (!$berhasil) {
                $errors['general'] = "Terjadi kesalahan saat update data karyawan.";
                $_SESSION['errors_messages'] = $errors;
                $_SESSION['old_messages'] = $old;
                return \redirect(\BASE_URL . 'karyawan');
            }
        } else {
            $errors = $validator->getErrors();
            $errors['general'] = "Terjadi kesalahan saat update karyawan.";
            $_SESSION['errors_messages'] = $errors;
            $_SESSION['old_messages'] = $old;
            return \redirect(\BASE_URL . 'karyawan');            
        }

        $_SESSION['errors_messages'] = "";
        $_SESSION['old_messages'] = "";
        return redirect(\BASE_URL . 'karyawan');

    }

    /**
     * Api Rekap Laporan per bulan
     */
    public function getRekapForMonth() {
        $errors = [];

        // mendapatkan rekap
        list($errors, $status, $response) = $this->adminServices->getRekapForMonth($errors, $_GET['bulan'] ?? date('Y-m'));

        if ($status != 200) {
            return \responseJson($errors, $status);
        }

        return \responseJson($response, $status);
    }

    /**
     * Api Rekap dari detail karyawan Laporan per bulan
     */
    public function getRekapDetailKaryawan() {
        $errors = [];

        // mendapatkan rekap
        list($errors, $status, $response) = $this->adminServices->getRekapDetailKaryawan($errors, $_GET['user_id'] ?? null, $_GET['bulan'] ?? date('Y-m'));

        if ($status != 200) {
            return \responseJson($errors, $status);
        }

        return \responseJson($response, $status);
    }

    /**
     * Api mendapatkan validasi izin
     */
    public function getValidasiIzin() {
        $errors = [];

        // Ambil parameter filter jenis (opsional)
        $jenisFilter = $_GET['jenis'] ?? null;
    
        // mendapatkan validasi izin
        list($errors, $status, $response) = $this->adminServices->getValidasiIzin($errors, $jenisFilter);

        if ($status != 200) {
            return \responseJson($errors, $status);
        }

        return \responseJson($response, $status);
    }

    /**
     * Api melakukan aksi accepted atau rejected validasi izin
     */
    public function aksiValidasiIzin() {
        $errors = [];
        $request = \requestJson();

        // melakukan aksi validasi izin
        list($errors, $status, $response) = $this->adminServices->aksiValidasiIzin($errors, $request);

        if ($status != 200) {
            return \responseJson($errors, $status);
        }

        return \responseJson($response, $status);
    }

    /**
     * Api melakukan bulk ke semua accepted atau rejected validasi izin
     */
    public function bulkValidasiIzin() {
        $errors = [];
        $request = \requestJson();
        
        // melakukan aksi validasi izin
        list($errors, $status, $response) = $this->adminServices->bulkValidasiIzin($errors, $request);

        if ($status != 200) {
            return \responseJson($errors, $status);
        }

        return \responseJson($response, $status);
    }

    /**
     * Api untuk mendapatkan jadwal per minggu
     */
    public function getJadwal() {
        $errors = [];
        
        // melakukan aksi validasi izin
        list($errors, $status, $response) = $this->adminServices->getJadwal($errors);

        if ($status != 200) {
            return \responseJson($errors, $status);
        }

        return \responseJson($response, $status);
    }

    /**
     * Api melakukan simpan jadwal
     */
    public function simpanJadwal() {
        $errors = [];
        $request = \requestJson();
        
        // melakukan aksi validasi izin
        list($errors, $status, $response) = $this->adminServices->simpanJadwal($errors, $request);

        if ($status != 200) {
            return \responseJson($errors, $status);
        }

        return \responseJson($response, $status);
    }

    /**
     * Api melakukan mendapatkan dashboard chart
     */
    public function getDashboardChart() {
        $errors = [];
        $request = \requestJson();
        
        // melakukan aksi validasi izin
        list($errors, $status, $response) = $this->adminServices->getDashboardChart($errors);

        if ($status != 200) {
            return \responseJson($errors, $status);
        }

        return \responseJson($response, $status);
    }

    /**
     * Menambahkan divisi
     */
    public function tambahDivisi() {
        $errors = [];
        $old = [
            'nama_divisi' => sanitize($_POST['nama_divisi'] ?? ''),
        ];

        // Validasi input
        $validator = \validate($_POST);
        $validator
            ->required('nama_divisi', 'Nama divisi wajib diisi.')
            ->maxLength('nama_divisi', 50, 'Nama divisi maksimal 50 karakter.');

        if ($validator->isValid()) {

            // Cek duplikat ID
            $cek = $this->divisiModel
            ->select(['divisi_id'])
            ->where('divisi_id', $old['divisi_id'])
            ->get();

            if (!empty($cek)) {
                $errors['divisi_id'] = "ID '{$old['divisi_id']}' sudah digunakan.";
                $errors['general']   = 'Terjadi kesalahan saat menambah divisi.';
                $_SESSION['errors_messages'] = $errors;
                $_SESSION['old_messages']    = $old;
                return \redirect(BASE_URL . 'divisi-jabatan#divisi');
            }

            list($errors, $berhasil) = $this->divisiJabatanServices->tambahDivisi($errors, $old);

            if (!$berhasil) {
                $errors['general']           = 'Terjadi kesalahan saat menambah divisi.';
                $_SESSION['errors_messages'] = $errors;
                $_SESSION['old_messages']    = $old;
                return \redirect(BASE_URL . 'divisi-jabatan#divisi');
            }

        } else {
            $errors            = $validator->getErrors();
            $errors['general'] = 'Terjadi kesalahan saat menambah divisi.';
            $_SESSION['errors_messages'] = $errors;
            $_SESSION['old_messages']    = $old;
            return \redirect(BASE_URL . 'divisi-jabatan#divisi');
        }

        $_SESSION['errors_messages']   = '';
        $_SESSION['old_messages']      = '';
        $_SESSION['flash_success']     = "Divisi '{$old['nama_divisi']}' berhasil ditambahkan.";
        return \redirect(BASE_URL . 'divisi-jabatan#divisi');
    }


    /**
    * Menambahkan jabatan
    */
    public function tambahJabatan() {
        $errors = [];
        $old = [
            'divisi_id'    => sanitize($_POST['divisi_id']    ?? ''),
            'nama_jabatan' => sanitize($_POST['nama_jabatan'] ?? ''),
        ];

        // Validasi input
        $validator = \validate($_POST);
        $validator
            ->required('divisi_id',    'Divisi wajib dipilih.')
            ->required('nama_jabatan', 'Nama jabatan wajib diisi.')
            ->maxLength('nama_jabatan', 50, 'Nama jabatan maksimal 50 karakter.');

        if ($validator->isValid()) {

            // Cek duplikat ID jabatan
            $cekJabatan = $this->jabatanModel
            ->select(['jabatan_id'])
            ->where('jabatan_id', $old['jabatan_id'])
            ->get();

            if (!empty($cekJabatan)) {
                $errors['jabatan_id'] = "ID '{$old['jabatan_id']}' sudah digunakan.";
                $errors['general']    = 'Terjadi kesalahan saat menambah jabatan.';
                $_SESSION['errors_messages'] = $errors;
                $_SESSION['old_messages']    = $old;
                return \redirect(BASE_URL . 'divisi-jabatan#jabatan');
            }

            // Cek divisi_id valid (foreign key)
            $cekDivisi = $this->divisiModel
            ->select(['divisi_id'])
            ->where('divisi_id', $old['divisi_id'])
            ->get();

            if (empty($cekDivisi)) {
                $errors['divisi_id'] = 'Divisi yang dipilih tidak ditemukan.';
                $errors['general']   = 'Terjadi kesalahan saat menambah jabatan.';
                $_SESSION['errors_messages'] = $errors;
                $_SESSION['old_messages']    = $old;
                return \redirect(BASE_URL . 'divisi-jabatan#jabatan');
            }

            list($errors, $berhasil) = $this->divisiJabatanServices->tambahJabatan($errors, $old);

            if (!$berhasil) {
                $errors['general']           = 'Terjadi kesalahan saat menambah jabatan.';
                $_SESSION['errors_messages'] = $errors;
                $_SESSION['old_messages']    = $old;
                return \redirect(BASE_URL . 'divisi-jabatan#jabatan');
            }

        } else {
            $errors            = $validator->getErrors();
            $errors['general'] = 'Terjadi kesalahan saat menambah jabatan.';
            $_SESSION['errors_messages'] = $errors;
            $_SESSION['old_messages']    = $old;
            return \redirect(BASE_URL . 'divisi-jabatan#jabatan');
        }

        $_SESSION['errors_messages']   = '';
        $_SESSION['old_messages']      = '';
        $_SESSION['flash_success']     = "Jabatan '{$old['nama_jabatan']}' berhasil ditambahkan.";
        return \redirect(BASE_URL . 'divisi-jabatan#jabatan');
    }


    /**
    * Menghapus divisi
    */
    public function hapusDivisi() {
        $errors = [];
        $old    = [];

        // Ambil ID dari URL (dikirim via hidden input atau route param)
        $divisiId = sanitize($_POST['divisi_id'] ?? '');

        if (empty($divisiId)) {
            $_SESSION['errors_messages'] = ['general' => 'ID Divisi tidak ditemukan.'];
            return \redirect(BASE_URL . 'divisi-jabatan#divisi');
        }

        // Cek divisi ada
        $divisi = $this->divisiModel
        ->select(['divisi_id', 'nama_divisi'])
        ->where('divisi_id', $divisiId)
        ->get();

        if (empty($divisi)) {
            $_SESSION['errors_messages'] = ['general' => 'Divisi tidak ditemukan.'];
            return \redirect(BASE_URL . 'divisi-jabatan#divisi');
        }

        $namaDivisi = $divisi[0]['nama_divisi'];

        // Cek karyawan & hapus ditangani di services
        list($errors, $berhasil) = $this->divisiJabatanServices->hapusDivisi($errors, $divisiId);

        if (!$berhasil) {
            $errors['general']           = "Terjadi kesalahan saat menghapus divisi '{$namaDivisi}'.";
            $_SESSION['errors_messages'] = $errors;
            return \redirect(BASE_URL . 'divisi-jabatan#divisi');
        }

        $_SESSION['errors_messages'] = '';
        $_SESSION['flash_success']   = "Divisi '{$namaDivisi}' berhasil dihapus.";
        return \redirect(BASE_URL . 'divisi-jabatan#divisi');
    }

    /**
    * Menghapus jabatan
    */
    public function hapusJabatan() {
        $errors = [];
        $old    = [];

        // Ambil ID jabatan
        $jabatanId = sanitize($_POST['jabatan_id'] ?? '');

        if (empty($jabatanId)) {
            $_SESSION['errors_messages'] = ['general' => 'ID Jabatan tidak ditemukan.'];
            return \redirect(BASE_URL . 'divisi-jabatan#jabatan');
        }

        // Cek jabatan ada
        $jabatan = $this->jabatanModel
        ->select(['jabatan_id', 'nama_jabatan'])
        ->where('jabatan_id', $jabatanId)
        ->get();

        if (empty($jabatan)) {
            $_SESSION['errors_messages'] = ['general' => 'Jabatan tidak ditemukan.'];
            return \redirect(BASE_URL . 'divisi-jabatan#jabatan');
        }

        $namaJabatan = $jabatan[0]['nama_jabatan'];

        // Cek karyawan & hapus ditangani di services
        list($errors, $berhasil) = $this->divisiJabatanServices->hapusJabatan($errors, $jabatanId);

        if (!$berhasil) {
            $errors['general']           = "Terjadi kesalahan saat menghapus jabatan '{$namaJabatan}'.";
            $_SESSION['errors_messages'] = $errors;
            return \redirect(BASE_URL . 'divisi-jabatan#jabatan');
        }

        $_SESSION['errors_messages'] = '';
        $_SESSION['flash_success']   = "Jabatan '{$namaJabatan}' berhasil dihapus.";
        return \redirect(BASE_URL . 'divisi-jabatan#jabatan');
    }

    public function cetakView() {
        // Halaman cetak tidak pakai main template
        // karena butuh full HTML sendiri
        return \view ('cetak-rekap.php');
    }
    
}