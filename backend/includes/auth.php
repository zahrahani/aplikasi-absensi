<?php
/**
 * =====================================================
 * HELPER FUNCTIONS - AUTHENTICATION
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */

use Models\Pengguna as PenggunaModel;


/**
 * Memulai session jika belum dimulai
 */
function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Mengecek apakah user sudah login
 */
function isLoggedIn() {
    startSession();
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Mengecek apakah user belum login
 */
function isGuest() {
    startSession();
    return empty($_SESSION['user_id']);
}


/**
 * Mengecek apakah user adalah admin
 */
function isAdmin() {
    startSession();
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Mengecek apakah user sudah login api
 */
function isLoggedInApi() {
    $request = \requestJson();

    if (!isset($request['remember_token'])) return false;


    $penggunaModel = new PenggunaModel();

    $countUser = $penggunaModel->selectRaw('count(*) as jumlah')
        ->where('remember_token', $request['remember_token'])
        ->get()[0];


    return $countUser['jumlah'] > 0;
}





/**
 * Mendapatkan data user yang sedang login
 */
function getCurrentUser() {
    startSession();
    if (!isLoggedIn()) {
        return null;
    }

    
    // Hanya contoh nilai yang dikembalikan oleh user
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'] ?? '',
        'nama_lengkap' => $_SESSION['nama_lengkap'] ?? '',
        'email' => $_SESSION['email'] ?? '',
        'role' => $_SESSION['role'] ?? 'user'
    ];
}

/**
 * Set session user setelah login
 */
function setUserSession($user) {
    startSession();
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['foto_profil'] = $user['foto_profil'];
    $_SESSION['login_time'] = time();

    generateCSRFToken();
}

/**
 * Menghapus session (logout)
 */
function destroySession() {
    startSession();

    // Hapus semua data session
    $_SESSION = [];

    // Hapus cookie session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Hapus cookie remember me
    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, '/');
    }

    session_destroy();
}

/**
 * Redirect jika belum login
 */
function requireLogin() {
    if (!isLoggedIn()) {
        setFlashMessage('error', 'Silakan login terlebih dahulu.');
        redirect(\BASE_URL . 'login');
        exit;
    }
}

/**
 * Redirect jika bukan admin
 */
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        setFlashMessage('error', 'Anda tidak memiliki akses ke halaman ini.');
        redirect(\BASE_URL . 'dashboard');
        exit;
    }
}

/**
 * Redirect jika harus guest
 */
function requireGuest() {
    if (isLoggedIn()) {
        setFlashMessage('error', 'Anda tidak memiliki akses ke halaman ini.');
        redirect(\BASE_URL . 'dashboard');
        exit;
    }
}

/**
 * Generate CSRF Token
 */
function generateCSRFToken() {
    startSession();
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validasi CSRF Token
 */
function validateCSRFToken($token) {
    startSession();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Generate Remember Token
 */
function generateRememberToken() {
    return bin2hex(random_bytes(32));
}

/**
 * Set Remember Cookie
 */
function setRememberCookie($token) {
    setcookie('remember_token', $token, time() + COOKIE_LIFETIME, '/', '', false, true);
}


/**
 * Sanitize input
 */
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Escape HTML output
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}


