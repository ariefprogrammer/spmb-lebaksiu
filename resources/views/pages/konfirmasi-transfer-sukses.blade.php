@extends('layouts.app')

@section('title', 'Konfirmasi Terkirim - SPMB SMK Muhammadiyah Lebaksiu')

@section('content')

<header class="page-hero">
  <div class="hero-shape s1"></div>
  <div class="hero-shape s2"></div>
  <div class="container">
    <nav class="breadcrumb-custom mb-2">
      <a href="{{ route('home') }}">Home</a> <i class="bi bi-chevron-right mx-1" style="font-size:.7rem;"></i> Konfirmasi Terkirim
    </nav>
    <h1>Konfirmasi Transfer Terkirim!</h1>
    <p class="lead-text mb-0">Bukti transfer kamu sudah kami terima dan akan segera diverifikasi panitia.</p>
  </div>
</header>

<section>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">

        <div class="page-sidebar-card text-center mb-4">
          <i class="bi bi-hourglass-split" style="font-size:3rem; color:var(--blue-700);"></i>
          <div class="mt-3 mb-1 text-muted">Status Konfirmasi</div>
          <h2 class="fw-bold" style="color:var(--blue-900);">Menunggu Verifikasi</h2>
          <div class="mt-3">
            <div>Nomor Pendaftaran: <strong>{{ $pembayaran->pendaftar->no_pendaftaran }}</strong></div>
            <div>Nominal: <strong>Rp{{ number_format($pembayaran->nominal_transfer, 0, ',', '.') }}</strong></div>
            <div>Tanggal Transfer: <strong>{{ $pembayaran->tanggal_transfer->translatedFormat('d M Y') }}</strong></div>
          </div>
        </div>

        <div class="page-sidebar-card mb-4">
          <p class="mb-0 text-muted small">
            <i class="bi bi-info-circle-fill me-1"></i>
            Panitia akan memverifikasi maksimal 2x24 jam kerja. Kamu bisa memantau status pendaftaran melalui menu <a href="#" class="fw-semibold">Cek Status</a> menggunakan nomor pendaftaranmu.
          </p>
        </div>

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