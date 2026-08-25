<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use App\Models\GaleriKategori;
use Illuminate\View\View;

class GaleriController extends Controller
{
    public function index(): View
    {
        $galeriList = Galeri::with('kategori')
            ->where('is_active', true)
            ->orderBy('urutan')
            ->get();

        $kategoriList = GaleriKategori::orderBy('urutan')->get();

        return view('pages.galeri', compact('galeriList', 'kategoriList'));
    }
}