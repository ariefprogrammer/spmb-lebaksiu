<!doctype html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="@yield('meta_description', 'Sistem Penerimaan Murid Baru (SPMB) SMK Muhammadiyah Lebaksiu secara online.')">
<title>@yield('title', 'SPMB SMK Muhammadiyah Lebaksiu 2027 / 2028')</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/site.css') }}">
@stack('styles')
</head>
<body>

<div class="topbar">
  <div class="container d-flex justify-content-between align-items-center">
    <div class="d-none d-md-flex gap-3 flex-shrink-0">
      <a href="tel:+622831234567" class="d-flex align-items-center"><i class="bi bi-telephone-fill me-1"></i>(0283) 123-4567</a>
      <a href="mailto:spmb@smkmuh1lebaksiu.sch.id" class="d-flex align-items-center"><i class="bi bi-envelope-fill me-1"></i>spmb@smkmuh1lebaksiu.sch.id</a>
    </div>
    <div class="w-100 text-center text-md-end">
      <i class="bi bi-megaphone-fill me-1 text-warning"></i>
      @if(isset($gelombangAktif))
        {{ $gelombangAktif->nama }} resmi dibuka &mdash; kuota terbatas!
      @else
        Pantau jadwal gelombang pendaftaran terbaru kami.
      @endif
    </div>
  </div>
</div>

<nav class="navbar navbar-expand-lg navbar-main sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
      <span class="navbar-brand-mark">M</span>
      <span class="navbar-brand-text">
        <span class="school-name d-block">SMK Muhammadiyah Lebaksiu</span>
        <span class="school-tag">SPMB 2027 / 2028</span>
      </span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link nav-link-custom{{ request()->routeIs('home') ? ' active' : '' }}" href="{{ route('home') }}">Home</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link nav-link-custom dropdown-toggle" href="#" data-bs-toggle="dropdown">Informasi</a>
          <ul class="dropdown-menu">
            @foreach(\App\Models\Page::menu()->get() as $p)
              <li><a class="dropdown-item" href="#">{{ $p->title }}</a></li>
            @endforeach
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link nav-link-custom dropdown-toggle" href="#" data-bs-toggle="dropdown">Pendaftaran</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Isi Formulir</a></li>
            <li><a class="dropdown-item" href="#">Konfirmasi Transfer</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link nav-link-custom" href="#">Guru</a></li>
        <li class="nav-item"><a class="nav-link nav-link-custom" href="#">Galeri</a></li>
        <li class="nav-item"><a class="nav-link nav-link-custom" href="#">Kontak</a></li>
      </ul>
      <div class="d-flex gap-2">
        <a href="#" class="btn btn-login">Login</a>
        <a href="#" class="btn btn-daftar">Daftar Sekarang</a>
      </div>
    </div>
  </div>
</nav>

@yield('content')

<footer class="footer-main" id="kontak">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="d-flex align-items-center gap-2 mb-3">
          <span class="navbar-brand-mark">M</span>
          <span class="fw-bold text-white">SMK Muhammadiyah Lebaksiu</span>
        </div>
        <p class="small">Mencetak generasi terampil, mandiri, dan berakhlak mulia melalui pendidikan kejuruan berkualitas.</p>
        <div class="d-flex gap-2 mt-3">
          <a href="#" class="footer-social"><i class="bi bi-instagram"></i></a>
          <a href="#" class="footer-social"><i class="bi bi-facebook"></i></a>
          <a href="#" class="footer-social"><i class="bi bi-youtube"></i></a>
          <a href="#" class="footer-social"><i class="bi bi-whatsapp"></i></a>
        </div>
      </div>
      <div class="col-lg-2 col-6">
        <h6>Tautan</h6>
        <ul>
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><a href="#">Galeri</a></li>
          <li><a href="#">Pendaftaran</a></li>
          <li><a href="#">Kontak</a></li>
        </ul>
      </div>
      <div class="col-lg-3 col-6">
        <h6>Informasi SPMB</h6>
        <ul>
          @foreach(\App\Models\Page::menu()->get() as $p)
            <li><a href="#">{{ $p->title }}</a></li>
          @endforeach
        </ul>
      </div>
      <div class="col-lg-3">
        <h6>Kontak Panitia</h6>
        <ul>
          <li><i class="bi bi-geo-alt-fill me-2"></i>Jl. Raya Lebaksiu, Kab. Tegal, Jawa Tengah</li>
          <li><i class="bi bi-telephone-fill me-2"></i>(0283) 123-4567</li>
          <li><i class="bi bi-envelope-fill me-2"></i>spmb@smkmuh1lebaksiu.sch.id</li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom d-flex flex-wrap justify-content-between gap-2">
      <div>&copy; {{ date('Y') }} SMK Muhammadiyah Lebaksiu. Seluruh hak cipta dilindungi.</div>
      <div>Panitia SPMB Tahun Ajaran 2027/2028</div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>