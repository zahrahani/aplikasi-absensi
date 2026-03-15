<?php
/**
 * =====================================================
 * CONTROLLER: MahasiswaController
 * Menangani CRUD data mahasiswa
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */

require_once MODELS_PATH . 'Mahasiswa.php';

class MahasiswaController {
    private $mahasiswaModel;
    private $fileHandler;

    /**
     * Constructor
     */
    public function __construct() {
        $this->mahasiswaModel = new Mahasiswa();
        $this->fileHandler = new FileHandler();
    }

    /**
     * Halaman Daftar Mahasiswa (Index)
     */
    public function index() {
        requireLogin();

        // Pagination
        $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $perPage = 10;
        $search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

        // Get data
        $mahasiswa = $this->mahasiswaModel->getPaginated($page, $perPage, $search);
        $totalData = $this->mahasiswaModel->countAll($search);
        $totalPages = ceil($totalData / $perPage);

        // Tampilkan view
        $pageTitle = 'Data Mahasiswa';
        include VIEWS_PATH . 'mahasiswa/index.php';
    }

    /**
     * Halaman Tambah Mahasiswa (Create)
     */
    public function create() {
        requireLogin();

        $errors = [];
        $old = [];

        // Proses form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old = [
                'nim' => sanitize($_POST['nim'] ?? ''),
                'nama' => sanitize($_POST['nama'] ?? ''),
                'email' => sanitize($_POST['email'] ?? ''),
                'jurusan' => sanitize($_POST['jurusan'] ?? ''),
                'semester' => sanitize($_POST['semester'] ?? ''),
                'alamat' => sanitize($_POST['alamat'] ?? ''),
                'telepon' => sanitize($_POST['telepon'] ?? '')
            ];

            // Validasi
            $validator = validate($_POST);
            $validator->required('nim', 'NIM wajib diisi.')
                      ->minLength('nim', 5, 'NIM minimal 5 karakter.')
                      ->required('nama', 'Nama wajib diisi.')
                      ->minLength('nama', 3, 'Nama minimal 3 karakter.')
                      ->required('email', 'Email wajib diisi.')
                      ->email('email', 'Format email tidak valid.')
                      ->required('jurusan', 'Jurusan wajib diisi.')
                      ->required('semester', 'Semester wajib diisi.')
                      ->integer('semester', 'Semester harus berupa angka.')
                      ->min('semester', 1, 'Semester minimal 1.')
                      ->max('semester', 14, 'Semester maksimal 14.');

