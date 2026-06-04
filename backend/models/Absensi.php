<?php
/**
 * =====================================================
 * MODEL: Absensi
 * Menangani operasi database untuk tabel absensi
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */
namespace Models;

use Models\ParentModels;

class Absensi extends ParentModels {
    /**
    * Constructor
    */
    public function __construct() {
    	parent::__construct('absensi');
    }
}