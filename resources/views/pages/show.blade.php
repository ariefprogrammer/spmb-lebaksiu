@extends('layouts.app')

@section('title', $page->meta_title ?: $page->title . ' - SPMB SMK Muhammadiyah Lebaksiu')
@section('meta_description', $page->meta_description ?: $page->excerpt)

@section('content')

<header class="page-hero">
  <div class="hero-shape s1"></div>
  <div class="hero-shape s2"></div>
  <div class="container">
    <nav class="breadcrumb-custom mb-2">
      <a href="{{ route('home') }}">Home</a> <i class="bi bi-chevron-right mx-1" style="font-size:.7rem;"></i> {{ $page->title }}
    </nav>
    <h1>{{ $page->title }}</h1>
    @if($page->excerpt)
      <p class="lead-text mb-0">{{ $page->excerpt }}</p>
    @endif
  </div>
</header>

<section>
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-8">
        <div class="page-content">
          {!! $page->content !!}
        </div>
      </div>
      <div class="col-lg-4">
        <div class="page-sidebar-card">
          <div class="page-sidebar-title">Halaman Lainnya</div>
          <ul class="page-sidebar-list">
            @foreach($pages as $p)
              <li>
                <a href="{{ route('pages.show', $p->slug) }}"
                   class="{{ $p->slug === $page->slug ? 'active' : '' }}">
                  <i class="bi bi-file-earmark-text-fill"></i>
                  {{ $p->title }}
                </a>
              </li>
            @endforeach
          </ul>

          <div class="page-sidebar-note">
            <i class="bi bi-info-circle-fill me-1"></i>
            Punya pertanyaan lain? Hubungi kami di halaman <a href="#" class="fw-semibold">Kontak</a>.
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection