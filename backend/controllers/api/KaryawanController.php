<?php
/**
 * =====================================================
 * CONTROLLER: AuthController
 * Menangani autentikasi (login, register, logout)
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */
namespace Controllers\Api;

use Models\User as UserModel;
use Models\Karyawan as KaryawanModel;
use Models\Divisi as DivisiModel;
use Models\Jabatan as JabatanModel;

use Services\KaryawanServices;

class KaryawanController {
    private $userModel;
    private $karyawanModel;
    private $divisiModel;
    private $jabatanModel;
    private $karyawanServices;

    /**
     * Constructor
     */
    public function __construct() {
        $this->userModel = new UserModel();
        $this->karyawanModel = new KaryawanModel();
        $this->divisiModel = new DivisiModel();
        $this->jabatanModel = new JabatanModel();
        $this->karyawanServices = new KaryawanServices();
    }

    /**
     * Api Dashboard
     */
    public function dashboard() {
        // Inisisasi variabel errors dan request
        $errors = [];
        $request = \requestJson();

        // Proses dashboard
        list($errors, $status, $response) = $this->karyawanServices->dashboard($errors, $request['remember_token']);

        if ($status != 200) {
            return \responseJson($errors, $status);
        }

        return \responseJson($response, $status);
    }

    /**
     * Api Rekal Laporan pribadi
    */
    public function getRekapMe() {
        // Inisisasi variabel errors dan request
        $errors = [];
        $request = \requestJson();

        $month   = isset($request['month']) ? (int) $request['month'] : (int) date('m');
        $year    = isset($request['year'])  ? (int) $request['year']  : (int) date('Y');

        list($errors, $status, $response) = $this->karyawanServices->rekapLaporan(
            $errors,
            $request['remember_token'],
            $month,
            $year
        );

        if ($status != 200) {
            return \responseJson($errors, $status);
        }

        return \responseJson($response, $status);
    }

    /**
     * Api Pengajuan izin pribadi
    */
    public function getPengajuanMe() {
        // Inisisasi variabel errors dan request
        $errors = [];
        $request = \requestJson();
    
        list($errors, $status, $response) = $this->karyawanServices->getPengajuan($errors, $request['remember_token']);

        if ( $status != 200 ) {
            return \responseJson($errors, $status);
        }

        return \responseJson($response, $status);        
    }

    /**
     * Api buat pengajuan
    */
    public function createPengajuanMe() {
        // Inisisasi variabel errors dan request
        $errors = [];
        $request = \requestJson();
    
        list($errors, $status, $response) = $this->karyawanServices->buatPengajuan($errors, $request['remember_token'], $request);

        if ( $status != 200 ) {
            return \responseJson($errors, $status);
        }

        return \responseJson($response, $status);        
    }

    /**
     * Api batal pengajuan
    */
    public function exitPengajuanMe() {
        // Inisisasi variabel errors dan request
        $errors = [];
        $request = \requestJson();
    
        list($errors, $status, $response) = $this->karyawanServices->batalPengajuan($errors, $request['remember_token'], $request['pengajuan_id']);

        if ( $status != 200 ) {
            return \responseJson($errors, $status);
        }

        return \responseJson($response, $status);        
    }
    
    /**
     * Api mendapatkan profile
    */
    public function getProfileKaryawan() {
        // Inisisasi variabel errors dan request
        $errors = [];
        $request = \requestJson();
    
        list($errors, $status, $response) = $this->karyawanServices->getProfileKaryawan($errors, $request['remember_token']);

        if ( $status != 200 ) {
            return \responseJson($errors, $status);
        }

        return \responseJson($response, $status); 
    }

    /**
     * Api memperbarui profile
    */
    public function updateProfileKaryawan() {
        // Inisisasi variabel errors dan request
        $errors = [];
        $request = \requestJson();
    
        list($errors, $status, $response) = $this->karyawanServices->updateProfileKaryawan($errors, $request['remember_token'], $request);

        if ( $status != 200 ) {
            return \responseJson($errors, $status);
        }

        return \responseJson($response, $status); 
    }

    /**
     * Api memperbarui foto profile
    */
    public function updateFotoProfileKaryawan() {
        // Inisisasi variabel errors dan request
        $errors = [];
    
        list($errors, $status, $response) = $this->karyawanServices->updateFotoProfileKaryawan($errors, $_POST['remember_token']);

        if ( $status != 200 ) {
            return \responseJson($errors, $status);
        }

        return \responseJson($response, $status); 
    }

    /**
     * Api memperbarui password
    */
    public function gantiPasswordKaryawan() {
        // Inisisasi variabel errors dan request
        $errors = [];
        $request = \requestJson();
    
        list($errors, $status, $response) = $this->karyawanServices->gantiPasswordKaryawan($errors, $request['remember_token'], $request);

        if ( $status != 200 ) {
            return \responseJson($errors, $status);
        }

        return \responseJson($response, $status); 
    }

    public function logout() {
        // Inisisasi variabel errors dan request
        $errors = [];
        $request = \requestJson();
    
        list($errors, $status, $response) = $this->karyawanServices->logout($errors, $request['remember_token']);

        if ( $status != 200 ) {
            return \responseJson($errors, $status);
        }

        return \responseJson($response, $status); 
    }


    

}