@extends('layouts.app')

@section('title', 'Guru & Tenaga Pendidik - SPMB SMK Muhammadiyah Lebaksiu')
@section('meta_description', 'Kenali guru dan tenaga pendidik profesional SMK Muhammadiyah Lebaksiu beserta mata pelajaran yang diampu.')

@section('content')

<header class="page-hero">
  <div class="hero-shape s1"></div>
  <div class="hero-shape s2"></div>
  <div class="container">
    <nav class="breadcrumb-custom mb-2">
      <a href="{{ route('home') }}">Home</a> <i class="bi bi-chevron-right mx-1" style="font-size:.7rem;"></i> Guru
    </nav>
    <h1>Guru & Tenaga Pendidik</h1>
    <p class="lead-text mb-0">Dibimbing langsung oleh guru-guru profesional dan berpengalaman di bidangnya masing-masing.</p>
  </div>
</header>

<section>
  <div class="container">
    <div class="text-center mb-4">
      <div class="eyebrow mb-2">Tenaga Pendidik</div>
      <h2 class="section-title">Guru &amp; Pengajar Kami</h2>
      <p class="section-sub mx-auto">Dibimbing oleh guru profesional dan bersertifikat kompetensi di bidangnya masing-masing.</p>
    </div>

    <div class="d-flex flex-wrap justify-content-center gap-2 mb-5" id="filterGuru">
      <button type="button" class="filter-chip active" data-filter="semua">Semua Guru</button>
      <button type="button" class="filter-chip" data-filter="pimpinan">Pimpinan</button>
      @foreach($jurusanList as $jurusan)
        <button type="button" class="filter-chip" data-filter="{{ $jurusan->slug }}">{{ $jurusan->nama }}</button>
      @endforeach
      @if($adaGuruUmum)
        <button type="button" class="filter-chip" data-filter="umum">Normatif &amp; Adaptif</button>
      @endif
    </div>

    <div class="row g-4" id="gridGuru">
      @foreach($guruList as $guru)
        @php
          $kategori = $guru->is_pimpinan ? 'pimpinan' : ($guru->jurusan_id ? $guru->jurusan->slug : 'umum');
          $fotoUrl = $guru->foto
              ? \Illuminate\Support\Facades\Storage::disk('public')->url($guru->foto)
              : 'https://ui-avatars.com/api/?name=' . urlencode($guru->nama) . '&background=1d4ed8&color=fff&size=256';
        @endphp
        <div class="col-6 col-md-4 col-lg-3 guru-item" data-kategori="{{ $kategori }}">
          <div class="guru-card">
            <div class="guru-photo-wrap">
              @if($guru->jabatan)
                <span class="guru-jabatan-badge">{{ $guru->jabatan }}</span>
              @endif
              <img src="{{ $fotoUrl }}" alt="Foto {{ $guru->nama }}" class="guru-photo">
            </div>
            <div class="guru-body">
              <div class="guru-name">{{ $guru->nama }}</div>
              <span class="guru-mapel">{{ $guru->mapel }}</span>
              <!-- <div class="guru-social">
                <a href="#" aria-label="Email"><i class="bi bi-envelope-fill"></i></a>
                <a href="#" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
              </div> -->
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <p class="text-center text-muted small mt-4 d-none" id="emptyGuru">Belum ada guru pada kategori ini.</p>
  </div>
</section>

@include('partials.cta')

@endsection

@push('scripts')
<script>
  (function () {
    var chips = document.querySelectorAll('#filterGuru .filter-chip');
    var items = document.querySelectorAll('#gridGuru .guru-item');
    var empty = document.getElementById('emptyGuru');

    chips.forEach(function (chip) {
      chip.addEventListener('click', function () {
        chips.forEach(function (c) { c.classList.remove('active'); });
        chip.classList.add('active');

        var filter = chip.getAttribute('data-filter');
        var visibleCount = 0;

        items.forEach(function (item) {
          var match = filter === 'semua' || item.getAttribute('data-kategori') === filter;
          item.style.display = match ? '' : 'none';
          if (match) visibleCount++;
        });

        empty.classList.toggle('d-none', visibleCount > 0);
      });
    });
  })();
</script>
@endpush