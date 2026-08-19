<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\AlurPendaftaran;
use App\Models\Faq;
use App\Models\Galeri;
use App\Models\Gelombang;
use App\Models\Jurusan;
use App\Models\Testimoni;
use App\Models\Pengaturan;
use App\Models\Pendaftar;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $today = now()->toDateString();

        $gelombangList = Gelombang::orderBy('urutan')->get()->map(function (Gelombang $g) use ($today) {
            $g->status_periode = match (true) {
                $today < $g->tanggal_mulai->toDateString() => 'soon',
                $today > $g->tanggal_selesai->toDateString() => 'closed',
                default => 'open',
            };

            return $g;
        });

        $jurusanList = Jurusan::where('is_active', true)
            ->orderBy('urutan')
            ->get();

        $testimoniList = Testimoni::where('is_active', true)
            ->orderBy('urutan')
            ->get();

        $gelombangAktif = $gelombangList->firstWhere('status_periode', 'open');

        $profilTahunBerdiri = Pengaturan::get('profil_tahun_berdiri', '1998');
        $profilDeskripsi = Pengaturan::get('profil_deskripsi', 'SMK Muhammadiyah Lebaksiu berkomitmen mencetak lulusan yang terampil, mandiri, dan siap kerja.');
        $totalPendaftar = Pendaftar::count();
        $persentaseTerserap = Pengaturan::get('serbaserbi_persentase_terserap', '86%');
        $jumlahMitra = Pengaturan::get('serbaserbi_jumlah_mitra', '40+');

        $highlightGaleri = Galeri::where('is_active', true)
            ->whereHas('kategori', fn ($q) => $q->whereIn('slug', ['spmb', 'prestasi']))
            ->orderBy('urutan')
            ->limit(4)
            ->get();

        $alumniHighlight = Alumni::with('jurusan')
            ->where('is_featured', true)
            ->orderBy('urutan')
            ->limit(6)
            ->get();

        $alurList = AlurPendaftaran::orderBy('urutan')->get();

        $faqList = Faq::where('is_active', true)->orderBy('urutan')->get();

        return view('pages.home', compact(
            'gelombangList',
            'jurusanList',
            'testimoniList',
            'gelombangAktif',
            'profilTahunBerdiri',
            'profilDeskripsi',
            'totalPendaftar',
            'persentaseTerserap',
            'jumlahMitra',
            'highlightGaleri',
            'alumniHighlight',
            'alurList',
            'faqList'
        ));

    }
}