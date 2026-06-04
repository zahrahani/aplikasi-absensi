<?php
/**
 * =====================================================
 * MODEL: JadwalKaryawan
 * Menangani operasi database untuk tabel jadwal_karyawan
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */
namespace Models;

use Models\ParentModels;

class JadwalKaryawan extends ParentModels {
    /**
    * Constructor
    */
    public function __construct() {
    	parent::__construct('jadwal_karyawan');
    }
}