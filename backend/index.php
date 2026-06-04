<?php
/**
 * =====================================================
 * FRONT CONTROLLER / ROUTER
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 *
 * File ini berfungsi sebagai entry point aplikasi
 * dan mengarahkan request ke controller yang sesuai.
 */

// Load konfigurasi
require_once __DIR__ . '/config/config.php';

// Load Route Web
require_once __DIR__ . '/routes/web.php';

// Load Route Web
require_once __DIR__ . '/routes/api.php';
