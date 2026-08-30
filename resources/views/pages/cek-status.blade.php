@extends('layouts.app')

@section('title', 'Cek Status Pendaftaran - SPMB SMK Muhammadiyah Lebaksiu')
@section('meta_description', 'Lacak status pendaftaran SPMB SMK Muhammadiyah Lebaksiu menggunakan nomor pendaftaran atau nomor WhatsApp.')

@php
$steps = ['Formulir Terkirim', 'Konfirmasi Pembayaran', 'Verifikasi Panitia', 'Pengumuman Hasil'];
$stepIcons = ['bi-file-earmark-check-fill', 'bi-receipt', 'bi-clipboard-check-fill', 'bi-megaphone-fill'];
$totalSteps = count($steps);
$highlightCount = $hasil && $stage < $totalSteps ? $stage + 1 : $totalSteps;
$fillPercent = $totalSteps > 1 ? (($highlightCount - 1) / ($totalSteps - 1)) * 100 : 0;
@endphp

@section('content')

<header class="page-hero">
  <div class="hero-shape s1"></div>
  <div class="hero-shape s2"></div>
  <div class="container">
    <nav class="breadcrumb-custom mb-2">
      <a href="{{ route('home') }}">Home</a> <i class="bi bi-chevron-right mx-1" style="font-size:.7rem;"></i> Cek Status
    </nav>
    <h1>Cek Status Pendaftaran</h1>
    <p class="lead-text mb-0">Pantau perkembangan pendaftaranmu, mulai dari formulir terkirim hingga pengumuman hasil.</p>
  </div>
</header>

<section class="pb-0">
  <div class="container">
    <div class="status-search-card">
      <div class="status-search-icon"><i class="bi bi-search"></i></div>
      <h5 class="fw-bold text-center mb-1" style="color:var(--blue-900);">Cek Status Pendaftaran</h5>
      <p class="text-muted small text-center mb-4">Masukkan nomor pendaftaran atau nomor WhatsApp yang digunakan saat mendaftar.</p>

      <form action="{{ route('cek-status.index') }}" method="GET">
        <div class="mb-3">
          <label class="form-label-custom" for="cariStatus">Nomor Pendaftaran / Nomor WhatsApp</label>
          <input type="text" class="form-control-custom" id="cariStatus" name="cari"
                 value="{{ $cariInput }}"
                 placeholder="Contoh: SPMB-2026-001 atau 081234567890" required>
        </div>
        <button type="submit" class="btn btn-daftar w-100">
          <i class="bi bi-search me-1"></i> Cek Status
        </button>
      </form>
    </div>
  </div>
</section>

<section class="pt-4">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">

        @if(!$sudahCari)
          <div class="status-empty-state">
            <div class="status-empty-icon"><i class="bi bi-clipboard-data"></i></div>
            <h6 class="fw-bold" style="color:var(--blue-900);">Belum Ada Pencarian</h6>
            <p class="text-muted small mb-0">Masukkan nomor pendaftaran atau nomor WhatsApp pada form di atas untuk melihat status terbaru.</p>
          </div>

        @elseif(!$hasil)
          <div class="status-notfound-card">
            <i class="bi bi-exclamation-triangle-fill mb-2" style="font-size:1.6rem;"></i>
            <h6 class="fw-bold mb-1">Data Tidak Ditemukan</h6>
            <p class="small mb-0">
              Nomor <strong>{{ $cariInput }}</strong> tidak terdaftar dalam sistem kami.
              Pastikan nomor yang dimasukkan sudah benar, atau hubungi panitia melalui halaman
              <a href="#" class="fw-semibold">Kontak</a>.
            </p>
          </div>

        @else
          <div class="status-summary-card">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
              <div>
                <div class="fw-bold" style="color:var(--blue-900); font-size:1.1rem;">{{ $hasil->nama_lengkap }}</div>
                <div class="text-muted small">No. Pendaftaran: {{ $hasil->no_pendaftaran }}</div>
              </div>
              <span class="status-badge {{ $badge['class'] }}"><i class="bi {{ $badge['icon'] }}"></i> {{ $badge['text'] }}</span>
            </div>

            <div class="status-summary-row">
              <span class="label">Pilihan Jurusan</span>
              <span class="value">{{ $hasil->jurusan->nama }}</span>
            </div>
            <div class="status-summary-row">
              <span class="label">Gelombang</span>
              <span class="value">{{ $hasil->gelombang->nama }}</span>
            </div>
            <div class="status-summary-row">
              <span class="label">Tanggal Daftar</span>
              <span class="value">{{ $hasil->created_at->translatedFormat('d F Y') }}</span>
            </div>
          </div>

          <div class="status-tracker" id="hasilPencarian">
            <div class="status-track">
              <div class="status-track-fill" style="width:{{ $fillPercent }}%;"></div>
              @foreach($steps as $i => $label)
                @php
                  $stepNum = $i + 1;
                  $cls = $stepNum <= $stage ? 'done' : ($stepNum === $stage + 1 ? 'active' : '');
                @endphp
                <div class="status-track-item {{ $cls }}">
                  <div class="status-track-circle">
                    @if($stepNum <= $stage)
                      <i class="bi bi-check-lg"></i>
                    @else
                      <i class="bi {{ $stepIcons[$i] }}"></i>
                    @endif
                  </div>
                  <div class="status-track-label">{{ $label }}</div>
                </div>
              @endforeach
            </div>

            @php
              $noteClass = 'note-default';
              if ($stage === 4 && $hasil->hasil_seleksi === 'diterima') {
                  $noteClass = 'note-success';
              } elseif ($stage < 2) {
                  $noteClass = 'note-warning';
              }
            @endphp
            <div class="status-note-box {{ $noteClass }}">
              <i class="bi bi-info-circle-fill"></i>
              <div>{{ $hasil->catatanUntukPendaftar() }}</div>
            </div>

            @if($stage < 2)
              <a href="{{ route('konfirmasi-transfer.create') }}" class="btn btn-daftar w-100 mt-3">
                <i class="bi bi-credit-card-2-front-fill me-1"></i> Konfirmasi Pembayaran Sekarang
              </a>
            @endif

            @if($hasil->hasil_seleksi === 'diterima' && $hasil->catatan_link)
              <a href="{{ $hasil->catatan_link }}" target="_blank" rel="noopener" class="btn btn-daftar w-100 mt-3">
                <i class="bi bi-whatsapp me-1"></i> Join Grup WhatsApp Siswa Diterima
              </a>
            @endif
          </div>
        @endif

      </div>
    </div>
  </div>    
</section>

@endsection