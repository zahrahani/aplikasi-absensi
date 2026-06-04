<?php
/**
 * =====================================================
 * MODEL: ApprovalHistory
 * Menangani operasi database untuk tabel approval_history
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */
namespace Models;

use Models\ParentModels;

class ApprovalHistory extends ParentModels {
    /**
    * Constructor
    */
    public function __construct() {
    	parent::__construct('approval_history');
    }
}