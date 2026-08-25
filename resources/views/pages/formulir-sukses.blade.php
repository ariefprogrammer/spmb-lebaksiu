@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil - SPMB SMK Muhammadiyah Lebaksiu')

@section('content')

<header class="page-hero">
  <div class="hero-shape s1"></div>
  <div class="hero-shape s2"></div>
  <div class="container">
    <nav class="breadcrumb-custom mb-2">
      <a href="{{ route('home') }}">Home</a> <i class="bi bi-chevron-right mx-1" style="font-size:.7rem;"></i> Pendaftaran Berhasil
    </nav>
    <h1>Pendaftaran Berhasil Dikirim!</h1>
    <p class="lead-text mb-0">Simpan nomor pendaftaranmu, kamu akan membutuhkannya untuk konfirmasi transfer dan cek status.</p>
  </div>
</header>

<section>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">

        <div class="page-sidebar-card text-center mb-4">
          <i class="bi bi-check-circle-fill" style="font-size:3rem; color:var(--blue-700);"></i>
          <div class="mt-3 mb-1 text-muted">Nomor Pendaftaran Kamu</div>
          <h2 class="fw-bold" style="color:var(--blue-900); letter-spacing:1px;">{{ $pendaftar->no_pendaftaran }}</h2>
          <div class="mt-3">
            <div>Nama: <strong>{{ $pendaftar->nama_lengkap }}</strong></div>
            <div>Jurusan: <strong>{{ $pendaftar->jurusan->nama }}</strong></div>
            <div>Gelombang: <strong>{{ $pendaftar->gelombang->nama }}</strong></div>
          </div>
        </div>

        <div class="page-sidebar-card mb-4">
          <div class="page-sidebar-title">Langkah Selanjutnya</div>
          <ol class="ps-3">
            <li class="mb-2">Transfer biaya formulir sesuai gelombang ke salah satu rekening resmi di bawah ini.</li>
            <li class="mb-2">Unggah bukti transfer pada menu <strong>Konfirmasi Transfer</strong> menggunakan nomor pendaftaran di atas.</li>
            <li>Panitia akan memverifikasi berkas &amp; pembayaran maksimal 2x24 jam kerja.</li>
          </ol>
        </div>

        @if($rekeningList->isNotEmpty())
        <div class="page-sidebar-card mb-4">
          <div class="page-sidebar-title">Rekening Resmi Sekolah</div>
          @foreach($rekeningList as $rek)
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
              <div>
                <div class="fw-bold">{{ $rek->nama_bank }}</div>
                <div class="text-muted small">a.n. {{ $rek->atas_nama }}</div>
              </div>
              <div class="fw-bold">{{ $rek->no_rekening }}</div>
            </div>
          @endforeach
        </div>
        @endif

        <div class="text-center">
          <a href="{{ route('home') }}" class="btn btn-outline-secondary" style="border-radius:10px;">
            <i class="bi bi-house me-1"></i> Kembali ke Beranda
          </a>
        </div>

      </div>
    </div>
  </div>
</section>

@endsection