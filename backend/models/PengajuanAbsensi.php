<?php
/**
 * =====================================================
 * MODEL: PengajuanAbsensi
 * Menangani operasi database untuk tabel pengajuan_absensi
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */
namespace Models;

use Models\ParentModels;

class PengajuanAbsensi extends ParentModels {
    /**
    * Constructor
    */
    public function __construct() {
    	parent::__construct('pengajuan_absensi');
    }
}