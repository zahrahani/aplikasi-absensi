<?php

namespace Controllers;

use Models\Divisi as DivisiModel;
use Models\Jabatan as JabatanModel;

use Services\DivisiJabatanServices;

class DivisiJabatanController {

    private $divisiModel;
    private $jabatanModel;
    private $divisiJabatanServices;

    public function __construct() {
        $this->divisiModel          = new DivisiModel();
        $this->jabatanModel         = new JabatanModel();
        $this->divisiJabatanServices = new DivisiJabatanServices();
    }

    // =========================================================
    // HALAMAN UTAMA DIVISI JABATAN
    // =========================================================
    public function index() {
        \viewWithMainTemplate('admin/divisi-jabatan/index', [
            'active'   => 'divisi-jabatan',
            'divisis'  => $this->divisiModel->select(['*'])->get(),
            'jabatans' => $this->jabatanModel
                ->selectRaw("
                    jabatan.jabatan_id,
                    jabatan.nama_jabatan,
                    jabatan.divisi_id,
                    divisi.nama_divisi
                ")
                ->join('divisi', 'jabatan.divisi_id', 'divisi.divisi_id')
                ->get(),
        ]);
    }

    // =========================================================
    // HALAMAN TAMBAH DIVISI
    // =========================================================
    public function divisiCreateView() {
        \viewWithMainTemplate('admin/divisi-jabatan/create-divisi', [
            'active' => 'divisi-jabatan',
            'old'    => $_SESSION['old_messages'] ?? [],
        ]);
        $_SESSION['old_messages']    = '';
        $_SESSION['errors_messages'] = '';
    }

    public function divisiCreatePost() {
        $errors = [];
        $old    = [
            'nama_divisi' => sanitize($_POST['nama_divisi'] ?? ''),
        ];

        $validator = \validate($_POST);
        $validator
            ->required('nama_divisi', 'Nama divisi wajib diisi.')
            ->minLength('nama_divisi', 2, 'Nama divisi minimal 2 karakter.')
            ->maxLength('nama_divisi', 50, 'Nama divisi maksimal 50 karakter.')
            ->string('nama_divisi');

        if ($validator->isValid()) {
            list($errors, $berhasil) = $this->divisiJabatanServices->tambahDivisi($errors, $old);

            if (!$berhasil) {
                $errors['general']           = $errors['general'] ?? 'Terjadi kesalahan saat menyimpan divisi.';
                $_SESSION['errors_messages'] = $errors;
                $_SESSION['old_messages']    = $old;
                \setFlashMessage('danger', $errors['general']);
                return redirect(\BASE_URL . 'divisi-jabatan/create-divisi');
            }
        } else {
            $errors                      = $validator->getErrors();
            $errors['general']           = 'Terjadi kesalahan saat menyimpan divisi.';
            $_SESSION['errors_messages'] = $errors;
            $_SESSION['old_messages']    = $old;
            \setFlashMessage('danger', $errors['general']);
            return redirect(\BASE_URL . 'divisi-jabatan/create-divisi');
        }

        \setFlashMessage('success', 'Divisi berhasil ditambahkan.');
        $_SESSION['errors_messages'] = '';
        $_SESSION['old_messages']    = '';
        return redirect(\BASE_URL . 'divisi-jabatan');
    }

    // =========================================================
    // HAPUS DIVISI
    // =========================================================
    public function divisiDelete() {
        $errors   = [];
        $divisiId = sanitize($_POST['divisi_id'] ?? '');

        if (empty($divisiId)) {
            \setFlashMessage('danger', 'ID divisi tidak valid.');
            return redirect(\BASE_URL . 'divisi-jabatan');
        }

        list($errors, $berhasil) = $this->divisiJabatanServices->hapusDivisi($errors, $divisiId);

        if (!$berhasil) {
            \setFlashMessage('danger', $errors['general'] ?? 'Gagal menghapus divisi.');
            return redirect(\BASE_URL . 'divisi-jabatan');
        }

        \setFlashMessage('success', 'Divisi berhasil dihapus.');
        return redirect(\BASE_URL . 'divisi-jabatan');
    }

    // =========================================================
    // HALAMAN TAMBAH JABATAN
    // =========================================================
    public function jabatanCreateView() {
        \viewWithMainTemplate('admin/divisi-jabatan/create-jabatan', [
            'active'  => 'divisi-jabatan',
            'divisis' => $this->divisiModel->select(['*'])->get(),
            'old'     => $_SESSION['old_messages'] ?? [],
        ]);
        $_SESSION['old_messages']    = '';
        $_SESSION['errors_messages'] = '';
    }

    public function jabatanCreatePost() {
        $errors = [];
        $old    = [
            'nama_jabatan' => sanitize($_POST['nama_jabatan'] ?? ''),
            'divisi_id'    => sanitize($_POST['divisi_id']    ?? ''),
        ];


        $validator = \validate($_POST);
        $validator
            ->required('nama_jabatan', 'Nama jabatan wajib diisi.')
            ->minLength('nama_jabatan', 2, 'Nama jabatan minimal 2 karakter.')
            ->maxLength('nama_jabatan', 50, 'Nama jabatan maksimal 50 karakter.')
            ->string('nama_jabatan')
            ->required('divisi_id', 'Divisi wajib dipilih.');


        if ($validator->isValid()) {
            list($errors, $berhasil) = $this->divisiJabatanServices->tambahJabatan($errors, $old);

            if (!$berhasil) {
                $errors['general']           = $errors['general'] ?? 'Terjadi kesalahan saat menyimpan jabatan.';
                $_SESSION['errors_messages'] = $errors;
                $_SESSION['old_messages']    = $old;
                \setFlashMessage('danger', $errors['general']);

                return redirect(\BASE_URL . 'divisi-jabatan/create-jabatan');
            }
        } else {
            $errors                      = $validator->getErrors();
            $errors['general']           = 'Terjadi kesalahan saat menyimpan jabatan.';
            $_SESSION['errors_messages'] = $errors;
            $_SESSION['old_messages']    = $old;
            \setFlashMessage('danger', $errors['general']);
            return redirect(\BASE_URL . 'divisi-jabatan/create-jabatan');
        }



        \setFlashMessage('success', 'Jabatan berhasil ditambahkan.');
        $_SESSION['errors_messages'] = '';
        $_SESSION['old_messages']    = '';
        return redirect(\BASE_URL . 'divisi-jabatan');
    }

    // =========================================================
    // HAPUS JABATAN
    // =========================================================
    public function jabatanDelete() {
        $errors    = [];
        $jabatanId = sanitize($_POST['jabatan_id'] ?? '');

        if (empty($jabatanId)) {
            \setFlashMessage('danger', 'ID jabatan tidak valid.');
            return redirect(\BASE_URL . 'divisi-jabatan');
        }

        list($errors, $berhasil) = $this->divisiJabatanServices->hapusJabatan($errors, $jabatanId);

        if (!$berhasil) {
            \setFlashMessage('danger', $errors['general'] ?? 'Gagal menghapus jabatan.');
            return redirect(\BASE_URL . 'divisi-jabatan');
        }

        \setFlashMessage('success', 'Jabatan berhasil dihapus.');
        return redirect(\BASE_URL . 'divisi-jabatan');
    }

    // =========================================================
    // API — Jabatan berdasarkan divisi (untuk dropdown dinamis)
    // =========================================================
    public function getJabatanByDivisi() {
        header('Content-Type: application/json');

        $divisiId = sanitize($_GET['divisi_id'] ?? '');

        if (empty($divisiId)) {
            echo json_encode([]);
            exit;
        }

        $jabatans = $this->jabatanModel
            ->select(['jabatan_id', 'nama_jabatan'])
            ->where('divisi_id', $divisiId)
            ->get();

        echo json_encode($jabatans);
        exit;
    }
}