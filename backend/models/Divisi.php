<?php
/**
 * =====================================================
 * MODEL: Divisi
 * Menangani operasi database untuk tabel divisi
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */
namespace Models;

use Models\ParentModels;

class Divisi extends ParentModels {
    /**
    * Constructor
    */
    public function __construct() {
    	parent::__construct('divisi');
    }
}