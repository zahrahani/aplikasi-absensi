<?php
/**
 * =====================================================
 * MODEL: ShiftKerja
 * Menangani operasi database untuk tabel shift_kerja
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */
namespace Models;

use Models\ParentModels;

class ShiftKerja extends ParentModels {
    /**
    * Constructor
    */
    public function __construct() {
    	parent::__construct('shift_kerja');
    }
}