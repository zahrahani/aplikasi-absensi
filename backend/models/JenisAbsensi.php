<?php
/**
 * =====================================================
 * MODEL: JenisAbsensi
 * Menangani operasi database untuk tabel jenis_absensi
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */
namespace Models;

use Models\ParentModels;

class JenisAbsensi extends ParentModels {
    /**
    * Constructor
    */
    public function __construct() {
        parent::__construct('jenis_absensi');
    }
}