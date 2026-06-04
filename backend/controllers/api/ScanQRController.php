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

use Services\ScanQRServices;

class ScanQrController {
    private $userModel;
    private $karyawanModel;
    private $divisiModel;
    private $jabatanModel;
    private $scanQRServices;

    /**
     * Constructor
     */
    public function __construct() {
        $this->userModel = new UserModel();
        $this->karyawanModel = new KaryawanModel();
        $this->divisiModel = new DivisiModel();
        $this->jabatanModel = new JabatanModel();
        $this->scanQRServices = new ScanQRServices();
    }

    /**
     * Halaman Scan QR
     */
    public function show() {
        return view('scanQr');
    }

    /**
     * Validate QR Token
     */

    public function presensi () {
        // Inisisasi variabel errors
        $errors = [];


        // mENGHANDLE INPUT
        $request = \requestJson();
        $qrCode    = $request['qr_code'] ?? null;
        $latUser  = $request['lat_user'] ?? null;
        $lngUser  = $request['lng_user'] ?? null;

        if (!$qrCode || $latUser === null || $lngUser === null) {
            return \responseJson(['errors_messages' => "Data tidak lengkap"], 404);
        }

        // validate jarak, token
        list($errors, $status) = $this->scanQRServices->validateScanQR($errors, $qrCode, $latUser, $lngUser);

        if ($status != 200) {
            return \responseJson($errors, $status);
        }

        // validate jadwal, absensi, dll
        list($errors, $status) = $this->scanQRServices->validateAbsensi($errors, $request['remember_token']);

        if ($status != 200) {
            return \responseJson($errors, $status);
        }

        return \responseJson([
            'messages' => "Anda berhasil melakukan presensi"
        ], 200);

    }

}