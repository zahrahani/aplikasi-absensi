<?php
/**
 * =====================================================
 * CONTROLLER: AuthController
 * Menangani autentikasi (login, register, logout) lewat gate api
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */
namespace Controllers\Api;

use Models\User as UserModel;
use Models\Pengguna as PenggunaModel;
use Models\Karyawan as KaryawanModel;


class AuthController {
    private $userModel;
    private $penggunaModel;
    private $karyawanModel;

    /**
     * Constructor
     */
    public function __construct() {
        $this->userModel = new UserModel();
        $this->penggunaModel = new PenggunaModel();
        $this->karyawanModel = new KaryawanModel();
    }

    /**
     * Autentikasi
     */
    public function authenticate () {
        $request = \requestJson();

        $username = sanitize($request['username'] ?? '');
        $password = $request['password'] ?? '';
        // $remember = isset($request['remember']);

            // Validasi
        $validator = \validate($request);
        $validator->required('username', 'Username wajib diisi.')->required('password', 'Password wajib diisi.');

        if ($validator->isValid() ) {

            // Autentikasi
            $user = $this->userModel->authenticate($username, $password);

           $token = ( is_array($user) )? $this->userModel->getRememberToken($user['user_id'])['remember_token'] : false;

           if ( $token ) {
                return responseJson(["errors_messages" => "Anda sudah melakukan login."], 403);
           }

            if ($user) {
                $token = \generateRememberToken();
                $this->userModel->updateRememberToken($user['id'], $token);

                $me = $this->me($token);
                return \responseJson($me, 200);
            } else {
                return \responseJson(['errors_messages' => 'Username atau password salah.'], 422);
            }
        } else {
            return responseJson(['errors_messages' => $validator->getErrors()], 422);
        }
    }

    /**
     * Return Me Response JSON
    */
    public function me($rememberToken) {
        $dataKaryawan = $this->karyawanModel
        ->select([
            'karyawan.no_handphone as hp',
            'karyawan.alamat',
            'karyawan.status',
            'karyawan.jabatan_id',
            'karyawan.divisi_id',
            'u.id',
            'u.user_id',
            'u.remember_token',
            'u.nama_lengkap',
            'u.username',
            'u.email',
            'u.created_at as bergabung', 
            'u.nama_lengkap AS nama',
            'u.user_id',
            'd.nama_divisi AS divisi',
            'j.nama_jabatan AS jabatan',
            'u.foto_profil'
        ])
        ->join('users AS u', 'karyawan.user_id', 'u.user_id')
        ->join('divisi AS d', 'karyawan.divisi_id', 'd.divisi_id')
        ->join('jabatan AS j', 'karyawan.jabatan_id', 'j.jabatan_id')
        ->where('u.remember_token', $rememberToken)
        ->get()[0];

        if ( $dataKaryawan['foto_profil'] != null ) {
            $dataKaryawan['foto_profil'] = 'uploads/profile/' . $dataKaryawan['foto_profil'];
        }
        
        return $dataKaryawan;

    }

    /**
     * Return Me Response JSON di API
    */
    public function meApi() {
        $request = \requestJson();
        $me = $this->me($request['remember_token']);

        return \responseJson($me, 200);

    }



    /**
     * Proses Logout
     */
    public function logout() {
        $request = \requestJson();

        // Hapus remember token dari database
        $this->userModel->clearApiRememberToken($request['remember_token']);

        return responseJson(["errors_messages" => "Anda telah berhasil logout."], 200);
    }



    /**
     * Update Profil
     */

    public function updateProfil () {
        $user = $this->userModel->getById($_SESSION['user_id']);
        $errors = [];
        $success = '';

        $nama_lengkap = sanitize($_POST['nama_lengkap'] ?? '');
        $email = sanitize($_POST['email'] ?? '');

                // Validasi
        $validator = validate($_POST);
        $validator->required('nama_lengkap', 'Nama lengkap wajib diisi.')
        ->required('email', 'Email wajib diisi.')
        ->email('email', 'Format email tidak valid.');

        if ($validator->isValid()) {
                    // Cek email sudah ada
            if ($this->userModel->emailExists($email, $user['id'])) {
                $errors['email'] = 'Email sudah digunakan.';
            } else {
                $this->userModel->update($user['id'], [
                    'nama_lengkap' => $nama_lengkap,
                    'email' => $email
                ]);

                $_SESSION['nama_lengkap'] = $nama_lengkap;
                $_SESSION['email'] = $email;

                $success = 'Profil berhasil diperbarui.';
                $user = $this->userModel->getById($user['id']);
            }
        } else {
            $errors = $validator->getErrors();
        }

        $_SESSION['errors_messages'] = $errors;
        $_SESSION['success_messages'] = $success;
        redirect(\BASE_URL . 'profile');
    }
    /**
     * Ganti Password Profil
     */

    public function changePassword () {
        $user = $this->userModel->getById($_SESSION['user_id']);
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
            }
        } else {
            $errors = $validator->getErrors();
        }

        $_SESSION['errors_messages'] = $errors;
        $_SESSION['success_messages'] = $success;
        redirect(\BASE_URL . 'profile');

    }
}
