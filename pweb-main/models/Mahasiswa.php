<?php
/**
 * =====================================================
 * MODEL: Mahasiswa
 * Menangani operasi database untuk tabel mahasiswa
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */

class Mahasiswa {
    private $db;
    private $table = 'mahasiswa';

    /**
     * Constructor
     */
    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Mendapatkan semua mahasiswa
     */
    public function getAll($orderBy = 'created_at', $order = 'DESC') {
        $allowedColumns = ['id', 'nim', 'nama', 'jurusan', 'semester', 'created_at'];
        $orderBy = in_array($orderBy, $allowedColumns) ? $orderBy : 'created_at';
        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$order}";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Mendapatkan mahasiswa dengan pagination
     */
    public function getPaginated($page = 1, $perPage = 10, $search = '') {
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT * FROM {$this->table}";
        $params = [];

        if (!empty($search)) {
            $sql .= " WHERE nim LIKE ? OR nama LIKE ? OR jurusan LIKE ?";
            $searchTerm = "%{$search}%";
            $params = [$searchTerm, $searchTerm, $searchTerm];
        }

        $sql .= " ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Hitung total data (untuk pagination)
     */
    public function countAll($search = '') {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        $params = [];

        if (!empty($search)) {
            $sql .= " WHERE nim LIKE ? OR nama LIKE ? OR jurusan LIKE ?";
            $searchTerm = "%{$search}%";
            $params = [$searchTerm, $searchTerm, $searchTerm];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    /**
     * Mendapatkan mahasiswa berdasarkan ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Mendapatkan mahasiswa berdasarkan NIM
     */
    public function getByNim($nim) {
        $sql = "SELECT * FROM {$this->table} WHERE nim = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nim]);
        return $stmt->fetch();
    }

    /**
     * Cek apakah NIM sudah ada
     */
    public function nimExists($nim, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE nim = ?";
        $params = [$nim];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Membuat mahasiswa baru
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table}
                (nim, nama, email, jurusan, semester, alamat, telepon, foto)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $data['nim'],
            $data['nama'],
            $data['email'],
            $data['jurusan'],
            $data['semester'],
            $data['alamat'] ?? null,
            $data['telepon'] ?? null,
            $data['foto'] ?? null
        ]);

        if ($result) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Update mahasiswa
     */
    public function update($id, $data) {
        $fields = [];
        $params = [];

        // Build query dinamis
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = ?";
            $params[] = $value;
        }

        $params[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Hapus mahasiswa
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Mendapatkan statistik mahasiswa per jurusan
     */
    public function getStatsByJurusan() {
        $sql = "SELECT jurusan, COUNT(*) as total
                FROM {$this->table}
                GROUP BY jurusan
                ORDER BY total DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Mendapatkan statistik mahasiswa per semester
     */
    public function getStatsBySemester() {
        $sql = "SELECT semester, COUNT(*) as total
                FROM {$this->table}
                GROUP BY semester
                ORDER BY semester ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Hitung total mahasiswa
     */
    public function count() {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return $stmt->fetchColumn();
    }

    /**
     * Cari mahasiswa
     */
    public function search($keyword) {
        $sql = "SELECT * FROM {$this->table}
                WHERE nim LIKE ? OR nama LIKE ? OR email LIKE ? OR jurusan LIKE ?
                ORDER BY nama ASC";

        $searchTerm = "%{$keyword}%";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }
}
