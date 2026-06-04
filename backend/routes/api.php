<?php 
namespace Routes\Api;

// Route
use Includes\Route;

// Controllers
use Controllers\KomponenController;
use Controllers\AdminController;
use Controllers\Api\AuthController as ApiAuthController;
use Controllers\Api\ScanQRController;
use Controllers\Api\KaryawanController;



$route = new Route();
$prefix = '/api';



// Mulai session
startSession();

// Routing
$route->middleware(['all'])->group(function ($routeChild) {
    $routeChild->post('/api/komponen/showJabatan', [KomponenController::class, 'showJabatanWithDivisi']);
    $routeChild->get('/api/komponen/showDivisi', [KomponenController::class, 'showDivisi']);
    $routeChild->post('/api/komponen/karyawan', [KomponenController::class, 'dataKaryawan']);
    $routeChild->post('/api/komponen/karyawan/update', [KomponenController::class, 'updateKaryawan']);

    $routeChild->post('/api/profile/foto', [KaryawanController::class, 'updateFotoProfileKaryawan']);
    $routeChild->post('/api/login', [ApiAuthController::class, 'authenticate']);

});

$route->middleware(['auth:api'])->group(function ($routeChild) {
    $routeChild->post('/api/logout', [ApiAuthController::class, 'logout']);  
    $routeChild->post('/api/me', [ApiAuthController::class, 'meApi']);
    $routeChild->post('/api/presensi', [ScanQrController::class, 'presensi']);
    $routeChild->post('/api/dashboard', [KaryawanController::class, 'dashboard']);
    $routeChild->post('/api/rekap-laporan', [KaryawanController::class, 'getRekapMe']);
    $routeChild->post('/api/pengajuan', [KaryawanController::class, 'getPengajuanMe']);
    $routeChild->post('/api/pengajuan/buat', [KaryawanController::class, 'createPengajuanMe']);
    $routeChild->post('/api/pengajuan/batal', [KaryawanController::class, 'exitPengajuanMe']);
    $routeChild->post('/api/profile', [KaryawanController::class, 'getProfileKaryawan']);
    $routeChild->post('/api/profile/update', [KaryawanController::class, 'updateProfileKaryawan']);
    $routeChild->post('/api/profile/ganti-password', [KaryawanController::class, 'gantiPasswordKaryawan']);

    $routeChild->post('/api/logout', [KaryawanController::class, 'logout']);


});


$route->middleware(['admin'])->group(function ($routeChild) {
    $routeChild->get('/api/rekap', [AdminController::class, 'getRekapForMonth']);
    $routeChild->get('/api/rekap/detail', [AdminController::class, 'getRekapDetailKaryawan']);
    $routeChild->get('/api/validasi-izin', [AdminController::class, 'getValidasiIzin']);
    $routeChild->post('/api/validasi-izin/aksi', [AdminController::class, 'aksiValidasiIzin']);
    $routeChild->post('/api/validasi-izin/bulk', [AdminController::class, 'bulkValidasiIzin']);

    $routeChild->get('/api/jadwal', [AdminController::class, 'getJadwal']);
    $routeChild->post('/api/jadwal/simpan', [AdminController::class, 'simpanJadwal']);
    $routeChild->post('/api/admin/dashboard/chart', [AdminController::class, 'getDashboardChart']);
});