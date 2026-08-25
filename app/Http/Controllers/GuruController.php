<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jurusan;
use Illuminate\View\View;

class GuruController extends Controller
{
    public function index(): View
    {
        $guruList = Guru::with('jurusan')
            ->where('is_active', true)
            ->orderBy('urutan')
            ->get();

        $jurusanList = Jurusan::where('is_active', true)->orderBy('urutan')->get();

        $adaGuruUmum = $guruList->contains(function (Guru $g) {
            return ! $g->is_pimpinan && ! $g->jurusan_id;
        });

        return view('pages.guru', compact('guruList', 'jurusanList', 'adaGuruUmum'));
    }
}