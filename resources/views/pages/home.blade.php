@extends('layouts.app')

@section('title', 'Home - SPMB SMK Muhammadiyah Lebaksiu 2027/2028')
@section('meta_description', 'Selamat datang di SPMB Online SMK Muhammadiyah Lebaksiu. Daftar sebagai siswa baru tahun ajaran 2027/2028 sekarang juga.')

@section('content')

<header class="hero" id="home">
  <div class="hero-shape s1"></div>
  <div class="hero-shape s2"></div>
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <span class="hero-badge mb-3">
          <i class="bi bi-stars"></i>
          {{ $gelombangAktif->nama ?? 'Pendaftaran' }} Tahun Ajaran 2027/2028
        </span>
        <h1 class="mt-3 mb-3">Wujudkan Masa Depanmu di SMK Muhammadiyah Lebaksiu</h1>
        <p class="lead-text mb-4">Daftar jadi siswa baru cukup dari rumah. Isi formulir, unggah berkas, dan pantau status pendaftaranmu secara online &mdash; semua dalam satu tempat.</p>
        <div class="d-flex flex-wrap gap-3">
          <a href="#" class="btn btn-daftar btn-lg"><i class="bi bi-pencil-square me-1"></i> Daftar Sekarang</a>
          <a href="#" class="btn btn-outline-light btn-lg" style="border-radius:10px;"><i class="bi bi-book me-1"></i> Panduan Pengisian</a>
        </div>
      </div>
      <div class="col-lg-6 d-none d-lg-block">
        <img src="{{ asset('siswa.png') }}" class="hero-illustration" alt="Ilustrasi siswa SMK Muhammadiyah Lebaksiu">
      </div>
    </div>
  </div>
</header>

<div class="container">
  <div class="quick-access">
    <div class="row g-2 g-md-3">
      <div class="col-6 col-md-3">
        <a href="#" class="qa-item">
          <span class="qa-icon"><i class="bi bi-file-earmark-text"></i></span>
          <span>Isi Formulir</span>
          <small>Data calon siswa</small>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="#" class="qa-item">
          <span class="qa-icon"><i class="bi bi-receipt"></i></span>
          <span>Konfirmasi Transfer</span>
          <small>Unggah bukti bayar</small>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="#" class="qa-item">
          <span class="qa-icon"><i class="bi bi-search"></i></span>
          <span>Cek Status</span>
          <small>Lacak pendaftaran</small>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="#gelombang" class="qa-item">
          <span class="qa-icon"><i class="bi bi-calendar2-week"></i></span>
          <span>Jadwal Gelombang</span>
          <small>Biaya &amp; tanggal</small>
        </a>
      </div>
    </div>
  </div>
</div>

