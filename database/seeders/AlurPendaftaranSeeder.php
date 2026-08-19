<?php

namespace Database\Seeders;

use App\Models\AlurPendaftaran;
use Illuminate\Database\Seeder;

class AlurPendaftaranSeeder extends Seeder
{
    public function run(): void
    {
        $steps = [
            ['icon' => 'bi-person-plus-fill', 'judul' => 'Daftar / Masuk Akun', 'deskripsi' => 'Buat akun baru menggunakan nomor telepon, atau masuk jika sudah punya akun.'],
            ['icon' => 'bi-file-earmark-text-fill', 'judul' => 'Isi Formulir', 'deskripsi' => 'Lengkapi data diri, orang tua, dan pilih program studi.'],
            ['icon' => 'bi-credit-card-2-front-fill', 'judul' => 'Transfer & Konfirmasi', 'deskripsi' => 'Transfer biaya formulir lalu unggah bukti transfer.'],
            ['icon' => 'bi-clipboard-check-fill', 'judul' => 'Verifikasi Panitia', 'deskripsi' => 'Panitia memeriksa berkas & pembayaran maks. 2x24 jam.'],
            ['icon' => 'bi-megaphone-fill', 'judul' => 'Pengumuman Hasil', 'deskripsi' => 'Cek status kapan saja untuk melihat hasil seleksi.'],
        ];

        foreach ($steps as $i => $step) {
            AlurPendaftaran::updateOrCreate(
                ['judul' => $step['judul']],
                [...$step, 'urutan' => $i + 1]
            );
        }
    }
}