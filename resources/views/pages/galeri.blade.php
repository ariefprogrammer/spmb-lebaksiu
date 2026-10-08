@extends('layouts.app')

@section('title', ($kategoriAktif ? 'Galeri ' . $kategoriAktif->nama : 'Galeri') . ' - SPMB SMK Muhammadiyah Lebaksiu')
@section('meta_description', $kategoriAktif
    ? 'Dokumentasi kategori ' . $kategoriAktif->nama . ' SMK Muhammadiyah Lebaksiu.'
    : 'Dokumentasi kegiatan belajar, ekstrakurikuler, fasilitas, prestasi, dan acara sekolah SMK Muhammadiyah Lebaksiu.')

@section('content')

<header class="page-hero">
  <div class="hero-shape s1"></div>
  <div class="hero-shape s2"></div>
  <div class="container">
    <nav class="breadcrumb-custom mb-2">
      <a href="{{ route('home') }}">Home</a> <i class="bi bi-chevron-right mx-1" style="font-size:.7rem;"></i> Galeri
    </nav>
    <h1>Galeri Sekolah</h1>
    <p class="lead-text mb-0">Dokumentasi kegiatan belajar, ekstrakurikuler, fasilitas, hingga prestasi siswa SMK Muhammadiyah Lebaksiu.</p>
  </div>
</header>

<section>
  <div class="container">
    <div class="text-center mb-4">
      <div class="eyebrow mb-2">Dokumentasi</div>
      <h2 class="section-title">Momen &amp; Kegiatan di Sekolah Kami</h2>
      <p class="section-sub mx-auto">Klik salah satu foto untuk melihat tampilan lebih besar.</p>
    </div>

    {{-- Filter kategori: berupa link agar bisa disalin/dibagikan --}}
    <div class="d-flex flex-wrap justify-content-center gap-2 mb-5" id="filterGaleri"
         data-aktif="{{ $kategoriAktif->slug ?? 'semua' }}">
      <a href="{{ request()->url() }}"
         class="filter-chip {{ $kategoriAktif ? '' : 'active' }}"
         style="text-decoration:none;"
         data-filter="semua">Semua</a>
      @foreach($kategoriList as $kategori)
        <a href="{{ request()->url() }}?kategori={{ $kategori->slug }}"
           class="filter-chip {{ $kategoriAktif && $kategoriAktif->slug === $kategori->slug ? 'active' : '' }}"
           style="text-decoration:none;"
           data-filter="{{ $kategori->slug }}">{{ $kategori->nama }}</a>
      @endforeach
    </div>

    <div class="galeri-grid" id="gridGaleri">
      @foreach($galeriList as $item)
        @php
          $fotoUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($item->foto);
          $sembunyi = $kategoriAktif && $item->kategori->slug !== $kategoriAktif->slug;
        @endphp
        <div class="galeri-item"
             @if($sembunyi) style="display:none;" @endif
             data-kategori="{{ $item->kategori->slug }}"
             data-bs-toggle="modal"
             data-bs-target="#galeriModal"
             data-img="{{ $fotoUrl }}"
             data-judul="{{ $item->judul }}"
             data-label="{{ $item->kategori->nama }}">
          <span class="galeri-badge">{{ $item->kategori->nama }}</span>
          <span class="galeri-view-icon"><i class="bi bi-zoom-in"></i></span>
          <img src="{{ $fotoUrl }}" alt="{{ $item->judul }}" loading="lazy">
          <div class="galeri-overlay">
            <div class="galeri-caption">{{ $item->judul }}</div>
            <div class="galeri-sub">{{ $item->kategori->nama }}</div>
          </div>
        </div>
      @endforeach
    </div>

    <p class="text-center text-muted small mt-4 d-none" id="emptyGaleri">Belum ada foto pada kategori ini.</p>
  </div>
</section>

<div class="modal fade" id="galeriModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="galeri-modal-imgwrap">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        <img id="galeriModalImg" src="" alt="">
      </div>
      <div class="galeri-modal-caption">
        <div class="fw-bold" id="galeriModalJudul"></div>
        <div class="small" id="galeriModalLabel" style="color:#D6E5FA;"></div>
      </div>
    </div>
  </div>
</div>

@include('partials.cta')

@endsection

@push('scripts')
<script>
  (function () {
    var wrapper = document.getElementById('filterGaleri');
    var chips = document.querySelectorAll('#filterGaleri .filter-chip');
    var items = document.querySelectorAll('#gridGaleri .galeri-item');
    var empty = document.getElementById('emptyGaleri');

    // ---------- Filter kategori ----------
    function terapkanFilter(filter) {
      var visibleCount = 0;

      chips.forEach(function (c) {
        c.classList.toggle('active', c.getAttribute('data-filter') === filter);
      });

      items.forEach(function (item) {
        var match = filter === 'semua' || item.getAttribute('data-kategori') === filter;
        item.style.display = match ? '' : 'none';
        if (match) visibleCount++;
      });

      empty.classList.toggle('d-none', visibleCount > 0);
    }

    function urlUntuk(filter) {
      var url = new URL(window.location.href);
      if (filter === 'semua') {
        url.searchParams.delete('kategori');
      } else {
        url.searchParams.set('kategori', filter);
      }
      return url.toString();
    }

    chips.forEach(function (chip) {
      chip.addEventListener('click', function (e) {
        e.preventDefault();
        var filter = chip.getAttribute('data-filter');
        terapkanFilter(filter);
        history.replaceState(null, '', urlUntuk(filter));
      });
    });

    terapkanFilter(wrapper.getAttribute('data-aktif'));

    var galeriModal = document.getElementById('galeriModal');
    galeriModal.addEventListener('show.bs.modal', function (event) {
      var trigger = event.relatedTarget;
      if (!trigger) return;

      document.getElementById('galeriModalImg').src = trigger.getAttribute('data-img');
      document.getElementById('galeriModalJudul').textContent = trigger.getAttribute('data-judul');
      document.getElementById('galeriModalLabel').textContent = trigger.getAttribute('data-label');
    });
  })();
</script>
@endpush