<section>
    <div class="container">
        <div class="row align-items-start mb-5 g-4">
        <div class="col-lg-7">
            <div class="eyebrow mb-2">Profil Sekolah</div>
            <h2 class="section-title mb-3">Mengenal SMK Muhammadiyah Lebaksiu</h2>
            <p class="section-sub">{{ $profilDeskripsi }}</p>
        </div>
        <div class="col-lg-5">
            <div class="row g-3 mt-3">
            <div class="col-6">
                <div class="profil-intro-stat">
                <i class="bi bi-calendar-check" style="font-size:1.6rem; color:var(--blue-700);"></i>
                <div>
                    <div class="profil-intro-stat-num">{{ $profilTahunBerdiri }}</div>
                    <div class="profil-intro-stat-label">Tahun Berdiri</div>
                </div>
                </div>
            </div>
            <div class="col-6">
                <div class="profil-intro-stat">
                <i class="bi bi-mortarboard-fill" style="font-size:1.6rem; color:var(--blue-700);"></i>
                <div>
                    <div class="profil-intro-stat-num">{{ $jurusanList->count() }}</div>
                    <div class="profil-intro-stat-label">Program Studi</div>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>

        <div class="row g-4">
            @foreach($jurusanList as $jurusan)
                <div class="col-md-6 col-lg-4">
                    <div class="prodi-card">   
                        <div class="prodi-card-img">
                            <span class="prodi-card-akreditasi">Akreditasi {{ $jurusan->akreditasi }}</span>
                            @if($jurusan->foto)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($jurusan->foto) }}"  alt="{{ $jurusan->nama }}">
                            @endif
                        </div>     
                        <div class="prodi-card-body">
                            <div class="prodi-card-icon"><i class="bi {{ $jurusan->icon }} me-2"></i></div>
                            <div class="prodi-card-title">{{ $jurusan->nama }}</div>          
                            <p class="prodi-card-desc">{{ $jurusan->deskripsi }}</p>
                            @if($jurusan->keunggulan)
                                <ul class="prodi-card-list">
                                @foreach($jurusan->keunggulan as $poin)
                                    <li><i class="bi bi-check-circle-fill"></i>{{ $poin }}</li>
                                @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section style="background:#fff;">
  <div class="container">
    <div class="text-center mb-5">
      <div class="eyebrow mb-2">Serba-serbi</div>
      <h2 class="section-title">Serba-serbi SPMB {{ now()->year }}</h2>
      <p class="section-sub mx-auto">Cuplikan proses penerimaan siswa baru, serta jejak alumni yang telah terjun ke dunia kerja.</p>
    </div>

    <div class="serbaserbi-shell">
      <div class="hero-shape s1"></div>
      <div class="hero-shape s2"></div>

      <div class="row g-4 position-relative" style="z-index:1;">
        <div class="col-6 col-md-3">
          <div class="serbaserbi-stat">
            <div class="serbaserbi-stat-num">{{ number_format($totalPendaftar, 0, ',', '.') }}</div>
            <div class="serbaserbi-stat-label">Pendaftar SPMB {{ now()->year }}</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="serbaserbi-stat">
            <div class="serbaserbi-stat-num">{{ $jurusanList->count() }}</div>
            <div class="serbaserbi-stat-label">Program Studi Dibuka</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="serbaserbi-stat">
            <div class="serbaserbi-stat-num">{{ $persentaseTerserap }}</div>
            <div class="serbaserbi-stat-label">Alumni Terserap Kerja</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="serbaserbi-stat">
            <div class="serbaserbi-stat-num">{{ $jumlahMitra }}</div>
            <div class="serbaserbi-stat-label">Perusahaan &amp; Instansi Mitra</div>
          </div>
        </div>
      </div>

      @if($highlightGaleri->isNotEmpty())
      <div class="serbaserbi-highlight-grid">
        @foreach($highlightGaleri as $g)
          <div class="serbaserbi-highlight-item">
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($g->foto) }}" alt="{{ $g->judul }}" loading="lazy">
            <div class="serbaserbi-highlight-caption">{{ $g->judul }}</div>
          </div>
        @endforeach
      </div>
      @endif

      <div class="text-center mt-4 position-relative" style="z-index:1;">
        <a href="#" class="btn btn-daftar">
          <i class="bi bi-images me-1"></i> Lihat Galeri Lengkap
        </a>
      </div>
    </div>

    @if($alumniHighlight->isNotEmpty())
    <div class="text-center mt-5 mb-4">
      <h5 class="fw-bold" style="color:var(--blue-900);">Jejak Alumni</h5>
      <p class="text-muted small mb-0">Sebagian alumni yang kini sudah bekerja sesuai bidang keahliannya.</p>
    </div>
    <div class="row g-4">
      @foreach($alumniHighlight as $a)
      <div class="col-6 col-lg-4">
        <div class="alumni-card">
          <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($a->foto) }}" alt="Foto {{ $a->nama }}" class="alumni-photo">
          <div class="alumni-name">{{ $a->nama }}</div>
          <span class="alumni-jurusan">{{ $a->jurusan->nama }}</span>
          <div class="alumni-meta">Lulusan Tahun {{ $a->tahun_lulus }}</div>
          <div class="alumni-work"><i class="bi bi-briefcase-fill"></i> {{ $a->tempat_kerja }}</div>
        </div>
      </div>
      @endforeach
    </div>
    @endif
  </div>
</section>

<section style="background:#fff;" id="gelombang">
  <div class="container">
    <div class="text-center mb-5">
      <div class="eyebrow mb-2">Informasi Gelombang</div>
      <h2 class="section-title">Jadwal Gelombang SPMB</h2>
    </div>
    <div class="row g-4">
      @foreach($gelombangList as $g)
      <div class="col-lg-4">
        <div class="wave-card{{ $g->is_highlight ? ' active' : '' }}">
          @if($g->ribbon_text)
            <div class="wave-ribbon">{{ $g->ribbon_text }}</div>
          @endif
          <div class="d-flex justify-content-between align-items-start mb-3">
            <h5 class="fw-bold mb-0">{{ $g->nama }}</h5>
            <span class="wave-status status-{{ $g->status_periode }}">
              {{ ['open' => 'Dibuka', 'closed' => 'Ditutup', 'soon' => 'Segera'][$g->status_periode] }}
            </span>
          </div>
          <p class="text-muted small mb-0">
            <i class="bi bi-calendar3 me-1"></i>
            {{ $g->tanggal_mulai->translatedFormat('d M Y') }} &ndash; {{ $g->tanggal_selesai->translatedFormat('d M Y') }}
          </p>
          @if($g->benefit)
            <ul class="wave-list mt-3">
              @foreach($g->benefit as $b)
                <li><i class="bi bi-check-circle-fill"></i> {{ $b }}</li>
              @endforeach
            </ul>
          @endif
          @if($g->status_periode === 'open')
            <a href="#" class="btn btn-daftar w-100">Daftar {{ $g->nama }}</a>
          @else
            <button class="btn btn-outline-secondary w-100" disabled>
              {{ $g->status_periode === 'closed' ? 'Sudah Berakhir' : 'Belum Dibuka' }}
            </button>
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>


