-- =====================================================
-- DATABASE: db_akademik
-- Praktikum Aplikasi Web - Universitas Tidar
-- =====================================================

-- Membuat database
CREATE DATABASE IF NOT EXISTS db_akademik;
USE db_akademik;

-- =====================================================
-- TABEL: users
-- Untuk sistem autentikasi
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    foto_profil VARCHAR(255) DEFAULT NULL,
    remember_token VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABEL: mahasiswa
-- Data mahasiswa untuk CRUD
-- =====================================================
CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    jurusan VARCHAR(100) NOT NULL,
    semester INT NOT NULL,
    alamat TEXT,
    telepon VARCHAR(20),
    foto VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- DATA AWAL: Admin User
-- Password: admin123 (sudah di-hash)
-- =====================================================
INSERT INTO users (username, email, password, nama_lengkap, role) VALUES
('admin', 'admin@untidar.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin');

-- =====================================================
-- DATA CONTOH: Mahasiswa
-- =====================================================
INSERT INTO mahasiswa (nim, nama, email, jurusan, semester, alamat, telepon) VALUES
('2021001', 'Ahmad Fauzi', 'ahmad.fauzi@student.untidar.ac.id', 'Teknik Informatika', 6, 'Jl. Magelang No. 10', '081234567890'),
('2021002', 'Siti Nurhaliza', 'siti.nurhaliza@student.untidar.ac.id', 'Teknik Informatika', 6, 'Jl. Sudirman No. 25', '081234567891'),
('2021003', 'Budi Santoso', 'budi.santoso@student.untidar.ac.id', 'Sistem Informasi', 4, 'Jl. Ahmad Yani No. 15', '081234567892'),
('2021004', 'Dewi Lestari', 'dewi.lestari@student.untidar.ac.id', 'Teknik Informatika', 6, 'Jl. Pemuda No. 30', '081234567893'),
('2021005', 'Eko Prasetyo', 'eko.prasetyo@student.untidar.ac.id', 'Sistem Informasi', 4, 'Jl. Diponegoro No. 45', '081234567894');
