<?php
/**
 * =====================================================
 * MODEL: Pengguna
 * Menangani operasi database untuk tabel users
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */
namespace Models;

use Models\ParentModels;

class Pengguna extends ParentModels {
    /**
    * Constructor
    */
    public function __construct() {
    	parent::__construct('users');
    }
}