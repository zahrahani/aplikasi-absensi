<?php 
namespace Routes\web;

// Route
use Includes\Route;

// Controllers
use Controllers\AuthController;
use Controllers\AdminController;
use Controllers\DivisiJabatanController;
use Controllers\Api\ScanQrController;
$route = new Route();



// Mulai session
startSession();



$route->middleware(['all'])->group(function ($routeChild) {
    $routeChild->get('/scan-qr', [ScanQrController::class, 'show']);


});


// Routing
$route->middleware(['guest'])->group(function ($routeChild) {
    $routeChild->get('/', [AuthController::class, 'loginView']);
    $routeChild->get('/login', [AuthController::class, 'loginView']);
    $routeChild->post('/login', [AuthController::class, 'authenticate']);



});


$route->middleware(['admin'])->group(function ($routeChild) {

    // Admin
    $routeChild->get('/dashboard', [AdminController::class, 'dashboardView']);
    $routeChild->get('/rekap-laporan', [AdminController::class, 'rekapView']);
    $routeChild->get('/validasi-izin', [AdminController::class, 'validasiIzinView']);
    $routeChild->get('/karyawan', [AdminController::class, 'karyawanView']);
    $routeChild->get('/karyawan/create', [AdminController::class, 'kCreateView']);
    $routeChild->get('/profile', [AuthController::class, 'profileView']);
    $routeChild->get('/jadwal', [AdminController::class, 'jadwalKaryawan']);

    $routeChild->post('/profile', [AuthController::class, 'update']);
    $routeChild->post('/profile/change-password', [AuthController::class, 'changePassword']);
    $routeChild->post('/profile/update-foto-profile', [AuthController::class, 'updateFotoProfile']);


    $routeChild->post('/karyawan/create', [AdminController::class, 'kCreatePost']);
    $routeChild->post('/karyawan/update', [AdminController::class, 'updateKaryawan']);
    $routeChild->post('/karyawan/delete', [AdminController::class, 'hapusKaryawan']);
    $routeChild->post('/karyawan/logout', [AdminController::class, 'logoutKaryawan']);

    // Divisi Jabatan
    $routeChild->get('/divisi-jabatan', [DivisiJabatanController::class, 'index']);
    $routeChild->get('/divisi-jabatan/create-divisi', [DivisiJabatanController::class, 'divisiCreateView']);
    $routeChild->post('/divisi-jabatan/create-divisi', [DivisiJabatanController::class, 'divisiCreatePost']);
    $routeChild->post('/divisi-jabatan/delete-divisi', [DivisiJabatanController::class, 'divisiDelete']);
    $routeChild->get('/divisi-jabatan/create-jabatan', [DivisiJabatanController::class, 'jabatanCreateView']);
    $routeChild->post('/divisi-jabatan/create-jabatan',[DivisiJabatanController::class, 'jabatanCreatePost']);
    $routeChild->post('/divisi-jabatan/delete-jabatan',[DivisiJabatanController::class, 'jabatanDelete']);
    $routeChild->get('/divisi-jabatan/jabatan-by-divisi', [DivisiJabatanController::class, 'getJabatanByDivisi']);

    $routeChild->get('cetak-rekap', [AdminController::class, 'cetakView']);
    $routeChild->get('/logout', [AuthController::class, 'logout']);

});


// $route->notFound();


