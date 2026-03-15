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

// Load controllers
require_once CONTROLLERS_PATH . 'AuthController.php';
require_once CONTROLLERS_PATH . 'MahasiswaController.php';

// Mulai session
startSession();

// Ambil parameter page dan action
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Routing
switch ($page) {
    // =====================================================
    // AUTH ROUTES
    // =====================================================
    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;

    case 'register':
        $controller = new AuthController();
        $controller->register();
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'profile':
        $controller = new AuthController();
        $controller->profile();
        break;

    // =====================================================
    // DASHBOARD
    // =====================================================
    case 'dashboard':
        requireLogin();

        // Load models untuk statistik
        require_once MODELS_PATH . 'Mahasiswa.php';
        require_once MODELS_PATH . 'User.php';

        $mahasiswaModel = new Mahasiswa();
        $userModel = new User();

        $totalMahasiswa = $mahasiswaModel->count();
        $totalUsers = $userModel->count();
        $statsByJurusan = $mahasiswaModel->getStatsByJurusan();
        $statsBySemester = $mahasiswaModel->getStatsBySemester();

        $pageTitle = 'Dashboard';
        include VIEWS_PATH . 'dashboard.php';
        break;

    // =====================================================
    // MAHASISWA ROUTES
    // =====================================================
    case 'mahasiswa':
        $controller = new MahasiswaController();

        switch ($action) {
            case 'create':
                $controller->create();
                break;

            case 'edit':
                $controller->edit();
                break;

            case 'show':
                $controller->show();
                break;

            case 'delete':
                $controller->delete();
                break;

            case 'index':
            default:
                $controller->index();
                break;
        }
        break;

    // =====================================================
    // HOME / DEFAULT
    // =====================================================
    case 'home':
    default:
        if (isLoggedIn()) {
            redirect('index.php?page=dashboard');
        } else {
            redirect('index.php?page=login');
        }
        break;
}
