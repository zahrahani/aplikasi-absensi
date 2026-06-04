-- =====================================================
-- DATABASE
-- =====================================================
CREATE DATABASE IF NOT EXISTS db_presensi;
USE db_presensi;



-- =====================================================
-- TABEL USERS
-- =====================================================
CREATE TABLE users (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id CHAR(8) UNIQUE,

    username VARCHAR(50) NOT NULL UNIQUE,

    email VARCHAR(100) NOT NULL UNIQUE,

    password VARCHAR(255) NOT NULL,

    nama_lengkap VARCHAR(100) NOT NULL,

    role ENUM('admin', 'user') DEFAULT 'user',

    foto_profil VARCHAR(255) DEFAULT NULL,

    remember_token VARCHAR(255) DEFAULT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- =====================================================
-- TABEL DIVISI
-- =====================================================
CREATE TABLE divisi (

    divisi_id CHAR(3) PRIMARY KEY,

    nama_divisi VARCHAR(50)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- =====================================================
-- TABEL JABATAN
-- =====================================================
CREATE TABLE jabatan (

    jabatan_id CHAR(3) PRIMARY KEY,

    divisi_id CHAR(3) NOT NULL,

    nama_jabatan VARCHAR(50),

    FOREIGN KEY (divisi_id)
    REFERENCES divisi(divisi_id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- =====================================================
-- TABEL KARYAWAN
-- =====================================================
CREATE TABLE karyawan (

    user_id CHAR(8) PRIMARY KEY,

    divisi_id CHAR(3) NOT NULL,

    jabatan_id CHAR(3) NOT NULL,

    no_handphone VARCHAR(15),

    status VARCHAR(20),

    alamat TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,


    FOREIGN KEY (user_id)
    REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    FOREIGN KEY (divisi_id)
    REFERENCES divisi(divisi_id),

    FOREIGN KEY (jabatan_id)
    REFERENCES jabatan(jabatan_id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- =====================================================
-- TABEL SHIFT
-- =====================================================
CREATE TABLE shift_kerja (

    shift_id CHAR(3) PRIMARY KEY,

    nama_shift VARCHAR(30),

    jam_masuk TIME,

    jam_pulang TIME,

    batas_telat TIME,

    keterangan VARCHAR(100)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- =====================================================
-- TABEL JADWAL
-- =====================================================
CREATE TABLE jadwal_karyawan (

    jadwal_id BIGINT AUTO_INCREMENT PRIMARY KEY,

    user_id CHAR(8),

    shift_id CHAR(3),

    -- keterangan VARCHAR(100),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
    REFERENCES karyawan(user_id),

    FOREIGN KEY (shift_id)
    REFERENCES shift_kerja(shift_id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- =====================================================
-- TABEL JENIS ABSENSI
-- =====================================================
CREATE TABLE jenis_absensi (

    jenis_id CHAR(3) PRIMARY KEY,

    nama_jenis VARCHAR(30),

    color_hex VARCHAR(20),

    icon_name VARCHAR(50),

    keterangan VARCHAR(100)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- =====================================================
-- TABEL PENGAJUAN
-- =====================================================
CREATE TABLE pengajuan_absensi (

    pengajuan_id BIGINT AUTO_INCREMENT PRIMARY KEY,

    user_id CHAR(8),

    jenis_id CHAR(3),

    tanggal_mulai DATE DEFAULT NULL,

    tanggal_selesai DATE DEFAULT NULL,

    alasan TEXT,

    lampiran VARCHAR(255),

    is_urgent TINYINT(1) DEFAULT 0,

    status_pengajuan ENUM(
        'pending',
        'approved',
        'rejected'
    ) DEFAULT 'pending',

    approved_by INT DEFAULT NULL,

    approved_at DATETIME DEFAULT NULL,

    catatan_admin TEXT DEFAULT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
    REFERENCES karyawan(user_id),

    FOREIGN KEY (jenis_id)
    REFERENCES jenis_absensi(jenis_id),

    FOREIGN KEY (approved_by)
    REFERENCES users(id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- =====================================================
-- TABEL ABSENSI
-- =====================================================
CREATE TABLE absensi (

    absensi_id BIGINT AUTO_INCREMENT PRIMARY KEY,

    user_id CHAR(8),

    shift_id CHAR(3),

    jenis_id CHAR(3),

    tanggal DATE,

    jam_masuk DATETIME DEFAULT NULL,

    jam_pulang DATETIME DEFAULT NULL,

    terlambat_menit INT DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
    REFERENCES karyawan(user_id),

    FOREIGN KEY (shift_id)
    REFERENCES shift_kerja(shift_id),

    FOREIGN KEY (jenis_id)
    REFERENCES jenis_absensi(jenis_id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- =====================================================
-- TABEL APPROVAL HISTORY
-- =====================================================
CREATE TABLE approval_history (

    history_id BIGINT AUTO_INCREMENT PRIMARY KEY,

    pengajuan_id BIGINT NOT NULL,

    action_type ENUM(
        'created',
        'approved',
        'rejected'
    ) NOT NULL,

    action_label VARCHAR(100),

    action_time DATETIME DEFAULT CURRENT_TIMESTAMP,

    action_by INT DEFAULT NULL,

    notes TEXT,

    FOREIGN KEY (pengajuan_id)
    REFERENCES pengajuan_absensi(pengajuan_id)
        ON DELETE CASCADE,

    FOREIGN KEY (action_by)
    REFERENCES users(id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- =====================================================
-- DATA DIVISI
-- =====================================================
INSERT INTO divisi VALUES
('D01', 'Human Resource'),
('D02', 'IT');



-- =====================================================
-- DATA JABATAN
-- =====================================================
INSERT INTO jabatan VALUES
('J01', 'D01', 'HR Manager'),
('J02', 'D02', 'Frontend Developer');



-- =====================================================
-- DATA USERS
-- password = admin123
-- =====================================================
INSERT INTO users
(
    user_id,
    username,
    email,
    password,
    nama_lengkap,
    role
)
VALUES

(
    'USR00001',
    'admin',
    'admin@gmail.com',
    '$2a$12$Y8pzKv0LV8SkZY5BaYhgROpFXRxy19uwzKABZJL5X0rGGhqwIHyTi',
    'Administrator',
    'admin'
),

(
    'USR00002',
    'dimas',
    'dimas@gmail.com',
    '$2a$12$Y8pzKv0LV8SkZY5BaYhgROpFXRxy19uwzKABZJL5X0rGGhqwIHyTi',
    'Dimas Arya',
    'user'
);



-- =====================================================
-- DATA KARYAWAN
-- =====================================================
INSERT INTO karyawan
(
    user_id,
    divisi_id,
    jabatan_id,
    no_handphone,
    status,
    alamat
)
VALUES

(
    'USR00002',
    'D02',
    'J02',
    '081234567890',
    'Aktif',
    'Yogyakarta'
);



-- =====================================================
-- DATA SHIFT
-- =====================================================
INSERT INTO shift_kerja VALUES

(
    'S01',
    'Shift Pagi',
    '08:00:00',
    '16:00:00',
    '08:15:00',
    'Jam kerja pagi'
),
(
    'S02',
    'Shift Sore',
    '16:00:00',
    '00:00:00',
    '16:15:00',
    'Jam kerja sore'
),
(
    'S03',
    'Shift Malam',
    '00:00:00',
    '08:00:00',
    '00:15:00',
    'Jam kerja malam'
);



-- =====================================================
-- DATA JADWAL
-- =====================================================
INSERT INTO jadwal_karyawan
(
    user_id,
    shift_id
)
VALUES

(
    'USR00002',
    'S01'
);



-- =====================================================
-- DATA JENIS ABSENSI
-- =====================================================
INSERT INTO jenis_absensi VALUES

('J01', 'Hadir', '#4CAF50', 'check', 'Hadir tepat waktu'),

('J02', 'Telat', '#9B59B6', 'schedule', 'Datang terlambat'),

('J03', 'Sakit', '#808080', 'sick', 'Tidak masuk karena sakit'),

('J04', 'Cuti', '#F5A623', 'event', 'Cuti tahunan'),

('J05', 'WFH', '#1900A7', 'home', 'Work from home');



-- =====================================================
-- DATA ABSENSI NORMAL
-- =====================================================
INSERT INTO absensi
(
    user_id,
    shift_id,
    jenis_id,
    tanggal,
    jam_masuk,
    jam_pulang,
    terlambat_menit
)
VALUES
(
    'USR00002',
    'S01',
    'J01',
    '2026-05-19',
    '2026-05-19 07:58:00',
    '2026-05-19 16:00:00',
    0
),
(
    'USR00002',
    'S01',
    'J02',
    '2026-05-20',
    '2026-05-20 08:20:00',
    '2026-05-20 16:05:00',
    20
);



-- =====================================================
-- DATA PENGAJUAN
-- =====================================================
INSERT INTO pengajuan_absensi
(
    user_id,
    jenis_id,
    tanggal_mulai,
    tanggal_selesai,
    alasan,
    lampiran,
    is_urgent,
    status_pengajuan,
    approved_by,
    approved_at,
    catatan_admin
)
VALUES

(
    'USR00002',
    'J03',
    '2026-05-21',
    '2026-05-22',
    'Demam tinggi dan disarankan istirahat',
    'surat_dokter.pdf',
    1,
    'approved',
    1,
    '2026-05-21 08:00:00',
    'Disetujui admin'
);



-- =====================================================
-- ABSENSI OTOMATIS DARI PENGAJUAN
-- =====================================================
INSERT INTO absensi
(
    user_id,
    shift_id,
    jenis_id,
    tanggal
)
VALUES

(
    'USR00002',
    'S01',
    'J03',
    '2026-05-21' 
),

(
    'USR00002',
    'S01',
    'J03',
    '2026-05-22'
);



-- =====================================================
-- APPROVAL HISTORY
-- =====================================================
INSERT INTO approval_history
(
    pengajuan_id,
    action_type,
    action_label,
    action_time,
    action_by,
    notes
)
VALUES

(
    1,
    'created',
    'Pengajuan dibuat',
    '2026-05-21 07:00:00',
    NULL,
    'Pengajuan dibuat dari aplikasi'
),

(
    1,
    'approved',
    'Pengajuan disetujui admin',
    '2026-05-21 08:00:00',
    1,
    'Admin menyetujui pengajuan'
);