            if ($validator->isValid()) {
                // Cek NIM sudah ada
                if ($this->mahasiswaModel->nimExists($old['nim'])) {
                    $errors['nim'] = 'NIM sudah terdaftar.';
                } else {
                    $foto = null;

                    // Upload foto jika ada
                    if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
                        $uploadResult = $this->fileHandler->upload($_FILES['foto'], 'mahasiswa');
                        if ($uploadResult) {
                            $foto = $uploadResult;
                        } else {
                            $errors['foto'] = $this->fileHandler->getFirstError();
                        }
                    }

                    if (empty($errors)) {
                        // Simpan data
                        $data = $old;
                        $data['foto'] = $foto;

                        $id = $this->mahasiswaModel->create($data);

                        if ($id) {
                            setFlashMessage('success', 'Data mahasiswa berhasil ditambahkan.');
                            redirect('index.php?page=mahasiswa');
                        } else {
                            $errors['general'] = 'Terjadi kesalahan saat menyimpan data.';
                        }
                    }
                }
            } else {
                $errors = $validator->getErrors();
            }
        }

        // Tampilkan view
        $pageTitle = 'Tambah Mahasiswa';
        include VIEWS_PATH . 'mahasiswa/create.php';
    }

    /**
     * Halaman Edit Mahasiswa
     */
    public function edit() {
        requireLogin();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $mahasiswa = $this->mahasiswaModel->getById($id);

        if (!$mahasiswa) {
            setFlashMessage('error', 'Data mahasiswa tidak ditemukan.');
            redirect('index.php?page=mahasiswa');
        }

        $errors = [];

        // Proses form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nim' => sanitize($_POST['nim'] ?? ''),
                'nama' => sanitize($_POST['nama'] ?? ''),
                'email' => sanitize($_POST['email'] ?? ''),
                'jurusan' => sanitize($_POST['jurusan'] ?? ''),
                'semester' => sanitize($_POST['semester'] ?? ''),
                'alamat' => sanitize($_POST['alamat'] ?? ''),
                'telepon' => sanitize($_POST['telepon'] ?? '')
            ];

            // Validasi
            $validator = validate($_POST);
            $validator->required('nim', 'NIM wajib diisi.')
                      ->minLength('nim', 5, 'NIM minimal 5 karakter.')
                      ->required('nama', 'Nama wajib diisi.')
                      ->minLength('nama', 3, 'Nama minimal 3 karakter.')
                      ->required('email', 'Email wajib diisi.')
                      ->email('email', 'Format email tidak valid.')
                      ->required('jurusan', 'Jurusan wajib diisi.')
                      ->required('semester', 'Semester wajib diisi.')
                      ->integer('semester', 'Semester harus berupa angka.')
                      ->min('semester', 1, 'Semester minimal 1.')
                      ->max('semester', 14, 'Semester maksimal 14.');

            if ($validator->isValid()) {
                // Cek NIM sudah ada (exclude current ID)
                if ($this->mahasiswaModel->nimExists($data['nim'], $id)) {
                    $errors['nim'] = 'NIM sudah terdaftar.';
                } else {
                    // Upload foto baru jika ada
                    if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
                        $uploadResult = $this->fileHandler->upload($_FILES['foto'], 'mahasiswa');
                        if ($uploadResult) {
                            // Hapus foto lama
                            if ($mahasiswa['foto']) {
                                $this->fileHandler->delete($mahasiswa['foto'], 'mahasiswa');
                            }
                            $data['foto'] = $uploadResult;
                        } else {
                            $errors['foto'] = $this->fileHandler->getFirstError();
                        }
                    }

                    if (empty($errors)) {
                        // Update data
                        $result = $this->mahasiswaModel->update($id, $data);

                        if ($result) {
                            setFlashMessage('success', 'Data mahasiswa berhasil diperbarui.');
                            redirect('index.php?page=mahasiswa');
                        } else {
                            $errors['general'] = 'Terjadi kesalahan saat memperbarui data.';
                        }
                    }
                }
            } else {
                $errors = $validator->getErrors();
            }

            // Update data mahasiswa untuk form
            $mahasiswa = array_merge($mahasiswa, $data);
        }

        // Tampilkan view
        $pageTitle = 'Edit Mahasiswa';
        include VIEWS_PATH . 'mahasiswa/edit.php';
    }

    /**
     * Halaman Detail Mahasiswa (Show)
     */
    public function show() {
        requireLogin();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $mahasiswa = $this->mahasiswaModel->getById($id);

        if (!$mahasiswa) {
            setFlashMessage('error', 'Data mahasiswa tidak ditemukan.');
            redirect('index.php?page=mahasiswa');
        }

        // Tampilkan view
        $pageTitle = 'Detail Mahasiswa';
        include VIEWS_PATH . 'mahasiswa/show.php';
    }

    /**
     * Hapus Mahasiswa (Delete)
     */
    public function delete() {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php?page=mahasiswa');
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $mahasiswa = $this->mahasiswaModel->getById($id);

        if (!$mahasiswa) {
            setFlashMessage('error', 'Data mahasiswa tidak ditemukan.');
            redirect('index.php?page=mahasiswa');
        }

        // Hapus foto jika ada
        if ($mahasiswa['foto']) {
            $this->fileHandler->delete($mahasiswa['foto'], 'mahasiswa');
        }

        // Hapus data
        $result = $this->mahasiswaModel->delete($id);

        if ($result) {
            setFlashMessage('success', 'Data mahasiswa berhasil dihapus.');
        } else {
            setFlashMessage('error', 'Gagal menghapus data mahasiswa.');
        }

        redirect('index.php?page=mahasiswa');
    }
}
