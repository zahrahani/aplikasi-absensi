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

// use Services\AdminServices;

class KomponenController {
	private $userModel;
    private $karyawanModel;
    private $divisiModel;
    private $jabatanModel;
    // private $adminServices;

    /**
     * Constructor
     */
    public function __construct() {
        $this->userModel = new UserModel();
        $this->karyawanModel = new KaryawanModel();
        $this->divisiModel = new DivisiModel();
        $this->jabatanModel = new JabatanModel();
        // $this->adminServices = new AdminServices();
    }

    /**
     * show jabatan with divisi_id
     */
    public function showJabatanWithDivisi() {
        $request = \requestJson();


        $jabatanWithDivisi = $this->jabatanModel->select(['*'])->where('divisi_id', $request['divisi_id'])->get();

        return \responseJson($jabatanWithDivisi, 200);
    }

    /**
     * show divisi
     */
    public function showDivisi() {

        $divisi = $this->divisiModel->select(['*'])->get();

        return \responseJson($divisi, 200);
    }

    /**
     * show karyawan with authorization
     */
    public function dataKaryawan() {
        $data = json_decode(file_get_contents("php://input"), true)  ?? ['csrf_token' => ''];

        if ( !\validateCSRFToken($data['csrf_token']) ) {
            return \responseJson(["messages" => "Anda tidak memiliki hak akses"], 403);
            return false;
        }
        $dataKaryawan = $this->karyawanModel
        ->select([
            'karyawan.no_handphone as hp',
            'karyawan.alamat',
            'karyawan.status',
            'karyawan.jabatan_id',
            'karyawan.divisi_id',
            'u.foto_profil',
            'u.id',
            'u.email',
            'u.created_at as bergabung', 
            'u.nama_lengkap AS nama',
            'u.user_id',
            'd.nama_divisi AS divisi',
            'j.nama_jabatan AS jabatan',
        ])
        ->join('users AS u', 'karyawan.user_id', 'u.user_id')
        ->join('divisi AS d', 'karyawan.divisi_id', 'd.divisi_id')
        ->join('jabatan AS j', 'karyawan.jabatan_id', 'j.jabatan_id')
        ->get();

        foreach ($dataKaryawan as $key => $karyawan) {
            $dataKaryawan[$key]['foto_profil'] = $dataKaryawan[$key]['foto_profil'] ? 'uploads/profile/' . $dataKaryawan[$key]['foto_profil'] : null;
            $dataKaryawan[$key]['inisial'] = $this->getInisial($karyawan['nama']);
            $dataKaryawan[$key]['kehadiran'] = 0;
            $dataKaryawan[$key]['izinDisetujui'] = 0;
            $dataKaryawan[$key]['keterlambatan'] = 0;
        }
        return \responseJson($dataKaryawan, 200);
    }

   /**
     * get inisial
     */
    public function getInisial($username) {
    // Hilangkan spasi berlebih
        $username = trim($username);

    // Pecah jadi array kata
        $words = explode(" ", $username);

        if (count($words) > 1) {
        // Ambil huruf pertama dari 2 kata pertama
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        } else {
        // Kalau cuma 1 kata, ambil 2 huruf pertama
            return strtoupper(substr($username, 0, 2));
        }
    }

}