<section>
  <div class="container">
    <div class="text-center mb-5">
      <div class="eyebrow mb-2">Cara Mendaftar</div>
      <h2 class="section-title">Alur Pendaftaran SPMB</h2>
      <p class="section-sub mx-auto">Hanya {{ $alurList->count() }} langkah mudah, semua bisa dilakukan online dari rumah.</p>
    </div>

    <div class="alur-track">
      @foreach($alurList as $i => $a)
        <div class="alur-step">
          <div class="alur-step-circle">
            <span class="alur-step-num">{{ $i + 1 }}</span>
            <i class="bi {{ $a->icon }}"></i>
          </div>
          <div class="alur-step-title">{{ $a->judul }}</div>
          <div class="alur-step-desc">{{ $a->deskripsi }}</div>
        </div>
      @endforeach
    </div>

    <div class="text-center mt-5">
      <a href="#" class="btn btn-daftar btn-lg">
        <i class="bi bi-pencil-square me-1"></i> Mulai Isi Formulir
      </a>
    </div>
  </div>
</section>

@if($faqList->isNotEmpty())
<section>
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-5">
        <div class="eyebrow mb-2">Sebelum Mendaftar</div>
        <h2 class="section-title">Pertanyaan yang Sering Ditanyakan</h2>
        <p class="section-sub">Belum ketemu jawabannya? Hubungi panitia SPMB di halaman Kontak.</p>
      </div>
      <div class="col-lg-7">
        <div class="accordion" id="faqAccordion">
          @foreach($faqList as $i => $faq)
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button{{ $i === 0 ? '' : ' collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $faq->id }}">
                {{ $faq->pertanyaan }}
              </button>
            </h2>
            <div id="faq{{ $faq->id }}" class="accordion-collapse collapse{{ $i === 0 ? ' show' : '' }}" data-bs-parent="#faqAccordion">
              <div class="accordion-body text-muted">{{ $faq->jawaban }}</div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>
@endif

@if($testimoniList->isNotEmpty())
<section style="background:#fff;">
  <div class="container">
    <div class="text-center mb-5">
      <div class="eyebrow mb-2">Kata Mereka</div>
      <h2 class="section-title">Testimoni Siswa &amp; Orang Tua</h2>
    </div>
    <div class="row g-4">
      @foreach($testimoniList as $t)
      <div class="col-md-4">
        <div class="testi-card">
          <p class="testi-quote">"{{ $t->quote }}"</p>
          <div class="d-flex align-items-center gap-2 mt-3">
            @if($t->foto)
              <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($t->foto) }}" class="testi-avatar" style="object-fit:cover;" alt="{{ $t->nama }}">
            @else
              <div class="testi-avatar">{{ \Illuminate\Support\Str::of($t->nama)->explode(' ')->map(fn($w) => mb_substr($w, 0, 1))->take(2)->implode('') }}</div>
            @endif
            <div>
              <div class="fw-bold small">{{ $t->nama }}</div>
              <div class="text-muted small">{{ $t->peran }}</div>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<section class="pt-0">
  <div class="container">
    <div class="cta-banner d-flex flex-wrap justify-content-between align-items-center gap-3">
      <div>
        <h3 class="mb-2">{{ $gelombangAktif ? "Kuota {$gelombangAktif->nama} Terbatas!" : 'Pendaftaran Segera Dibuka' }}</h3>
        <p class="mb-0">Amankan tempatmu sekarang sebelum kuota jurusan favorit penuh.</p>
      </div>
      <a href="#" class="btn btn-cta-dark">Daftar Sekarang <i class="bi bi-arrow-right ms-1"></i></a>
    </div>
  </div>
</section>

@endsection