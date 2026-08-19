<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['pertanyaan' => 'Apa saja persyaratan pendaftaran?', 'jawaban' => 'Fotokopi ijazah/SKL SMP-MTs, kartu keluarga, akta kelahiran, pas foto 3x4, dan mengisi formulir pendaftaran online.'],
            ['pertanyaan' => 'Bagaimana cara pembayaran biaya pendaftaran?', 'jawaban' => 'Transfer ke rekening resmi sekolah, lalu unggah bukti transfer pada menu Konfirmasi Transfer untuk diverifikasi panitia.'],
            ['pertanyaan' => 'Berapa lama proses verifikasi berkas?', 'jawaban' => 'Maksimal 2x24 jam kerja setelah berkas dan bukti pembayaran lengkap diunggah.'],
            ['pertanyaan' => 'Apakah bisa pindah gelombang jika belum bayar?', 'jawaban' => 'Bisa, selama gelombang berikutnya masih dibuka. Biaya akan mengikuti ketentuan gelombang yang aktif.'],
        ];

        foreach ($items as $i => $item) {
            Faq::updateOrCreate(
                ['pertanyaan' => $item['pertanyaan']],
                [...$item, 'urutan' => $i + 1, 'is_active' => true]
            );
        }
    }
}