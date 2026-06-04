<?php
/**
 * =====================================================
 * MODEL: Jabatan
 * Menangani operasi database untuk tabel divisi
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */
namespace Models;

use Models\ParentModels;

class Jabatan extends ParentModels {
    /**
    * Constructor
    */
    public function __construct() {
    	parent::__construct('jabatan');
    }
}