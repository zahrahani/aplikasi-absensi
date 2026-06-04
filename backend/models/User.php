<?php
/**
 * =====================================================
 * MODEL: User
 * Menangani operasi database untuk tabel users
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */
namespace Models;

class User {
    private $db;
    private $table = 'users';

    /**
     * Constructor
     */
    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Mendapatkan semua user
     */
    public function getAll() {
        $sql = "SELECT id, username, email, nama_lengkap, role, foto_profil, created_at
                FROM {$this->table}
                ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Mendapatkan user berdasarkan ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Mendapatkan user berdasarkan user_id
     */
    public function getByUserId($id) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Mendapatkan user berdasarkan username
     */
    public function getByUsername($username) {
        $sql = "SELECT * FROM {$this->table} WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    /**
     * Mendapatkan user berdasarkan email
     */
    public function getByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Mendapatkan user berdasarkan remember token
     */
    public function getByRememberToken($token) {
        $sql = "SELECT * FROM {$this->table} WHERE remember_token = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    /**
     * Cek apakah username sudah ada
     */
    public function usernameExists($username, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE username = ?";
        $params = [$username];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Cek apakah email sudah ada
     */
    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE email = ?";
        $params = [$email];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Membuat data baru
     */
    public function create($data = []) {

        $columns = implode(', ', array_keys($data));

        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $values = array_values($data);

        $sql = "INSERT INTO {$this->table} ({$columns})
        VALUES ({$placeholders})";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($values);

        return $result;
    }

    /**
     * Update user
     */
    public function update($id, $data) {
        $fields = [];
        $params = [];

        // Build query dinamis
        foreach ($data as $key => $value) {
            if ($key === 'password') {
                $fields[] = "{$key} = ?";
                $params[] = password_hash($value, PASSWORD_DEFAULT);
            } else {
                $fields[] = "{$key} = ?";
                $params[] = $value;
            }
        }

        $params[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Update remember token
     */
    public function updateRememberToken($id, $token) {
        $sql = "UPDATE {$this->table} SET remember_token = ? WHERE id = ? OR user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$token, $id, $id]);
    }

    /**
     * Hapus remember token
     */
    public function clearRememberToken($id) {
        return $this->updateRememberToken($id, null);
    }

    /**
     * Hapus remember token yang digunakan untuk api
     */
    public function clearApiRememberToken($rememberToken) {
        return $this->updateData(
            ['remember_token' => null], [
                "remember_token" => $rememberToken
            ]
        );
    }

    /**
     * Hapus user
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Verifikasi password
     */
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Autentikasi user (login)
     */
    public function authenticate($username, $password) {
        // Cari user berdasarkan username atau email
        $sql = "SELECT * FROM {$this->table} WHERE username = ? OR email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        if ($user && $this->verifyPassword($password, $user['password'])) {
            return $user;
        }
        return false;
    }


    /**
     * Hitung total user
     */
    public function count() {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return $stmt->fetchColumn();
    }

    public function updateData($data = [], $where = []) {

        if (empty($data)) return false;

    // SET
        $set = implode(', ', array_map(fn($col) => "{$col} = ?", array_keys($data)));

    // WHERE
        $whereClause = implode(' AND ', array_map(fn($col) => "{$col} = ?", array_keys($where)));

        $sql = "UPDATE {$this->table} SET {$set} WHERE {$whereClause}";

        $stmt = $this->db->prepare($sql);

    // values: data dulu, lalu where
        $values = array_merge(array_values($data), array_values($where));

        return $stmt->execute($values);
    }


    /**
     * Mendapatkan remember_token berdasarkan user_id
     */
    public function getRememberToken($id) {
        $sql = "SELECT remember_token FROM {$this->table} WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

}
