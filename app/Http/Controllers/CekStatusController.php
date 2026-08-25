<?php

namespace App\Http\Controllers;

use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CekStatusController extends Controller
{
    public function index(Request $request): View
    {
        $cariInput = trim((string) $request->query('cari', ''));
        $sudahCari = $cariInput !== '';
        $hasil = null;
        $stage = 0;
        $badge = null;

        if ($sudahCari) {
            $hasil = Pendaftar::with(['jurusan', 'gelombang'])
                ->where('no_pendaftaran', $cariInput)
                ->orWhere('whatsapp_siswa', $cariInput)
                ->first();
        }

        if ($hasil) {
            $stage = $this->hitungStage($hasil);
            $badge = $this->tentukanBadge($stage, $hasil->hasil_seleksi);
        }

        return view('pages.cek-status', compact('sudahCari', 'cariInput', 'hasil', 'stage', 'badge'));
    }

    protected function hitungStage(Pendaftar $pendaftar): int
    {
        $stage = 1;

        if (in_array($pendaftar->status_pembayaran, ['menunggu_verifikasi', 'terverifikasi'], true)) {
            $stage = 2;
        }

        if ($pendaftar->status_pembayaran === 'terverifikasi' && $pendaftar->status_verifikasi_berkas === 'terverifikasi') {
            $stage = 3;
        }

        if ($pendaftar->hasil_seleksi !== 'menunggu') {
            $stage = 4;
        }

        return $stage;
    }

    protected function tentukanBadge(int $stage, string $hasilSeleksi): array
    {
        if ($stage === 4 && $hasilSeleksi === 'diterima') {
            return ['class' => 'badge-diterima', 'icon' => 'bi-check-circle-fill', 'text' => 'Dinyatakan Diterima'];
        }

        if ($stage === 4 && $hasilSeleksi === 'ditolak') {
            return ['class' => 'badge-ditolak', 'icon' => 'bi-x-circle-fill', 'text' => 'Belum Beruntung'];
        }

        if ($stage < 2) {
            return ['class' => 'badge-pending', 'icon' => 'bi-clock-fill', 'text' => 'Menunggu Pembayaran'];
        }

        return ['class' => 'badge-proses', 'icon' => 'bi-arrow-repeat', 'text' => 'Sedang Diproses'];
    }
}