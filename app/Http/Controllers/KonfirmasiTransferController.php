<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKonfirmasiTransferRequest;
use App\Models\Gelombang;
use App\Models\Pembayaran;
use App\Models\Pendaftar;
use App\Models\Rekening;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KonfirmasiTransferController extends Controller
{
    public function create(): View
    {
        $rekeningList = Rekening::where('is_active', true)->orderBy('urutan')->get();
        $gelombangAktif = Gelombang::aktif()->orderBy('urutan')->first();

        return view('pages.konfirmasi-transfer', compact('rekeningList', 'gelombangAktif'));
    }

    public function store(StoreKonfirmasiTransferRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $pendaftar = Pendaftar::where('no_pendaftaran', $data['no_pendaftaran'])->first();

        $namaPendaftar = strtolower(trim($pendaftar->nama_lengkap));
        $namaInput = strtolower(trim($data['nama_siswa']));
        $namaCocok = str_contains($namaPendaftar, $namaInput) || str_contains($namaInput, $namaPendaftar);

        if (! $namaCocok) {
            return back()
                ->withInput()
                ->withErrors(['nama_siswa' => 'Nama tidak sesuai dengan data pada nomor pendaftaran tersebut.']);
        }

        $buktiPath = $request->file('bukti_transfer')->store('bukti-transfer', 'public');

        $pembayaran = Pembayaran::create([
            'pendaftar_id' => $pendaftar->id,
            'rekening_id' => $data['rekening_id'],
            'bank_pengirim' => $data['bank_pengirim'],
            'tanggal_transfer' => $data['tanggal_transfer'],
            'nominal_transfer' => $data['nominal_transfer'],
            'bukti_transfer_path' => $buktiPath,
            'catatan' => $data['catatan'] ?? null,
            'status' => 'menunggu',
        ]);

        if ($pendaftar->status_pembayaran === 'belum_bayar') {
            $pendaftar->update(['status_pembayaran' => 'menunggu_verifikasi']);
        }

        return redirect()->route('konfirmasi-transfer.sukses', $pembayaran->id);
    }

    public function sukses(Pembayaran $pembayaran): View
    {
        $pembayaran->load('pendaftar');

        return view('pages.konfirmasi-transfer-sukses', compact('pembayaran'));
    }
}