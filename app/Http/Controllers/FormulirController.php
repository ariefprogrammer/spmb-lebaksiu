<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePendaftarRequest;
use App\Models\Gelombang;
use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\Pendaftar;
use App\Models\Rekening;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FormulirController extends Controller
{
    protected function gelombangAktif(): ?Gelombang
    {
        $today = now()->toDateString();

        return Gelombang::where('tanggal_mulai', '<=', $today)
            ->where('tanggal_selesai', '>=', $today)
            ->orderBy('urutan')
            ->first();
    }

    public function create(): View
    {
        $gelombangAktif = $this->gelombangAktif();
        $jurusanList = Jurusan::where('is_active', true)->orderBy('urutan')->get();
        $guruList = Guru::where('is_active', true)->orderBy('nama')->get();

        return view('pages.formulir', compact('gelombangAktif', 'jurusanList', 'guruList'));
    }

    public function store(StorePendaftarRequest $request): RedirectResponse
    {
        $gelombangAktif = $this->gelombangAktif();

        if (! $gelombangAktif) {
            return redirect()
                ->route('formulir-pendaftaran.create')
                ->with('error', 'Mohon maaf, saat ini tidak ada gelombang pendaftaran yang sedang dibuka.');
        }

        $data = $request->validated();

        $pendaftar = DB::transaction(function () use ($data, $gelombangAktif) {
            $tahun = now()->year;
            $urutan = Pendaftar::whereYear('created_at', $tahun)->lockForUpdate()->count() + 1;
            $noPendaftaran = 'SPMB-' . $tahun . '-' . str_pad((string) $urutan, 3, '0', STR_PAD_LEFT);

            return Pendaftar::create([
                'no_pendaftaran' => $noPendaftaran,
                'gelombang_id' => $gelombangAktif->id,
                'jurusan_id' => $data['jurusan_id'],
                'nama_lengkap' => $data['nama_lengkap'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'agama' => $data['agama'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'asal_sekolah' => $data['asal_sekolah'],
                'nisn' => $data['nisn'],
                'nik' => $data['nik'],
                'anak_ke' => $data['anak_ke'],
                'whatsapp_siswa' => $data['whatsapp_siswa'],
                'email_siswa' => $data['email_siswa'],
                'alamat_lengkap' => $data['alamat_lengkap'],
                'desa_kelurahan' => $data['desa_kelurahan'],
                'kecamatan' => $data['kecamatan'],
                'kabupaten' => $data['kabupaten'],
                'nama_ibu' => $data['nama_ibu'],
                'pendidikan_ibu' => $data['pendidikan_ibu'],
                'pekerjaan_ibu' => $data['pekerjaan_ibu'],
                'nama_ayah' => $data['nama_ayah'],
                'pendidikan_ayah' => $data['pendidikan_ayah'],
                'pekerjaan_ayah' => $data['pekerjaan_ayah'],
                'whatsapp_ortu' => $data['whatsapp_ortu'],
                'punya_kip' => $data['punya_kip'] === 'ya',
                'nomor_kip' => $data['punya_kip'] === 'ya' ? $data['nomor_kip'] : null,
                'rekomendasi_guru_id' => $data['rekomendasi_guru_id'] ?: null,
                'status_pembayaran' => 'belum_bayar',
                'status_verifikasi_berkas' => 'menunggu',
                'hasil_seleksi' => 'menunggu',
            ]);
        });

        return redirect()->route('formulir-pendaftaran.sukses', $pendaftar->no_pendaftaran);
    }

    public function sukses(string $noPendaftaran): View
    {
        $pendaftar = Pendaftar::with(['jurusan', 'gelombang'])
            ->where('no_pendaftaran', $noPendaftaran)
            ->firstOrFail();

        $rekeningList = Rekening::where('is_active', true)->orderBy('urutan')->get();

        return view('pages.formulir-sukses', compact('pendaftar', 'rekeningList'));
    }
}