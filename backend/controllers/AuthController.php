<?php
/**
 * =====================================================
 * CONTROLLER: AuthController
 * Menangani autentikasi (login, register, logout)
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */
namespace Controllers;

use Models\User as UserModel;
use Models\Pengguna as PenggunaModel;


use Services\AdminServices;
use Includes\FileHandler;

class AuthController {
    private $userModel;
    private $penggunaModel;
    private $adminServices;
    private $fileHandler;


    /**
     * Constructor
     */
    public function __construct() {
        $this->userModel = new UserModel();
        $this->penggunaModel = new PenggunaModel();
        $this->adminServices = new AdminServices();
        $this->fileHandler = new FileHandler();
    }

    /**
     * Halaman Login
     */
    public function loginView() {
        // Jika sudah login, redirect ke dashboard
        if (isLoggedIn()) {
            redirect(\BASE_URL . 'dashboard');
        }

        // Cek remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            $user = $this->userModel->getByRememberToken($_COOKIE['remember_token']);
            if ($user) {
                setUserSession($user);
                redirect(\BASE_URL . 'dashboard');
            }
        }

        $error = $_SESSION['errors_messages'] ?? '';

        // Tampilkan view login
        $pageTitle = 'Login';
        \view('login', [
            'pageTitle' => $pageTitle,
            'error' => $error
        ]);
    }

    /**
     * Autentikasi
     */
    public function authenticate () {
        $username = sanitize($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

            // Validasi
        $validator = \validate($_POST);
        $validator->required('username', 'Username wajib diisi.')->required('password', 'Password wajib diisi.');


        if ($validator->isValid()) {

                // Autentikasi
            $user = $this->userModel->authenticate($username, $password);

            if ($user) {
                    // Set session
                \setUserSession($user);

                    // Set remember cookie jika dicentang
                if ($remember) {
                    $token = \generateRememberToken();
                    $this->userModel->updateRememberToken($user['id'], $token);
                    \setRememberCookie($token);
                }

                \setFlashMessage('success', 'Selamat datang, ' . $user['nama_lengkap'] . '!');
                \redirect(\BASE_URL . 'dashboard');
            } else {
                $_SESSION['errors_messages'] = 'Username atau password salah.';
                \redirect(\BASE_URL . 'login');
            }
        } else {
            $_SESSION['errors_messages'] = $validator->getFirstError();
            \redirect(\BASE_URL . 'login');
        }
    }


    /**
     * Halaman Register
     */
    public function register() {
        // Jika sudah login, redirect ke dashboard
        if (isLoggedIn()) {
            \redirect(\BASE_URL . 'dashboard');
        }

        $errors = $_SESSION['errors_messages'] ?? [];
        $old = $_SESSION['old_messages'] ?? [];

        // Tampilkan view register
        $pageTitle = 'Register';
        \view('auth/register', [
            'pageTitle' => $pageTitle,
            'errors' => $errors,
            'old' => $old
        ]);
    }

    /**
     * Tambah Register
     */
    public function storeUser() {
        $errors = [];
        $old = [
            'username' => sanitize($_POST['username'] ?? ''),
            'email' => sanitize($_POST['email'] ?? ''),
            'nama_lengkap' => sanitize($_POST['nama_lengkap'] ?? '')
        ];

            // Validasi
        $validator = \validate($_POST);
        $validator->required('username', 'Username wajib diisi.')
        ->minLength('username', 4, 'Username minimal 4 karakter.')
        ->maxLength('username', 50, 'Username maksimal 50 karakter.')
        ->alphanumeric('username', 'Username hanya boleh huruf dan angka.')
        ->required('email', 'Email wajib diisi.')
        ->email('email', 'Format email tidak valid.')
        ->required('nama_lengkap', 'Nama lengkap wajib diisi.')
        ->minLength('nama_lengkap', 3, 'Nama lengkap minimal 3 karakter.')
        ->required('password', 'Password wajib diisi.')
        ->minLength('password', 6, 'Password minimal 6 karakter.')
        ->required('confirm_password', 'Konfirmasi password wajib diisi.')
        ->matches('confirm_password', 'password', 'Konfirmasi password tidak cocok.');

        if ($validator->isValid()) {
                // Cek username sudah ada
            if ($this->userModel->usernameExists($old['username'])) {
                $errors['username'] = 'Username sudah digunakan.';
            }

                // Cek email sudah ada
            if ($this->userModel->emailExists($old['email'])) {
                $errors['email'] = 'Email sudah digunakan.';
            }

            if (empty($errors)) {
                    // Buat user baru
                $userId = $this->userModel->create([
                    'username' => $old['username'],
                    'email' => $old['email'],
                    'nama_lengkap' => $old['nama_lengkap'],
                    'password' => $_POST['password'],
                    'role' => 'user'
                ]);

                if ($userId) {
                    \setFlashMessage('success', 'Registrasi berhasil! Silakan login.');
                    \redirect(\BASE_URL . 'login');
                } else {
                    $errors['general'] = 'Terjadi kesalahan saat registrasi.';
                }
            }
        } else {
            $errors = $validator->getErrors();
        }

        $_SESSION['errors_messages'] = $errors;
        $_SESSION['old_messages'] = $old;
        redirect(\BASE_URL . 'login');
    }

    /**
     * Proses Logout
     */
    public function logout() {
        // Hapus remember token dari database
        if (isLoggedIn()) {
            $this->userModel->clearRememberToken($_SESSION['user_id']);
        }

        // Hapus session
        destroySession();

        setFlashMessage('success', 'Anda telah berhasil logout.');
        redirect(\BASE_URL . 'login');
    }

    /**
     * Halaman Profile
     */
    public function profileView() {
        $userId = $_SESSION['user_id'];
        $admin = $this->adminServices->getProfileKaryawan($userId);
        $old = $_SESSION['old_messages'] ?? '';


        \viewWithMainTemplate('admin/profile', [
            'active' => 'profile',
            'akun_admin' => $admin,
            'old' => $old
        ]); 
    }

    /**
     * Update Profile
     */
    public function update() {
        $errors = [];
        $old = [
            'username' => sanitize($_POST['username'] ?? ''),
            'email' => sanitize($_POST['email'] ?? ''),
            'nama_lengkap' => sanitize($_POST['nama_lengkap'] ?? '')
        ];

            // Validasi
        $validator = \validate($_POST);
        $validator->required('username', 'Username wajib diisi.')
        ->minLength('username', 4, 'Username minimal 4 karakter.')
        ->maxLength('username', 50, 'Username maksimal 50 karakter.')
        ->alphanumeric('username', 'Username hanya boleh huruf dan angka.')
        ->required('email', 'Email wajib diisi.')
        ->email('email', 'Format email tidak valid.')
        ->required('nama_lengkap', 'Nama lengkap wajib diisi.')
        ->minLength('nama_lengkap', 3, 'Nama lengkap minimal 3 karakter.');



        if ($validator->isValid()) {


                // Cek username sudah ada
            if ($this->userModel->usernameExists($old['username'], 1)) {
                $errors['username'] = 'Username sudah digunakan.';
            }

                // Cek email sudah ada
            if ($this->userModel->emailExists($old['email'], 1)) {
                $errors['email'] = 'Email sudah digunakan.';
            }

            if (empty($errors)) {
                    // Buat user baru

                $userId = $this->penggunaModel->update([
                    'username' => $old['username'],
                    'email' => $old['email'],
                    'nama_lengkap' => $old['nama_lengkap']
                ])
                ->where('user_id', $_SESSION['user_id'])
                ->execute();

                if ($userId) {
                    unset($_SESSION['old_messages']);
                    \setFlashMessage('success', 'Update profil berhasil!');
                    \redirect(\BASE_URL . 'profile');
                } else {
                    $errors['general'] = 'Terjadi kesalahan saat update profil.';
                }
            }
        } else {
            $errors = $validator->getErrors();
        }

        $_SESSION['errors_messages'] = $errors;
        $_SESSION['old_messages'] = $old;
        redirect(\BASE_URL . 'profile');
    }

    /**
     * Ganti Password Profil
     */
    public function changePassword () {
        $user = $this->userModel->getById(1);
        $errors = [];
        $success = '';

        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

                // Validasi
        $validator = validate($_POST);
        $validator->required('current_password', 'Password saat ini wajib diisi.')
        ->required('new_password', 'Password baru wajib diisi.')
        ->minLength('new_password', 6, 'Password baru minimal 6 karakter.')
        ->required('confirm_password', 'Konfirmasi password wajib diisi.')
        ->matches('confirm_password', 'new_password', 'Konfirmasi password tidak cocok.');

        if ($validator->isValid()) {
                    // Verifikasi password saat ini
            if (!$this->userModel->verifyPassword($current_password, $user['password'])) {
                $errors['current_password'] = 'Password saat ini salah.';
            } else {
                $this->userModel->update($user['id'], [
                    'password' => $new_password
                ]);

                $success = 'Password berhasil diubah.';
                \setFlashMessage('success', $success);
            }
        } else {
            $errors = $validator->getErrors();
        }

        $_SESSION['errors_messages'] = $errors;
        redirect(\BASE_URL . 'profile');
    }

    public function updateFotoProfile() {
        $errors = [];
        $fotoProfile = $this->penggunaModel->select(['foto_profil'])
        ->where('user_id', $_SESSION['user_id'])
        ->get()[0]['foto_profil'];
        $folder = 'profile';


        // Cek apakah ada SERVER METHOD FILES yang dikirimkan
        if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] !== UPLOAD_ERR_NO_FILE) {


            // Upload foto kedalam folder profile
            $uploadResult = $this->fileHandler->upload($_FILES['foto_profil'], $folder);

            // validasi apakah berhasil
            if ($uploadResult) {
                $foto = $uploadResult;
            } else {
                $errors['foto_profil'] = $this->fileHandler->getFirstError();
            }


            if ( empty($errors) ) {
                // cek apakah sebelumnya punya foto profil
                if ( !is_null($fotoProfile) ) {

                    // hapus file foto profile 
                    $this->fileHandler->delete($fotoProfile, $folder);
                } 

                $berhasil = $this->penggunaModel->update([
                    'foto_profil' => $foto
                ])->where('user_id', $_SESSION['user_id'])
                ->execute();

                if ($berhasil) {
                    $_SESSION['foto_profil'] = $foto;
                    $success = 'Foto profil berhasil diubah.';
                    \setFlashMessage('success', $success);
                }
            }

            $_SESSION['errors_messages'] = $errors;
            redirect(\BASE_URL . 'profile');

        }

    }
}
