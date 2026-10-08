<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use App\Models\GaleriKategori;
use Illuminate\View\View;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    public function index(Request $request): View
    {
        $galeriList = Galeri::with('kategori')
            ->where('is_active', true)
            ->whereHas('kategori')
            ->orderBy('urutan')
            ->orderByDesc('id')
            ->get();

        $kategoriList = GaleriKategori::whereHas('galeri', fn ($q) => $q->where('is_active', true))
            ->orderBy('urutan')
            ->get();

        $kategoriAktif = $kategoriList->firstWhere('slug', $request->query('kategori'));

        return view('pages.galeri', compact('galeriList', 'kategoriList', 'kategoriAktif'));
    }
}