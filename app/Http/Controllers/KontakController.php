<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePesanKontakRequest;
use App\Models\Pengaturan;
use App\Models\PesanKontak;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KontakController extends Controller
{
    public function index(): View
    {
        $telepon = Pengaturan::get('kontak_telepon', '(0283) 123-4567');
        $email = Pengaturan::get('kontak_email', 'spmb@smkmuh1lebaksiu.sch.id');
        $whatsappCs = Pengaturan::get('kontak_whatsapp_cs', '6288219918654');
        $alamat = Pengaturan::get('alamat_sekolah', 'Jl. Raya Lebaksiu, Kec. Lebaksiu, Kab. Tegal, Jawa Tengah 52461');
        $jamWeekday = Pengaturan::get('jam_operasional_weekday', 'Senin &ndash; Jumat: 07.00 &ndash; 15.00 WIB');
        $jamSabtu = Pengaturan::get('jam_operasional_sabtu', 'Sabtu: 08.00 &ndash; 12.00 WIB');

        $kontakInfoList = [
            [
                'icon' => 'bi-geo-alt-fill',
                'title' => 'Alamat Sekolah',
                'value' => $alamat,
            ],
            [
                'icon' => 'bi-telephone-fill',
                'title' => 'Telepon / WhatsApp',
                'value' => $telepon . '<br><a href="https://wa.me/' . $whatsappCs . '" target="_blank" rel="noopener">' . $whatsappCs . '</a>',
            ],
            [
                'icon' => 'bi-envelope-fill',
                'title' => 'Email',
                'value' => '<a href="mailto:' . $email . '">' . $email . '</a>',
            ],
            [
                'icon' => 'bi-clock-fill',
                'title' => 'Jam Operasional',
                'value' => $jamWeekday . '<br>' . $jamSabtu,
            ],
        ];

        return view('pages.kontak', compact('kontakInfoList', 'whatsappCs', 'email'));
    }

    public function store(StorePesanKontakRequest $request): RedirectResponse
    {
        PesanKontak::create($request->validated());

        return redirect()
            ->route('kontak.index')
            ->with('success', 'Pesan kamu sudah terkirim. Tim panitia akan membalas secepatnya.');
    }
}