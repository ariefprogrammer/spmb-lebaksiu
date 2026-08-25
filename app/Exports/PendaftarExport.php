<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles; 
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; 
use PhpOffice\PhpSpreadsheet\Style\Alignment; 
use PhpOffice\PhpSpreadsheet\Style\Fill; 

class PendaftarExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $query;
    private int $rowNumber = 0; 

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection(): Collection
    {
        return $this->query->get();
    }

    public function headings(): array
    {
        return [
            'No.', 
            'No. Pendaftaran', 'Nama Lengkap', 'Jenis Kelamin', 'Tempat, Tanggal Lahir',
            'NISN', 'NIK', 'Asal Sekolah', 'Jurusan', 'Gelombang', 'WhatsApp Siswa',
            'Email Siswa', 'Alamat', 'Desa/Kelurahan', 'Kecamatan', 'Kabupaten',
            'Nama Ibu', 'Nama Ayah', 'WhatsApp Orang Tua', 'Memiliki KIP',
            'Status Pembayaran', 'Status Verifikasi Berkas', 'Hasil Seleksi',
            'Status Daftar Ulang', 'Tanggal Daftar',
        ];
    }

    public function map($p): array
    {
        $this->rowNumber++; 

        return [
            $this->rowNumber, 
            $p->no_pendaftaran,
            $p->nama_lengkap,
            $p->jenis_kelamin === 'laki-laki' ? 'Laki-laki' : 'Perempuan',
            $p->tempat_lahir . ', ' . ($p->tanggal_lahir ? $p->tanggal_lahir->translatedFormat('d F Y') : ''),
            $p->nisn,
            $p->nik,
            $p->asal_sekolah,
            $p->jurusan->nama ?? '-',
            $p->gelombang->nama ?? '-',
            $p->whatsapp_siswa,
            $p->email_siswa,
            $p->alamat_lengkap,
            $p->desa_kelurahan,
            $p->kecamatan,
            $p->kabupaten,
            $p->nama_ibu,
            $p->nama_ayah,
            $p->whatsapp_ortu,
            $p->punya_kip ? 'Ya' : 'Tidak',
            ucwords(str_replace('_', ' ', $p->status_pembayaran)),
            ucwords(str_replace('_', ' ', $p->status_verifikasi_berkas)),
            ucfirst($p->hasil_seleksi),
            $p->daftar_ulang_at ? 'Sudah Daftar Ulang' : 'Belum Daftar Ulang',
            $p->created_at ? $p->created_at->format('d-m-Y H:i') : '',
        ];
    }

    /**
     * Styling untuk worksheet Excel
     */
    public function styles(Worksheet $sheet): ?array
    {
        return [
            // Target Baris 1 (Header)
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFF'], // Teks Putih
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER, // Rata Tengah Horizontal
                    'vertical' => Alignment::VERTICAL_CENTER,     // Rata Tengah Vertikal
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '101938'], // Background Biru (Kode Hex: #101938 - Royal Blue / Navy)
                ],
            ],
        ];
    }
}