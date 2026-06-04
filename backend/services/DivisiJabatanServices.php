<?php

/**
 * =====================================================
 * SERVICE: DivisiJabatanServices
 * Menangani logika bisnis untuk Divisi & Jabatan
 * Praktikum Aplikasi Web - Universitas Tidar
 * =====================================================
 */

namespace Services;

use Models\Divisi as DivisiModel;
use Models\Jabatan as JabatanModel;
use Models\Karyawan as KaryawanModel;


class DivisiJabatanServices {

    private $divisiModel;
    private $jabatanModel;
    private $karyawanModel;

    /**
     * Constructor
     */
    public function __construct() {
        $this->divisiModel   = new DivisiModel();
        $this->jabatanModel  = new JabatanModel();
        $this->karyawanModel = new KaryawanModel();
    }

    /**
     * Simpan divisi baru ke database
     */
    public function tambahDivisi(array $errors, array $data): array {
        $berhasil = false;

        try {

            $divisiId = $this->generateDivisiId();

            $result = $this->divisiModel->create([
                'divisi_id'   => $divisiId,
                'nama_divisi' => $data['nama_divisi'],
            ]);

            $berhasil = (bool) $result;

        } catch (\Exception $e) {
            $errors['general'] = 'Gagal menyimpan divisi: ' . $e->getMessage();
        }

        return [$errors, $berhasil];
    }

    /**
     * Simpan jabatan baru ke database
     */
    public function tambahJabatan(array $errors, array $data): array {
        $berhasil = false;

        try {

            $jabatanId = $this->generateJabatanId();

            $result = $this->jabatanModel->create([
                'jabatan_id'   => $jabatanId,
                'divisi_id'    => $data['divisi_id'],
                'nama_jabatan' => $data['nama_jabatan'],
            ]);

            $berhasil = (bool) $result;

        } catch (\Exception $e) {
            $errors['general'] = 'Gagal menyimpan jabatan: ' . $e->getMessage();
        }

        return [$errors, $berhasil];
    }

    /**
     * Hapus divisi — cek karyawan aktif dulu sebelum hapus
     * Jika masih ada karyawan → kembalikan error, jangan hapus
     */
    public function hapusDivisi(array $errors, string $divisiId): array {
        $berhasil = false;

        // Cek apakah masih ada karyawan di divisi ini
        $karyawanAda = $this->karyawanModel
        ->select(['user_id'])
        ->where('divisi_id', $divisiId)
        ->get();

        if (!empty($karyawanAda)) {
            $jumlah            = count($karyawanAda);
            $errors['general'] = "Divisi tidak dapat dihapus karena masih memiliki {$jumlah} karyawan aktif. Pindahkan atau hapus karyawan dari divisi ini terlebih dahulu.";
            return [$errors, $berhasil];
        }

        try {
            // Hapus jabatan di divisi ini dulu (jaga foreign key constraint)
            $this->jabatanModel
            ->delete()
            ->where('divisi_id', $divisiId)
            ->execute();

            // Hapus divisi
            $berhasil = (bool) $this->divisiModel
            ->delete()
            ->where('divisi_id', $divisiId)
            ->execute();

        } catch (\Exception $e) {
            $errors['general'] = 'Gagal menghapus divisi: ' . $e->getMessage();
        }

        return [$errors, $berhasil];
    }

    /**
     * Hapus jabatan — cek karyawan aktif dulu sebelum hapus
     * Jika masih ada karyawan → kembalikan error, jangan hapus
     */
    public function hapusJabatan(array $errors, string $jabatanId): array {
        $berhasil = false;

        // Cek apakah masih ada karyawan dengan jabatan ini
        $karyawanAda = $this->karyawanModel
        ->select(['user_id'])
        ->where('jabatan_id', $jabatanId)
        ->get();

        if (!empty($karyawanAda)) {
            $jumlah            = count($karyawanAda);
            $errors['general'] = "Jabatan tidak dapat dihapus karena masih digunakan oleh {$jumlah} karyawan. Pindahkan atau hapus karyawan dari jabatan ini terlebih dahulu.";
            return [$errors, $berhasil];
        }

        try {
            $berhasil = (bool) $this->jabatanModel
            ->delete()
            ->where('jabatan_id', $jabatanId)
            ->execute();

        } catch (\Exception $e) {
            $errors['general'] = 'Gagal menghapus jabatan: ' . $e->getMessage();
        }

        return [$errors, $berhasil];
    }

    /**
     * Generate ID jabatan otomatis
     * Format: J01, J02, J03, ...
     */
    private function generateJabatanId(): string {
        $rows = $this->jabatanModel
        ->select(['jabatan_id'])
        ->get();
 
        $max = 0;
        foreach ($rows as $r) {
            $angka = (int) preg_replace('/[^0-9]/', '', $r['jabatan_id']);
            if ($angka > $max) $max = $angka;
        }
 
        return 'J' . str_pad($max + 1, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Generate ID divisi otomatis
     * Format: D01, D02, D03, ...
     * Cari ID terbesar yang ada lalu increment
     */
    private function generateDivisiId(): string {
        $rows = $this->divisiModel
        ->select(['divisi_id'])
        ->get();
 
        $max = 0;
        foreach ($rows as $r) {
            // Ambil angka dari ID, misal D03 → 3
            $angka = (int) preg_replace('/[^0-9]/', '', $r['divisi_id']);
            if ($angka > $max) $max = $angka;
        }
 
        return 'D' . str_pad($max + 1, 2, '0', STR_PAD_LEFT);
    }

}