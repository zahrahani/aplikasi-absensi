# Sistem Akademik - PHP Native MVC

Project PHP Native dengan arsitektur MVC untuk Praktikum Aplikasi Web, Universitas Tidar.

## Struktur Folder

```
project-mvc-php/
├── config/
│   ├── config.php          # Konfigurasi aplikasi
│   └── database.php         # Koneksi database (Singleton)
├── controllers/
│   ├── AuthController.php   # Login, Register, Logout, Profile
│   └── MahasiswaController.php  # CRUD Mahasiswa
├── includes/
│   ├── auth.php             # Helper functions authentication
│   ├── validation.php       # Class Validator
│   └── FileHandler.php      # Class untuk upload file
├── models/
│   ├── User.php             # Model User
│   └── Mahasiswa.php        # Model Mahasiswa
├── views/
│   ├── layouts/
│   │   └── main.php         # Layout utama
│   ├── auth/
│   │   ├── login.php        # Halaman login
│   │   ├── register.php     # Halaman register
│   │   └── profile.php      # Halaman profil
│   ├── mahasiswa/
│   │   ├── index.php        # Daftar mahasiswa
│   │   ├── create.php       # Form tambah
│   │   ├── edit.php         # Form edit
│   │   └── show.php         # Detail mahasiswa
│   └── dashboard.php        # Halaman dashboard
├── public/
│   ├── css/
│   │   └── style.css        # Custom CSS
│   └── js/
│       └── validasi.js      # JavaScript validation
├── uploads/
│   └── mahasiswa/           # Folder upload foto mahasiswa
├── database/
│   └── db_akademik.sql      # File SQL database
├── index.php                # Front Controller / Router
├── .htaccess                # Konfigurasi Apache
└── README.md                # Dokumentasi
```

## Instalasi

### 1. Setup Database

1. Buat database baru di phpMyAdmin atau MySQL CLI
2. Import file `database/db_akademik.sql`

```sql
-- Via MySQL CLI
mysql -u root -p < database/db_akademik.sql
```

### 2. Konfigurasi Database

Edit file `config/database.php`:

```php
private $host = 'localhost';
private $dbname = 'db_akademik';
private $username = 'root';
private $password = '';
```

### 3. Konfigurasi Base URL

Edit file `config/config.php`:

```php
// Development
define('BASE_URL', 'http://localhost/project-mvc-php/');

// Production
// define('BASE_URL', 'https://yourdomain.com/');
```

### 4. Jalankan Aplikasi

1. Letakkan folder project di `htdocs` (XAMPP) atau `www` (Laragon)
2. Akses `http://localhost/project-mvc-php/`

## Akun Default

```
Username: admin
Password: password
Role: admin
```

## Fitur

### Authentication
- Login dengan username/email
- Register user baru
- Logout
- Remember Me (cookie)
- Session management
- Password hashing (bcrypt)

### CRUD Mahasiswa
- Daftar mahasiswa dengan pagination
- Pencarian mahasiswa
- Tambah mahasiswa baru
- Edit data mahasiswa
- Hapus mahasiswa
- Upload foto mahasiswa

### Validasi
- Client-side validation (JavaScript)
- Server-side validation (PHP)
- CSRF protection

### File Upload
- Validasi tipe file (MIME type)
- Validasi ukuran file (max 2MB)
- Validasi ekstensi file
- Rename file otomatis

### UI/UX
- Bootstrap 5 responsive design
- Bootstrap Icons
- Flash messages
- Confirmation modals
- Image preview

## Teknologi

- PHP 7.4+
- MySQL 5.7+ / MariaDB
- PDO dengan Prepared Statements
- Bootstrap 5.3
- Bootstrap Icons
- JavaScript ES6

## Security

- Password hashing dengan `password_hash()`
- PDO Prepared Statements (SQL Injection prevention)
- `htmlspecialchars()` untuk output (XSS prevention)
- Session security
- File upload validation
- .htaccess protection

## Author

Praktikum Aplikasi Web
Program Studi Teknik Informatika
Universitas Tidar

## License

Educational use only.
