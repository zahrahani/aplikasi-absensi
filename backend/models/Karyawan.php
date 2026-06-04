<?php
/**
 * =====================================================
 * MODEL: Karyawan
 * Menangani operasi database untuk tabel karyawan
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */
namespace Models;

use Models\ParentModels;

class Karyawan extends ParentModels {
    /**
    * Constructor
    */
    public function __construct() {
    	parent::__construct('karyawan');
    }
}